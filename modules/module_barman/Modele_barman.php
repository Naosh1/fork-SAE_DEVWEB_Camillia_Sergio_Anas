<?php

class ModeleBarman extends Connexion
{
    public function creerTransaction($produits, $compte)
    {
        try {
            $date = date("Y-m-d");
            $montant = 0;

            foreach ($produits as $produit) {
                $montant += $produit['quantite']*$produit['prix'];
            }
            if(!$this->debitTransaction($compte,$montant)) {
                return false;
            }
            $requete = self::getBdd()->prepare("INSERT INTO vente (date_vente , montant_total , compte_id) VALUES(? , ? , ?)");
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

        }
        catch (PDOException $e) {
            error_log("Erreur dans la création d'une Transaction " . $e->getMessage());
            return false;
        }
    }

    public function debitTransaction($montant, $compte)
    {
        try {
            $requete = self::getBdd()->prepare('UPDATE compte SET solde = solde - ? WHERE id_compte = ?');
            return $requete->execute([$montant, $compte]);
        }
        catch (PDOException $e) {
            error_log("Erreur lors du débit" . $e->getMessage());
            return false;
        }
    }
    public function updateStock($produit){

    }

    public function rechercherClient($identification)
    {
        try {
            if (ctype_digit($identification)) {
                $requete = self::getBdd()->prepare(
                    "SELECT id, prenom, solde
                 FROM compte
                 WHERE id = ?"
                );
                $requete->execute([$identification]);
            }
            else {
                $requete = self::getBdd()->prepare(
                    "SELECT id, prenom, solde
                 FROM compte
                 WHERE nom LIKE ?"
                );
                $requete->execute(['%' . $identification . '%']);
            }

            return $requete->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur rechercherClient: " . $e->getMessage());
            return false;
        }
    }
    public function listerProduits()
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT id,nom,prix,quantiteActuelle,
                CASE 
                    WHEN quantiteActuelle > 0 THEN 'Disponible'
                    ELSE 'Indisponible'
                END AS disponibilite
                FROM produit
                ORDER BY nom"
            );

            $requete->execute();

            return $requete->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur listerProduits: " . $e->getMessage());
            return false;
        }
    }
    public function listerCommandesEnCours()
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT 
                v.id AS commande_id,
                v.date_vente,
                v.montant_total,
                c.prenom
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             WHERE v.date_vente = CURDATE()
             ORDER BY v.id DESC"
            );

            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur listerCommandesEnCours: " . $e->getMessage());
            return false;
        }
    }
    public function getCommande($commande_id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT 
                v.id AS commande_id,
                v.date_vente,
                v.montant_total,
                c.id AS client_id,
                c.prenom
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             WHERE v.id = ?"
            );

            $requete->execute([$commande_id]);
            return $requete->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur getCommande: " . $e->getMessage());
            return false;
        }
    }
    public function getProduitsCommande($commande_id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT 
                p.nom,
                ct.quantite,
                ct.prix_unitaire
             FROM contient ct
             JOIN produit p ON ct.produit_id = p.id
             WHERE ct.vente_id = ?"
            );

            $requete->execute([$commande_id]);
            return $requete->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur getProduitsCommande: " . $e->getMessage());
            return false;
        }
    }
}