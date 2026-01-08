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
                    ":role"   => $role
                ]);

                header("Location: index.php?module=client&action=form_compteBonLogin_utilisateur");
            }
        }

        public function connexion() {
            if (!isset($_POST['emailUtilisateurConnexion'], $_POST['mdpUtilisateurConnexion'])) {
                header("Location: index.php?module=client&action=erreur&erreur=personneEstConnectee_utilisateur");
                exit;
            }
            else {
                $sql = "SELECT id, prenom, mdp FROM compte WHERE email = :login";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([':login' => $_POST['emailUtilisateurConnexion']]);

                $user = $stmt->fetch();

                if ($user && password_verify($_POST['mdpUtilisateurConnexion'], $user['mdp'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['login'] = $_POST["emailUtilisateurConnexion"];
                    $_SESSION['id'] = $user['id'];

                    header("Location: index.php?module=client&action=form_connexionReussie_utilisateur");
                }
                else {
                    header("Location: index.php?module=client&action=erreur&erreur=connexionPasBon_utilisateur");
                }
            }
        }

        public function modification() {
            if (!isset($_SESSION['id'])) {
                header("Location: index.php?module=client&action=erreur&erreur=personneEstConnectee_utilisateur");
                exit;
            }
            else {

                $id = $_SESSION['id'];
                $nvEmail = $_POST["nvEmailUtilisateur"];
                $nvMdp = $_POST["nvMdpUtilisateur"];
                $nvRole = $_POST["nvRoleUtilisateur"];

                $sql = "SELECT COUNT(*) FROM compte WHERE email = :nvEmail AND id != :id";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([
                    ':nvEmail' => $nvEmail,
                    ":id" => $id
                ]);

                if ($stmt->fetchColumn() > 0) {
                    header("Location: index.php?module=client&action=erreur&erreur=emailDejaUtilise_utilisateur");
                    exit;
                }

                if ($nvMdp !== $_POST["nvMdpUtilisateurConfirmation"]) {
                    header("Location: index.php?module=client&action=erreur&erreur=mdpPasBon_utilisateur");
                    exit;
                }

                $mdpHasher = password_hash($nvMdp, PASSWORD_DEFAULT);

                $sql = "UPDATE compte SET email = :nvEmail, mdp = :nvMdp, role = :nvRole WHERE id = :id";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([
                    ":nvEmail" => $nvEmail,
                    ":nvMdp" => $mdpHasher,
                    ":nvRole" => $nvRole,
                    ":id" => $id
                ]);

                header("Location: index.php?module=client&action=form_modificationReussie_utilisateur");
            }
        }

        public function deconnexion() {
            session_unset();

            session_destroy();

            header("Location : index.php?module=client&action=form_deconnexionReussie_utilisateur");
        }

        public function miseAJourDuSolde($idCompte, $nouveauSolde) {
            $stmt = $this->bdd->prepare(
                "UPDATE compte SET solde = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauSolde, $idCompte]);
        }
    }
