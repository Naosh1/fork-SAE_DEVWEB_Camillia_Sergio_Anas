<?php

class ModeleGestionnaire extends Connexion
{


    public function getProduits()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom, type, prix, quantiteActuelle FROM produit ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getProduits: " . $e->getMessage());
            return [];
        }
    }

    public function getAssociations()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom, adresse, email, telephone, solde FROM association ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAssociations: " . $e->getMessage());
            return [];
        }
    }

    public function ajouterProduit($nom, $type, $prix, $stock)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO produit (nom, type, prix, quantiteActuelle)
                 VALUES (?, ?, ?, ?)"
            );
            $requete->execute([$nom, $type, $prix, $stock]);
            return self::getBdd()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur ajouterProduit: " . $e->getMessage());
            return false;
        }
    }

    public function lierProduitAssociation($idProduit, $idAssociation)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO gere (association_id, produit_id)
                 VALUES (?, ?)"
            );
            return $requete->execute([$idAssociation, $idProduit]);
        } catch (PDOException $e) {
            error_log("Erreur lierProduitAssociation: " . $e->getMessage());
            return false;
        }
    }

    public function getStock()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom as produit, quantiteActuelle as quantite 
                 FROM produit 
                 WHERE quantiteActuelle > 0 
                 ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getStock: " . $e->getMessage());
            return [];
        }
    }

    public function getVentes()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT v.id, v.date_vente, v.montant_total, v.compte_id,
                        c.nom as client_nom, c.prenom as client_prenom
                 FROM vente v
                 LEFT JOIN compte c ON v.compte_id = c.id
                 ORDER BY v.date_vente DESC, v.id DESC"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getVentes: " . $e->getMessage());
            return [];
        }
    }

    public function getUtilisateurs()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom, prenom, email, solde, role 
                 FROM compte 
                 ORDER BY nom, prenom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getUtilisateurs: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalVentes()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT COALESCE(SUM(montant_total), 0) as total 
                 FROM vente"
            );
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Erreur getTotalVentes: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbProduits()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM produit");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbProduits: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbUtilisateurs()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM compte");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbUtilisateurs: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbAssociations()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM association");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbAssociations: " . $e->getMessage());
            return 0;
        }
    }

    public function ajouterAssociation($nom, $adresse, $email, $telephone, $solde = 0)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO association (nom, adresse, email, telephone, solde)
             VALUES (?, ?, ?, ?, ?)"
            );
            $requete->execute([$nom, $adresse, $email, $telephone, $solde]);
            return self::getBdd()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur ajouterAssociation: " . $e->getMessage());
            return false;
        }
    }

    public function supprimerAssociation($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "DELETE FROM association WHERE id = ?"
            );
            return $requete->execute([$id]);
        } catch (PDOException $e) {
            error_log("L'association n'a pas pu être supprimé : " . $e->getMessage());
            return false;
        }
    }

    public function getAssociationParId($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT * FROM association WHERE id = ?"
            );
            $requete->execute([$id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAssociationParId: " . $e->getMessage());
            return false;
        }
    }

    public function modifierAssociation($id, $nom, $adresse, $email, $telephone, $solde)
    {
        try {
            $requete = self::getBdd()->prepare(
                "UPDATE association SET 
                nom = ?,
                adresse = ?,
                email = ?,
                telephone = ?,
                solde = ?
             WHERE id = ?"
            );
            return $requete->execute([$nom, $adresse, $email, $telephone, $solde, $id]);
        } catch (PDOException $e) {
            error_log("Erreur modifierAssociation: " . $e->getMessage());
            return false;
        }
    }
}