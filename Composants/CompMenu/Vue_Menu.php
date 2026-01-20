<?php
class Vue_Menu {
    public $affichage = '';

    public function __construct() {

    }

    public function gen_menu() {
        ob_start();
        ?>

        <nav>
            <?php if (isset($_SESSION['id'])): ?>
                <a href='index.php?module=client&action=espace'> Espace Personnel </a>
                <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                <a href='index.php?module=client&action=form_produits_utilisateur'> Produits </a>
                <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
                <a href='index.php?module=client&action=form_panier_utilisateur' class="panier"> Panier </a>
                <a href='index.php?module=client&action=form_commande_statut_panier_utilisateur' class="panier"> Suivi Commandes </a>
            <?php endif; ?>
        </nav>

        <?php
        $this->affichage = ob_get_clean();
    }
}