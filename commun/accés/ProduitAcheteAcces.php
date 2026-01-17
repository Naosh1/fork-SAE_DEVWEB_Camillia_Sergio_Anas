<?php

    class ProduitAcheteAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrer(Ligne_Achat $ligneAchat) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO ligne_achat (produit_id, achat_id, quantite, prix_achat_unitaire)
             VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $ligneAchat->getProduitId(),
                $ligneAchat->getAchatId(),
                $ligneAchat->getQuantite(),
                $ligneAchat->getPrixAchatUnitaire()
            ]);
        }

        public function rechercherParAchat($idAchat) {
            $stmt = $this->bdd->prepare(
                "SELECT * FROM ligne_achat WHERE achat_id = ?"
            );

            $stmt->execute([$idAchat]);
            $rows = $stmt->fetchAll();

            $result = [];

            foreach ($rows as $row) {
                $result[] = new Ligne_Achat(
                    $row["produit_id"],
                    $row["achat_id"],
                    $row["quantite"],
                    $row["prix_achat_unitaire"]
                );
            }

            return $result;
        }

        public function supprimerParAchat($idAchat) {
            $stmt = $this->bdd->prepare(
                "DELETE FROM ligne_achat WHERE achat_id = ?"
            );
            $stmt->execute([$idAchat]);
        }




    }