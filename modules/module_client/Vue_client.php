<?php

    class Vue_client {

        public function __construct() {

        }

        public function form_inscription() {
            ob_start();

            echo '<form method="post" action="/SaeWeb/index.php?module=client&action=ajout_utilisateur"> <br>';
            echo    'Nom : ' . '<input type="text" name="nomUtilisateur" required> <br>';
            echo    'Prenom : ' . '<input type="text" name="prenomUtilisateur" required> <br>';
            echo    'Email : ' . '<input type="email" name="emailUtilisateur" pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Entrez une adresse email valide (ex : nom@gmail.com)" required> <br>';
            echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateur" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}" title="Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre" required> <br>';
            echo    'Confirmez le MDP : ' . '<input type="password" name="mdpUtilisateurConfirmation" required> <br>';
            echo    'Solde : ' . '<input type="number" value="0" name="soldeUtilisateur" required> <br>';
            echo    'Role : ' . '<input type="text" name="roleUtilisateur" required> <br><br>';
            echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_connexion() {
            ob_start();

            echo '<form method="post" action="/SaeWeb/index.php?module=client&action=verif_connexion">';
            echo    'Email : ' . '<input type="email"  name="emailUtilisateur" placeholder="votreEmail@gmail.com" required> <br>';
            echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateur" required> <br>';
            echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_comptePasBonLogin() {
            ob_start();

            echo "Le login est deja utilisé !!!";

            return ob_get_clean();
        }

        public function form_compteBon() {
            ob_start();

            echo "Utilisateur entré avec succés !!!";

            return ob_get_clean();
        }

        public function form_mdpPasBon() {
            ob_start();

            echo "Le mdp n'est pas le meme !!! Recommencer";

            return ob_get_clean();
        }

        public function form_connexionReussie() {
            ob_start();

            echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . "<br>";

            return ob_get_clean();
        }

        public function connexionPasBon() {
            ob_start();

            echo "Login ou mot de passe incorrect" . "<br>";

            return ob_get_clean();
        }

        public function personneEstConnectee() {
            ob_start();

            echo "Personne est connectée !" . "<br>";

            return ob_get_clean();
        }

    }