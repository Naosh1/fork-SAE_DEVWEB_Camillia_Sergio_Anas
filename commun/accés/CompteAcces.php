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

            $sql = "SELECT COUNT(*) FROM compte WHERE email = :login";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([':login' => $_POST['emailUtilisateur']]);
            $dejaExistant = $stmt->fetchColumn();

            if ($dejaExistant > 0) {
                header("Location: index.php?module=client&action=erreur&erreur=loginPasBon_utilisateur");
                exit;
            }

            if ($mdp !== $_POST["mdpUtilisateurConfirmation"]) {
                header("Location: index.php?module=client&action=erreur&erreur=mdpPasBon_utilisateur");
                exit;
            }

            else {
                $sql = "INSERT INTO compte (nom, prenom, email, mdp, solde, role) VALUES (:nom, :prenom, :email, :mdp, :solde, :role)";

                $mdpHasher = password_hash($mdp, PASSWORD_DEFAULT);

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([
                    ":nom"   => $nom,
                    ":prenom" => $prenom,
                    ":email"  => $email,
                    ":mdp"    => $mdpHasher,
                    ":solde"  => $solde,
                    ":role"   => $role]);

                header("Location: index.php?module=client&action=form_compteBonLogin_utilisateur");
            }
        }

        public function connexion() {
            if (!isset($_POST['emailUtilisateur'], $_POST['mdpUtilisateur'])) {
                echo "Personne est connectée ! <br>";
                header("Location: index.php?module=client&action=erreur&erreur=personneEstConnectee_utilisateur");
                exit;

            }
            else {
                $sql = "SELECT email, mdp FROM compte WHERE email = :login";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([':login' => $_POST['emailUtilisateur']]);

                $user = $stmt->fetch();

                if ($user && password_verify($_POST['mdpUtilisateur'], $user['mdp'])) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $_POST['emailUtilisateur'];

                    header("Location: index.php?module=client&action=form_connexionReussie_utilisateur");
                }
                else {
                    header("Location: index.php?module=client&action=erreur&erreur=connexionPasBon_utilisateur");
                }
            }
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
