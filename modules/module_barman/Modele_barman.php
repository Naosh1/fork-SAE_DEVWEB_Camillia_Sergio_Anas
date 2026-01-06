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

}