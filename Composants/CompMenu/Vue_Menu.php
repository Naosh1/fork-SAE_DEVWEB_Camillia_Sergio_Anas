<?php
    class Vue_Menu {
        public $affichage = '';

        public function __construct() {

        }

        public function gen_menu() {
            ob_start();
            ?>

            <nav>
               <a href='index.php?module=client&action=accueil'> Accueil </a>
               <?php if (isset($_SESSION['id'])): ?>
                  <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                  <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
               <?php else: ?>
                  <a href='index.php?module=client&action=form_inscription_utilisateur'> S'inscrire </a>
                  <a href='index.php?module=client&action=form_connexion_utilisateur'> Connexion </a>
               <?php endif; ?>
            </nav>

            <?php
            $this->affichage = ob_get_clean();
        }
    }


