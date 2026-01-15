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
            $role = "Client";

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
                $sql = "SELECT id, prenom, email, mdp, role FROM compte WHERE email = :login";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([':login' => $_POST['emailUtilisateurConnexion']]);

                $user = $stmt->fetch();

                if ($user && password_verify($_POST['mdpUtilisateurConnexion'], $user['mdp'])) {

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['login'] = $user["email"];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: index.php?module=client&action=form_connexionReussie_utilisateur");
                    exit();
                }
                else {
                    header("Location: index.php?module=client&action=erreur&erreur=connexionPasBon_utilisateur");
                    exit();
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

                $sql = "UPDATE compte SET email = :nvEmail, mdp = :nvMdp WHERE id = :id";

                $stmt = $this->bdd->prepare($sql);

                $stmt->execute([
                    ":nvEmail" => $nvEmail,
                    ":nvMdp" => $mdpHasher,
                    ":id" => $id
                ]);

                header("Location: index.php?module=client&action=form_modificationReussie_utilisateur");
                exit();
            }
        }

        public function rechargement() {
            if (!isset($_SESSION['id'])) {
                header("Location: index.php?module=client&action=erreur&erreur=personneEstConnectee_utilisateur");
                exit;
            }

            $id = $_SESSION['id'];
            $montant = (int) $_POST['montant'];

            $stmt = $this->bdd->prepare(
                "SELECT solde FROM compte WHERE id = :id"
            );

            $stmt->execute([':id' => $id]);

            $soldeActuel = $stmt->fetchColumn();

            $nvSolde = $soldeActuel + $montant;

            $this->miseAJourDuSolde($id, $nvSolde);

            $this->ajouterRechargement($id, $montant);

            header("Location: index.php?module=client&action=rechargementReussi_utilisateur");
            exit();
        }

        public function deconnexion() {
            session_unset();

            session_destroy();

            header("Location: index.php?module=client&action=form_deconnexionReussie_utilisateur");
            exit();
        }

        public function getSolde() {
            $stmt = $this->bdd->prepare(
                "SELECT solde FROM compte WHERE id = :id"
            );

            $stmt->execute([':id' => $_SESSION['id']]);

            return $stmt->fetchColumn();
        }

        public function miseAJourDuSolde($idCompte, $nouveauSolde) {
            $stmt = $this->bdd->prepare(
                "UPDATE compte SET solde = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauSolde, $idCompte]);
        }

        public function ajouterRechargement($idCompte, $montant) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO rechargement (valeur, date_rechargement, compte_id) VALUES (:montant, NOW(), :idCompte)"
            );
            $stmt->execute([
                ':montant' => $montant,
                ':idCompte' => $idCompte
            ]);
        }

        public function getHistoriqueRechargements($idCompte) {
            $stmt = $this->bdd->prepare(
                "SELECT valeur, date_rechargement
                 FROM rechargement
                 WHERE compte_id = :id
                 ORDER BY date_rechargement DESC"
            );
            $stmt->execute([':id' => $idCompte]);

            return $stmt->fetchAll();
        }

    }