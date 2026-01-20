<?php
    class VenteAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrerProduit() {
            $date = $_POST['dateVente'];
            $montant = $_POST['montantTotal'];
            $compteId = $_POST['compte_id'];

            $stmt = $this->bdd->prepare(
                "INSERT INTO vente (date_vente, montant_total, compte_id)
             VALUES (:date, :montant, :compteId)"
            );

            $stmt->execute([
                ":date"   => $date,
                ":montant" => $montant,
                ":compteId"  => $compteId
            ]);
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