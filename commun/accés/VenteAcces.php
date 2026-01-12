<?php
    class VenteAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrerVente(Vente $vente) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO vente (date_vente, montant_total, compte_id)
             VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $vente->getDateVente(),
                $vente->getMontantTotal(),
                $vente->getCompte()
            ]);

            return (int)$this->bdd->lastInsertId();
        }

        public function rechercheVenteParCompte($idCompte) {
            $stmt = $this->bdd->prepare(
                "SELECT * FROM vente WHERE compte_id = ?"
            );
            $stmt->execute([$idCompte]);

            $ventes = [];

            foreach ($stmt->fetchAll() as $row) {
                $ventes[] = new Vente(
                    $row["id"],
                    $row["date_vente"],
                    $row["montant_total"],
                    $row["compte_id"]
                );
            }

            return $ventes;
        }
    }