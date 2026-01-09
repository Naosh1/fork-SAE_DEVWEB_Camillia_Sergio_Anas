<?php

    class Vue_client {

        public function __construct() {

        }

        public function form_inscription() {
            ob_start();

            echo '<form method="post" action="/~asemghouni/SAE_DEVWEB_Camillia_Sergio_Anas/index.php?module=client&action=ajout_utilisateur"> <br>';
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

            echo '<form method="post" action="/~asemghouni/SAE_DEVWEB_Camillia_Sergio_Anas/index.php?module=client&action=verif_connexion">';
            echo    'Email : ' . '<input type="email"  name="emailUtilisateurConnexion" placeholder="votreEmail@gmail.com" required> <br>';
            echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateurConnexion" required> <br>';
            echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_modification() {
            ob_start();

            echo '<form method="post" action="/~asemghouni/SAE_DEVWEB_Camillia_Sergio_Anas/index.php?module=client&action=verif_modification"> <br>';
            echo    'Nouvelle Email : ' . '<input type="email" name="nvEmailUtilisateur" pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Entrez une adresse email valide (ex : nom@gmail.com)" required> <br>';
            echo    'Nouveau Mot de passe : ' . '<input type="password" name="nvMdpUtilisateur" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}" title="Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre" required> <br>';
            echo    'Confirmez le MDP : ' . '<input type="password" name="nvMdpUtilisateurConfirmation" required> <br>';
            echo    'Nouveau Role : ' . '<input type="text" name="nvRoleUtilisateur" required> <br><br>';
            echo    '<input type="submit" name="bouton" value="Modifier"> <br>';
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

            echo "Bienvenue " . htmlspecialchars($_SESSION['prenom']) . "<br>";

            return ob_get_clean();
        }

        public function form_connexionPasBon() {
            ob_start();

            echo "Login ou mot de passe incorrect" . "<br>";

            return ob_get_clean();
        }

        public function form_personneEstConnectee() {
            ob_start();

            echo "Personne est connectée !" . "<br>";

            return ob_get_clean();
        }

        public function form_emailDejaUtilise() {
            ob_start();

            echo "Le mail est déja utilisée" . "<br>";

            return ob_get_clean();
        }

        public function form_modificationReussie() {
            ob_start();

            echo "Modification des infos réussie avec succés !" . "<br>";

            return ob_get_clean();
        }

        public function form_dejaConnecte() {
            ob_start();

            echo "Vous êtes déjà connecté(e)" . "<br>";

            return ob_get_clean();
        }

        public function form_deconnexion() {
            ob_start();

            echo '<a href="index.php?module=client&action=deconnexion">Se déconnecter</a>';

            return ob_get_clean();
        }

        public function form_deconnexionReussie() {
           ob_start();

           echo 'Vous êtes déconnecter !';

           return ob_get_clean();
        }

        public function form_rechargement() {
            ob_start();

            echo '<form method="post" action="/~asemghouni/SAE_DEVWEB_Camillia_Sergio_Anas/index.php?module=client&action=verif_rechargement"> <br>';
            echo    'Montant à recharger : ' . '<input type="number" name="montant" min="1" max="40" step="1" required> <br>';
            echo    'Nom du titulaire : ' . '<input type="text" inputmode="text" name="nomTitulaire" pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s-]+" title="Uniquement des lettres" required><br>';
            echo    'Numero de carte : ' . '<input type="text" inputmode="numeric" name="codeCarteUtilisateur" pattern="[0-9]{16}" maxlength="16" placeholder="XXXX-XXXX-XXXX-XXXX" title="Coordonnées numerique sans espace" required> <br>';
            echo    'CVV : ' . '<input type="text" inputmode="numeric" name="cvvUtilisateur" pattern="[0-9]{3}" maxlength="3" placeholder="123" title="3 chiffres" required> <br>';
            echo    'Date d\'expiration : ' . '<input type="text" inputmode="numeric" name="dateExpirationUtilisateur" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" placeholder="MM/AA" title="Entrez une date correcte" required> <br>';
            echo    '<input type="submit" name="bouton" value="Payer"> <br>';
            echo '</form>';

            return ob_get_clean();
        }

        public function form_plus() {
            ob_start();
        ?>
                <nav class="nav-user">
                    <ul>
                        <li> <a href='index.php?module=client&action=form_modification_utilisateur'> Modifier mes infos </a> </li>
                        <li> <a href='index.php?module=client&action=deconnexion'> Déconnexion </a> </li>
                    </ul>
                </nav>
        <?php
            return ob_get_clean();
        }

        public function form_rechargementReussi() {
            ob_start();

            echo "Rechargement réussie !";

            return ob_get_clean();
        }

        public function form_demandeConnexion() {
            ob_start();

            echo "Veuillez vous connecter !";

            return ob_get_clean();
        }
    }