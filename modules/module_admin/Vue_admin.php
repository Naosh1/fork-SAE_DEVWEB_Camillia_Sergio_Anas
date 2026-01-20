<?php
    class Vue_admin {

        public function __construct(){

        }

        public function afficherNav() {
            ob_start();
            ?>
            <nav>
                <?php if (isset($_SESSION['id'])): ?>
                    <a href='index.php?module=admin&action=espace'> Espace Personnel </a>
                    <a href='index.php?module=admin&action=form_rechargement_utilisateur'> Rechargement </a>
                    <a href='index.php?module=admin&action=form_produits_utilisateur'> Produits </a>
                    <a href='index.php?module=admin&action=form_plus_utilisateur'> Plus </a>
                    <a href='index.php?module=admin&action=form_historique_utilisateur'> Historique </a>
                    <a href='index.php?module=admin&action=form_gestion_utilisateur'> Gestion </a>
                    <a class="panier" href='index.php?module=admin&action=form_panier_utilisateur'> Panier </a>
                    <a class="panier" href='index.php?module=admin&action=form_commande_statut_panier_utilisateur'> Suivi Commandes </a>
                <?php endif; ?>
            </nav>
            <?php
            return ob_get_clean();
        }
    }