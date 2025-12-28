<?php
    class CompteAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrerCompte() {
            $nom = $_POST["nomUtilisateur"];
            $prenom = $_POST["prenomUtilisateur"];
            $email = $_POST["emailUtilisateur"];
            $mdp = $_POST["mdpUtilisateur"];
            $solde = $_POST["soldeUtilisateur"];
            $role = $_POST["roleUtilisateur"];

            $stmt = $this->bdd->prepare(
                "INSERT INTO compte (nom, prenom, email, mdp, solde, role)
             VALUES (:nom, :prenom, :email, :mdp, :solde, :role)"
            );

            $stmt->execute([
                ":nom"   => $nom,
                ":prenom" => $prenom,
                ":email"  => $email,
                ":mdp"    => $mdp,
                ":solde"  => $solde,
                ":role"   => $role
            ]);

            echo "Utilisateur ajouté";
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

        public function miseAJourDuSolde($idCompte, $nouveauSolde) {
            $stmt = $this->bdd->prepare(
                "UPDATE compte SET solde = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauSolde, $idCompte]);
        }
    }
