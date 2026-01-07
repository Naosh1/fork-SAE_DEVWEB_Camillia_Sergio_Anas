<?php

    class Modele_barman {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function supprimerCompteParID($id) {
            $stmt = $this->bdd->prepare(
                "DELETE FROM compte WHERE id = ?"
            );
            $stmt->execute([$id]);
        }

        public function rechercheCompteParID($id) {
            $stmt = $this->bdd->prepare("SELECT * FROM compte WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            return new Compte(
                $row["id"],
                $row["nom"],
                $row["prenom"],
                $row["email"],
                $row["mdp"],
                $row["solde"],
                $row["role"]
            );
        }
    }
