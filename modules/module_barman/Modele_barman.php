<?php
include_once 'connexion/Connexion.php';
include_once 'modules/module_staff/modele_staff.php';

class ModeleBarman extends ModeleStaff {

    public function listerProduits() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT id, nom, prix, quantiteActuelle as disponibilite, 
                   COALESCE(type, "autre") as type
            FROM produit 
            ORDER BY type, nom
        ');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur liste produits: " . $e->getMessage());
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
            // CORRECTION : On remplace 'contient' par 'ligne_vente'
            // et on adapte les alias (c.quantite devient l.quantite)
            $requete = self::getBdd()->prepare('
            SELECT p.nom, l.quantite, l.prix_unitaire 
            FROM ligne_vente l 
            JOIN produit p ON l.produit_id = p.id 
            WHERE l.vente_id = ?
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
    public function listerCommandesEnCours() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT 
                v.id as commande_id, 
                c.prenom, 
                c.nom, 
                v.date_vente, 
                v.montant_total,
                CASE 
                    WHEN SUM(l.statut = "en_attente") > 0 THEN "en_attente"
                    WHEN SUM(l.statut = "validee") > 0 THEN "validee"
                    WHEN SUM(l.statut = "en_preparation") > 0 THEN "en_preparation"
                    WHEN SUM(l.statut = "prete") > 0 THEN "prete"
                    ELSE "livree"
                END AS statut
            FROM vente v 
            JOIN compte c ON v.compte_id = c.id 
            JOIN ligne_vente l ON l.vente_id = v.id
            WHERE DATE(v.date_vente) = CURDATE()
            AND l.statut != "livree"
            GROUP BY v.id, c.prenom, c.nom, v.date_vente, v.montant_total
            ORDER BY v.id DESC
        ');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur listerCommandesEnCours: " . $e->getMessage());
            return [];
        }
    }
    // Dans Modele_barman.php

    public function getHistoriqueCommandes() {
        // CORRECTION : On enlève la jointure avec ligne_vente pour éviter les doublons
        // On récupère directement le statut de la table VENTE
        $sql = "SELECT 
            v.id AS commande_id, 
            v.date_vente AS date_heure_affichage, 
            v.montant_total, 
            v.statut, -- On prend le statut de la vente, pas de la ligne
            c.nom,
            c.prenom
        FROM vente v 
        JOIN compte c ON v.compte_id = c.id
        ORDER BY v.date_vente DESC";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerTransaction($produits, $compte_id, $montant_total) {
        try {
            self::getBdd()->beginTransaction();

            // Récupérer l'association du barman
            $associationId = $this->getAssociationIdParBarman($_SESSION['id']);

            // Vérifier le solde
            $soldeClient = $this->getSoldeClient($compte_id);
            if ($soldeClient === false || $soldeClient < $montant_total) {
                self::getBdd()->rollBack();
                error_log("Solde insuffisant ou client introuvable");
                return false;
            }

            // 1. Créer la vente
            $requete = self::getBdd()->prepare("
            INSERT INTO vente (date_vente, montant_total, compte_id, association_id) 
            VALUES(NOW(), :montant, :compte_id, :asso_id)
        ");
            $requete->execute([
                ':montant' => $montant_total,
                ':compte_id' => $compte_id,
                ':asso_id' => $associationId
            ]);
            $vente_id = self::getBdd()->lastInsertId();

            // 2. Insérer les lignes de vente (avec statut 'en_attente')
            $this->insererLignesVente($vente_id, $produits);

            // 3. Mettre à jour les stocks
            $this->mettreAJourStocks($produits);

            // 4. Débiter le compte client
            if (!$this->debiterCompte($compte_id, $montant_total)) {
                self::getBdd()->rollBack();
                error_log("Échec du débit du compte");
                return false;
            }

            self::getBdd()->commit();
            error_log("Transaction créée avec succès. ID: $vente_id");
            return $vente_id;

        } catch (Exception $e) {
            self::getBdd()->rollBack();
            error_log("Erreur creerTransaction: " . $e->getMessage());
            return false;
        }
    }

    public function getDerniereTransaction() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT 
                v.id as transaction_id, 
                v.date_vente, 
                v.montant_total, 
                v.statut, 
                c.id as client_id,
                c.nom, 
                c.prenom
            FROM vente v
            JOIN compte c ON v.compte_id = c.id
            ORDER BY v.id DESC
            LIMIT 1
        ');
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur derniereTransaction : " . $e->getMessage());
            return null;
        }
    }
    /**
     * Change le statut d'une commande
     * en_attente → validee → en_preparation → prete → livree
     */
    public function changerStatutCommande($venteId, $nouveauStatut)
    {
        // Statuts que le barman peut envoyer
        $statutsAutorises = ['en_attente', 'validee', 'en_preparation', 'prete', 'livree'];

        if (!in_array($nouveauStatut, $statutsAutorises, true)) {
            error_log("Statut non autorisé: " . $nouveauStatut);
            return false;
        }

        // Traduction métier barman → client
        $statutVente = ($nouveauStatut === 'livree') ? 'payee' : $nouveauStatut;

        $bdd = self::getBdd();
        $bdd->beginTransaction();

        try {
            // 1️⃣ Statut des lignes (cuisine / bar)
            $stmt1 = $bdd->prepare(
                "UPDATE ligne_vente 
             SET statut = :statut 
             WHERE vente_id = :vente_id"
            );
            $stmt1->execute([
                ':statut' => $nouveauStatut,
                ':vente_id' => $venteId
            ]);

            // 2️⃣ Statut de la commande (client)
            $stmt2 = $bdd->prepare(
                "UPDATE vente 
             SET statut = :statut 
             WHERE id = :vente_id"
            );
            $stmt2->execute([
                ':statut' => $statutVente,
                ':vente_id' => $venteId
            ]);

            $bdd->commit();
            return true;

        } catch (PDOException $e) {
            $bdd->rollBack();
            error_log("Erreur changement statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le statut actuel d'une commande
     */
    public function getStatutCommande($venteId)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT 
                CASE 
                    WHEN SUM(statut = 'en attente') > 0 THEN 'en attente'
                    WHEN SUM(statut = 'validee') > 0 THEN 'validee'
                    WHEN SUM(statut = 'en_preparation') > 0 THEN 'en_preparation'
                    WHEN SUM(statut = 'prete') > 0 THEN 'prete'
                    ELSE 'livree'
                END AS statut
             FROM ligne_vente 
             WHERE vente_id = :vente_id"
            );

            $requete->execute([':vente_id' => $venteId]);
            $result = $requete->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['statut'] : null;
        } catch (PDOException $e) {
            error_log("Erreur get statut: " . $e->getMessage());
            return null;
        }
    }
    public function getTypesProduits() {
        try {
            $requete = self::getBdd()->prepare('
            SELECT DISTINCT COALESCE(type, "autre") as type
            FROM produit 
            WHERE type IS NOT NULL AND type != ""
            ORDER BY type
        ');
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erreur get types produits: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Recherche produits avec filtres
     */
    public function rechercherProduits($search = null, $type = null) {
        try {
            $sql = 'SELECT id, nom, prix, quantiteActuelle as disponibilite, type 
                FROM produit 
                WHERE 1=1';
            $params = [];

            // Filtre par recherche
            if ($search) {
                $sql .= ' AND nom LIKE :search';
                $params[':search'] = "%$search%";
            }

            // Filtre par type
            if ($type && $type !== 'tous') {
                $sql .= ' AND type = :type';
                $params[':type'] = $type;
            }

            $sql .= ' ORDER BY type, nom';

            $requete = self::getBdd()->prepare($sql);
            $requete->execute($params);
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur recherche produits: " . $e->getMessage());
            return [];
        }
    }
    private function insererLignesVente($vente_id, $produits) {
        try {
            $requete = self::getBdd()->prepare("
            INSERT INTO ligne_vente (produit_id, vente_id, quantite, prix_unitaire, statut) 
            VALUES (:produit_id, :vente_id, :quantite, :prix, 'en attente')
        ");

            foreach ($produits as $produit) {
                $requete->execute([
                    ':produit_id' => $produit['id'],
                    ':vente_id' => $vente_id,
                    ':quantite' => $produit['quantite'],
                    ':prix' => $produit['prix']
                ]);
            }
        } catch (PDOException $e) {
            error_log("Erreur insertion lignes vente: " . $e->getMessage());
            throw $e;
        }
    }
    private function debiterCompte($compte_id, $montant) {
        try {
            // Vérifier le solde
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

            // Débiter
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
    /**
     * Recherche les clients de la même association que le barman
     */
    public function rechercherClientsDeMonAssociation($search, $barmanId) {
        try {
            $requete = self::getBdd()->prepare("
            SELECT DISTINCT c.id, c.nom, c.prenom, c.email, c.solde
            FROM compte c
            JOIN appartient ap_client ON c.id = ap_client.compte_id
            WHERE ap_client.association_id = (
                SELECT association_id 
                FROM appartient 
                WHERE compte_id = :barman_id
                LIMIT 1
            )
            AND (
                c.nom LIKE :search 
                OR c.prenom LIKE :search 
                OR c.email LIKE :search
            )
            ORDER BY c.nom, c.prenom
            LIMIT 50
        ");

            $searchTerm = "%$search%";
            $requete->execute([
                ':barman_id' => $barmanId,
                ':search' => $searchTerm
            ]);

            return $requete->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur rechercherClientsDeMonAssociation: " . $e->getMessage());
            return [];
        }
    }
    public function getStatsAccueil() {
        // CA du jour
        $reqCA = self::getBdd()->prepare('
        SELECT SUM(montant_total) as ca 
        FROM vente 
        WHERE date_vente >= CURDATE() AND statut != "annulee"
    ');
        $reqCA->execute();
        $ca = $reqCA->fetch(PDO::FETCH_ASSOC)['ca'] ?? 0;

        // Nombre de commandes actives (non livrées)
        $reqCmd = self::getBdd()->prepare('
        SELECT COUNT(DISTINCT v.id) as nb 
        FROM vente v 
        JOIN ligne_vente l ON v.id = l.vente_id 
        WHERE l.statut != "livree" AND v.date_vente >= CURDATE()
    ');
        $reqCmd->execute();
        $nbCmd = $reqCmd->fetch(PDO::FETCH_ASSOC)['nb'] ?? 0;

        // Nombre d'articles en rupture ou faible stock (< 5)
        $reqStock = self::getBdd()->prepare('SELECT COUNT(*) as nb FROM produit WHERE quantiteActuelle < 5');
        $reqStock->execute();
        $nbStock = $reqStock->fetch(PDO::FETCH_ASSOC)['nb'] ?? 0;

        return ['ca' => $ca, 'nb_commandes' => $nbCmd, 'stock_critique' => $nbStock];
    }

// 2. Récupère le nom du dernier client ayant commandé
    public function getDernierClientActif() {
        $req = self::getBdd()->prepare('
        SELECT c.nom, c.prenom 
        FROM vente v 
        JOIN compte c ON v.compte_id = c.id 
        ORDER BY v.id DESC LIMIT 1
    ');
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

// 3. Récupère les 5 dernières ventes pour le tableau
    public function getVentesRecentesTableau() {
        $req = self::getBdd()->prepare('
        SELECT v.id, v.montant_total, v.date_vente, c.nom, c.prenom, 
               (SELECT SUM(quantite) FROM ligne_vente WHERE vente_id = v.id) as nb_articles
        FROM vente v 
        JOIN compte c ON v.compte_id = c.id
        ORDER BY v.date_vente DESC 
        LIMIT 5
    ');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

// 4. Liste précise des produits en rupture pour la liste rouge
    public function getListeStockCritique() {
        $req = self::getBdd()->prepare('
        SELECT nom, quantiteActuelle 
        FROM produit 
        WHERE quantiteActuelle < 5 
        ORDER BY quantiteActuelle ASC 
        LIMIT 5
    ');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}