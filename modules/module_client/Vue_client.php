<?php

    class Vue_client {

        public function __construct() {

        }

        public function form_inscription() {
            ob_start();

            echo '<form method="post" action="../../index.php?module=client&action=ajout_utilisateur"> <br>';
            echo    'Nom : ' . '<input type="text" name="nomUtilisateur" required> <br>';
            echo    'Prenom : ' . '<input type="text" name="prenomUtilisateur" required> <br>';
            echo    'Email : ' . '<input type="text" name="emailUtilisateur" required> <br>';
            echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateur" required> <br>';
            echo    'Solde : ' . '<input type="number" value="0" name="soldeUtilisateur" required> <br>';
            echo    'Role : ' . '<input type="text" name="roleUtilisateur" required> <br><br>';
            echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_connexion() {
            ob_start();

            echo '<form method="post" action="../../index.php?module=client&action=verif_connexion">';
            echo    'Email : ' . '<input type="text" name="emailUtilisateur" required> <br>';
            echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateur" required> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_paiement() {

        }

    }