<?php
    class Vue_Menu {
        public $affichage = '';

        public function __construct() {

        }

        public function gen_menu() {
                ob_start();
                ?>

                <nav>
                    <?php if (isset($_SESSION['id']) && $_SESSION['role'] === 'SuperAdmin'): ?>
                        <a href='index.php?module=client&action=gestion'> Gestion </a>
                        <a href='index.php?module=client&action=espace'> Espace Personnel </a>
                        <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                        <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
                    <?php elseif (isset($_SESSION['id'])): ?>
                        <a href='index.php?module=client&action=espace'> Espace Personnel </a>
                        <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                        <a href='index.php?module=client&action=form_produits_utilisateur'> Produits </a>
                        <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
                        <a class="panier" href='index.php?module=client&action=form_panier_utilisateur'> Panier </a>
                    <?php else: ?>
                        <a href='index.php?module=client&action=accueil'> Accueil </a>
                        <a href='index.php?module=client&action=form_inscription_utilisateur'> S'inscrire </a>
                        <a href='index.php?module=client&action=form_connexion_utilisateur'> Connexion </a>
                    <?php endif; ?>
                </nav>

                <?php
                $this->affichage = ob_get_clean();
            }
    }



