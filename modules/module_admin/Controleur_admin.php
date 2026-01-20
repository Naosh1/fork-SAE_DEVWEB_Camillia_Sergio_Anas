<?php

    include_once "Vue_admin.php.php";
    include_once "commun/accés/ProduitVenduAcces.php";

    class Controleur_admin {
        private $vue;
        private $modeleProduitVenduAcces;

        public function __construct()
        {
            $this->vue = new Vue_admin();
            $this->modeleProduitVenduAcces = new ProduitVenduAcces();
        }

        public function gererAction($action) {
            switch ($action) {
                case "accueil":
                    $contenu = $this->vue->afficherNav();
                    VueGenerique::setAffichage($contenu);
                    break;
            }
        }



    }