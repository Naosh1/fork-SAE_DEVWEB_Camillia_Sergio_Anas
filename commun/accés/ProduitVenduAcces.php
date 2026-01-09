<?php

    class ProduitVenduAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrer(Contient $contient) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO contient (produit_id, vente_id, quantite, prix_unitaire)
             VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $contient->getProduitId(),
                $contient->getVenteId(),
                $contient->getQuantite(),
                $contient->getPrixUnitaire()
            ]);
        }

        public function rechercheParVente($idVente) {
            $stmt = $this->bdd->prepare(
                "SELECT * FROM contient WHERE vente_id = ?"
            );

            $stmt->execute([$idVente]);

            $lignes = [];

            foreach ($stmt->fetchAll() as $row) {
                $lignes[] = new Contient(
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
                "DELETE FROM contient WHERE vente_id = ?"
            );
            $stmt->execute([$idVente]);
        }
    }