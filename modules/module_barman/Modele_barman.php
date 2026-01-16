<?php
include_once 'connexion/Connexion.php';
include_once 'modules/module_staff/modele_staff.php';

class ModeleBarman extends ModeleStaff {

    public function listerProduits() {
        try {
            $requete = self::getBdd()->prepare('
                SELECT id, nom, prix, quantiteActuelle as disponibilite 
                FROM produit 
                ORDER BY nom
            ');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur liste produits: " . $e->getMessage());
            return [];
        }
    }

    public function listerCommandesEnCours() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT v.id as commande_id, c.prenom, c.nom, v.date_vente, v.montant_total, v.statut
            FROM vente v 
            JOIN compte c ON v.compte_id = c.id 
            WHERE v.statut = "payee" AND DATE(v.date_vente) = CURDATE()
            ORDER BY v.id DESC
        ');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur liste commandes: " . $e->getMessage());
            return [];
        }
    }

    public function getCommande($id) {
        try {
            $requete = self::getBdd()->prepare('
                SELECT v.id as commande_id, c.prenom, c.nom, v.date_vente, v.montant_total 
                FROM vente v 
                JOIN compte c ON v.compte_id = c.id 
                WHERE v.id = ?
            ');
            $requete->execute([$id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur get commande: " . $e->getMessage());
            return false;
        }
    }

    public function getHistoriqueCommandes() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT 
                v.id as commande_id, 
                v.date_vente, 
                v.montant_total,
                COALESCE(v.statut, "payee") as statut,
                c.id as client_id,
                c.prenom, 
                c.nom
            FROM vente v 
            JOIN compte c ON v.compte_id = c.id 
            ORDER BY v.id DESC
        ');
            $requete->execute();
            $commandes = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($commandes as &$commande) {
                $datetime = new DateTime($commande['date_vente']);
                $commande['date_heure_affichage'] = $datetime->format('d/m/Y à H:i');

                $commande['statut_affichage'] = $this->getStatutAffichage($commande['statut']);
            }

            return $commandes;
        } catch (PDOException $e) {
            error_log("Erreur récupération historique commandes: " . $e->getMessage());
            return [];
        }
    }

    private function getStatutAffichage($statut) {
        $statuts = [
            'payee' => 'Terminée',
            'annulee' => 'Annulée',
            'en_attente' => 'En attente',
            'echouee' => 'Échouée'
        ];

        return $statuts[$statut] ?? 'Terminée';
    }

    public function getProduitsCommande($id) {
        try {
            $requete = self::getBdd()->prepare('
                SELECT p.nom, c.quantite, c.prix_unitaire 
                FROM contient c 
                JOIN produit p ON c.produit_id = p.id 
                WHERE c.vente_id = ?
            ');
            $requete->execute([$id]);
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur get produits commande: " . $e->getMessage());
            return [];
        }
    }

    public function getInfoProduit($produit_id) {
        try {
            $requete = self::getBdd()->prepare('
                SELECT id, nom, prix, quantiteActuelle as disponibilite 
                FROM produit 
                WHERE id = ?
            ');
            $requete->execute([$produit_id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération info produit: " . $e->getMessage());
            return false;
        }
    }

    public function getSoldeClient($client_id) {
        try {
            $requete = self::getBdd()->prepare('SELECT solde FROM compte WHERE id = ?');
            $requete->execute([$client_id]);
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result ? (float)$result['solde'] : false;
        } catch (PDOException $e) {
            error_log("Erreur récupération solde client: " . $e->getMessage());
            return false;
        }
    }

    public function creerTransaction($produits, $compte_id, $montant_total) {
        try {
            self::getBdd()->beginTransaction();

            if (!$this->verifierCompteExiste($compte_id)) {
                error_log("Compte non trouvé lors de la transaction: " . $compte_id);
                self::getBdd()->rollBack();
                return false;
            }

            if (!$this->debiterCompte($compte_id, $montant_total)) {
                error_log("Échec du débit pour le compte: " . $compte_id);
                self::getBdd()->rollBack();
                return false;
            }

            $date = date("Y-m-d");
            $requete = self::getBdd()->prepare("
                INSERT INTO vente (date_vente, montant_total, compte_id, statut) 
                VALUES(?, ?, ?, 'payee')
            ");
            $requete->execute([$date, $montant_total, $compte_id]);
            $vente_id = self::getBdd()->lastInsertId();

            $this->insererProduitsVente($vente_id, $produits);

            $this->mettreAJourStocks($produits);

            self::getBdd()->commit();
            return $vente_id;

        } catch (PDOException $e) {
            self::getBdd()->rollBack();
            error_log("Erreur dans la création d'une Transaction: " . $e->getMessage());
            return false;
        }
    }

    private function verifierCompteExiste($compte_id) {
        try {
            $requete = self::getBdd()->prepare('SELECT id FROM compte WHERE id = ?');
            $requete->execute([$compte_id]);
            return $requete->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erreur vérification compte: " . $e->getMessage());
            return false;
        }
    }

    private function debiterCompte($compte_id, $montant) {
        try {

            $requeteCheck = self::getBdd()->prepare('SELECT solde FROM compte WHERE id = ?');
            $requeteCheck->execute([$compte_id]);
            $result = $requeteCheck->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                error_log("Compte non trouvé: " . $compte_id);
                return false;
            }

            $soldeActuel = (float)$result['solde'];

            if ($soldeActuel < $montant) {
                error_log("Solde insuffisant. Compte: $compte_id, Solde: $soldeActuel, Montant: $montant");
                return false;
            }

            $requete = self::getBdd()->prepare('UPDATE compte SET solde = solde - ? WHERE id = ?');
            $success = $requete->execute([$montant, $compte_id]);

            if ($success) {
                error_log("Débit effectué. Compte: $compte_id, Montant débité: $montant");
            }

            return $success;

        } catch (PDOException $e) {
            error_log("Erreur PDO lors du débit: " . $e->getMessage());
            return false;
        }
    }

    private function insererProduitsVente($vente_id, $produits) {
        try {
            $requeteContient = self::getBdd()->prepare("
                INSERT INTO contient (produit_id, vente_id, quantite, prix_unitaire) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($produits as $produit) {
                $requeteContient->execute([
                    $produit['id'],
                    $vente_id,
                    $produit['quantite'],
                    $produit['prix']
                ]);
            }
        } catch (PDOException $e) {
            error_log("Erreur insertion produits vente: " . $e->getMessage());
            throw $e;
        }
    }

    public function getDerniereTransaction() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT v.id as transaction_id, v.date_vente, v.montant_total, v.statut, 
                   c.id as client_id, c.nom, c.prenom, c.solde
            FROM vente v 
            JOIN compte c ON v.compte_id = c.id 
            WHERE v.statut != "annulee" OR v.statut IS NULL
            ORDER BY v.id DESC 
            LIMIT 1
        ');
            $requete->execute();
            $transaction = $requete->fetch(PDO::FETCH_ASSOC);

            if ($transaction) {
                $requeteProduits = self::getBdd()->prepare('
                SELECT p.id, p.nom, c.quantite, c.prix_unitaire 
                FROM contient c 
                JOIN produit p ON c.produit_id = p.id 
                WHERE c.vente_id = ?
            ');
                $requeteProduits->execute([$transaction['transaction_id']]);
                $transaction['produits'] = $requeteProduits->fetchAll(PDO::FETCH_ASSOC);
            }

            return $transaction;
        } catch (PDOException $e) {
            error_log("Erreur récupération dernière transaction: " . $e->getMessage());
            return false;
        }
    }

    public function annulerTransaction($transaction_id) {
        try {
            self::getBdd()->beginTransaction();

            $requeteTransaction = self::getBdd()->prepare('
                SELECT v.compte_id, v.montant_total, v.date_vente, v.statut
                FROM vente v 
                WHERE v.id = ?
            ');
            $requeteTransaction->execute([$transaction_id]);
            $transaction = $requeteTransaction->fetch(PDO::FETCH_ASSOC);

            if (!$transaction) {
                self::getBdd()->rollBack();
                error_log("Transaction non trouvée: " . $transaction_id);
                return false;
            }

            if (isset($transaction['statut']) && $transaction['statut'] === 'annulee') {
                self::getBdd()->rollBack();
                error_log("Transaction déjà annulée: " . $transaction_id);
                return false;
            }

            $dateTransaction = date('Y-m-d', strtotime($transaction['date_vente']));
            $dateAujourdhui = date("Y-m-d");

            error_log("DEBUG Annulation - Date transaction: " . $dateTransaction . " | Date aujourd'hui: " . $dateAujourdhui);

            if ($dateTransaction !== $dateAujourdhui) {
                self::getBdd()->rollBack();
                error_log("Transaction trop ancienne pour être annulée: " . $transaction_id);
                return false;
            }

            $requeteProduits = self::getBdd()->prepare('
                SELECT produit_id, quantite 
                FROM contient 
                WHERE vente_id = ?
            ');
            $requeteProduits->execute([$transaction_id]);
            $produits = $requeteProduits->fetchAll(PDO::FETCH_ASSOC);

            $requeteRemboursement = self::getBdd()->prepare('
                UPDATE compte 
                SET solde = solde + ? 
                WHERE id = ?
            ');
            $requeteRemboursement->execute([$transaction['montant_total'], $transaction['compte_id']]);

            $requeteStock = self::getBdd()->prepare('
                UPDATE produit 
                SET quantiteActuelle = quantiteActuelle + ? 
                WHERE id = ?
            ');

            foreach ($produits as $produit) {
                $requeteStock->execute([$produit['quantite'], $produit['produit_id']]);
            }

            $requeteUpdate = self::getBdd()->prepare('
                UPDATE vente 
                SET statut = "annulee" 
                WHERE id = ?
            ');


            $resultat = $requeteUpdate->execute([$transaction_id]);

            self::getBdd()->commit();

            error_log("Transaction annulée avec succès: " . $transaction_id);
            return true;

        } catch (PDOException $e) {
            self::getBdd()->rollBack();
            error_log("Erreur lors de l'annulation de la transaction: " . $e->getMessage());
            return false;
        }
    }

    private function mettreAJourStocks($produits) {
        try {
            $requete = self::getBdd()->prepare('
                UPDATE produit 
                SET quantiteActuelle = quantiteActuelle - ? 
                WHERE id = ?
            ');

            foreach ($produits as $produit) {
                $requete->execute([$produit['quantite'], $produit['id']]);
            }
        } catch (PDOException $e) {
            error_log("Erreur mise à jour stock: " . $e->getMessage());
            throw $e;
        }
    }
}