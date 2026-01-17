<?php

    class ProduitVenduAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrer(Ligne_Vente $ligneVente) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO ligne_vente (produit_id, vente_id, quantite, prix_unitaire)
             VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $ligneVente->getProduitId(),
                $ligneVente->getVenteId(),
                $ligneVente->getQuantite(),
                $ligneVente->getPrixUnitaire()
            ]);
        }

        public function rechercheParVente($idVente) {
            $stmt = $this->bdd->prepare(
                "SELECT * FROM ligne_vente WHERE vente_id = ?"
            );

            $stmt->execute([$idVente]);

            $lignes = [];

            foreach ($stmt->fetchAll() as $row) {
                $lignes[] = new Ligne_Vente(
                    $row["produit_id"],
                    $row["vente_id"],
                    $row["quantite"],
                    $row["prix_unitaire"]
                );
            }

            return $lignes;
        }

        public function supprimerParVente($idVente) {
            $stmt = $this->bdd->prepare(
                "DELETE FROM ligne_vente WHERE vente_id = ?"
            );
            $stmt->execute([$idVente]);
        }
    }