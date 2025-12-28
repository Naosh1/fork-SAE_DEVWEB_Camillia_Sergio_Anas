<?php

    class ProduitAcheteAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrer(Comprend $comprend) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO comprend (produit_id, achat_id, quantite, prix_achat_unitaire)
             VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $comprend->getProduitId(),
                $comprend->getAchatId(),
                $comprend->getQuantite(),
                $comprend->getPrixAchatUnitaire()
            ]);
        }

        public function rechercherParAchat($idAchat) {
            $stmt = $this->bdd->prepare(
                "SELECT * FROM comprend WHERE achat_id = ?"
            );

            $stmt->execute([$idAchat]);
            $rows = $stmt->fetchAll();

            $result = [];

            foreach ($rows as $row) {
                $result[] = new Comprend(
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
                "DELETE FROM comprend WHERE acaht_id = ?"
            );
            $stmt->execute([$idAchat]);
        }




    }