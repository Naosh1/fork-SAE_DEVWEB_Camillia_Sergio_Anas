<?php
include_once 'connexion/Connexion.php';
class ModeleBarman extends Connexion {
    public function listerProduits() {
        try {
            $requete = self::getBdd()->prepare('SELECT id, nom, prix, quantiteActuelle as disponibilite FROM produit ORDER BY nom');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur liste produits: " . $e->getMessage());
            return [];
        }
    }

    public function rechercherClient($search) {
        try {
            $requete = self::getBdd()->prepare('
                SELECT id, nom, prenom, solde 
                FROM compte 
                WHERE nom LIKE ? OR prenom LIKE ? OR id = ?
            ');
            $searchTerm = "%$search%";
            $requete->execute([$searchTerm, $searchTerm, $search]);
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur recherche client: " . $e->getMessage());
            return [];
        }
    }

    public function listerCommandesEnCours() {
        try {
            $requete = self::getBdd()->prepare('
                SELECT v.id as commande_id, c.prenom, c.nom, v.date_vente, v.montant_total 
                FROM vente v 
                JOIN compte c ON v.compte_id = c.id 
                ORDER BY v.date_vente DESC
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

    public function creerTransaction($produits, $compte) {
        try {
            $date = date("Y-m-d");
            $montant = 0;

            foreach ($produits as $produit) {
                $montant += $produit['quantite'] * $produit['prix'];
            }

            $requeteCheckCompte = self::getBdd()->prepare('SELECT id FROM compte WHERE id = ?');
            $requeteCheckCompte->execute([$compte]);
            if (!$requeteCheckCompte->fetch()) {
                error_log("Compte non trouvé lors de la transaction: " . $compte);
                return false;
            }

            if (!$this->debitTransaction($montant, $compte)) {
                error_log("Échec du débit pour le compte: " . $compte . ", montant: " . $montant);
                return false;
            }

            $requete = self::getBdd()->prepare("INSERT INTO vente (date_vente, montant_total, compte_id) VALUES(?, ?, ?)");
            $requete->execute([$date, $montant, $compte]);
            $vente_id = self::getBdd()->lastInsertId();

            $requeteContient = self::getBdd()->prepare(
                "INSERT INTO contient (produit_id, vente_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)"
            );

            foreach ($produits as $p) {
                $requeteContient->execute([
                    $p['id'],
                    $vente_id,
                    $p['quantite'],
                    $p['prix']
                ]);
            }

            return $vente_id;

        } catch (PDOException $e) {
            error_log("Erreur dans la création d'une Transaction " . $e->getMessage());
            return false;
        }
    }

    public function debitTransaction($montant, $compte) {
        try {
            $requeteCheck = self::getBdd()->prepare('SELECT solde FROM compte WHERE id = ?');
            $requeteCheck->execute([$compte]);
            $result = $requeteCheck->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                error_log("DEBUG: Compte non trouvé: " . $compte);
                return false;
            }

            $soldeActuel = (float)$result['solde'];

            if ($soldeActuel < $montant) {
                error_log("DEBUG: Solde insuffisant. Compte: " . $compte . ", Solde: " . $soldeActuel . ", Montant: " . $montant);
                return false;
            }

            // Débiter le compte
            $requete = self::getBdd()->prepare('UPDATE compte SET solde = solde - ? WHERE id = ?');
            $success = $requete->execute([$montant, $compte]);

            if (!$success) {
                error_log("DEBUG: Échec de l'UPDATE sur le compte " . $compte);
                return false;
            }

            $requeteCheck2 = self::getBdd()->prepare('SELECT solde FROM compte WHERE id = ?');
            $requeteCheck2->execute([$compte]);
            $result2 = $requeteCheck2->fetch(PDO::FETCH_ASSOC);

            $nouveauSolde = (float)$result2['solde'];
            error_log("DEBUG: Débit effectué. Ancien solde: " . $soldeActuel . ", Nouveau solde: " . $nouveauSolde);

            return true;

        } catch (PDOException $e) {
            error_log("DEBUG: Erreur PDO lors du débit: " . $e->getMessage());
            return false;
        }
    }

    public function getInfoProduit($produit_id) {
        try {
            $requete = self::getBdd()->prepare('SELECT id, nom, prix, quantiteActuelle as disponibilite FROM produit WHERE id = ?');
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

    public function updateStock($produit_id, $quantite_vendue) {
        try {
            $requete = self::getBdd()->prepare('UPDATE produit SET quantiteActuelle = quantiteActuelle - ? WHERE id = ?');
            return $requete->execute([$quantite_vendue, $produit_id]);
        } catch (PDOException $e) {
            error_log("Erreur mise à jour stock: " . $e->getMessage());
            return false;
        }
    }
}