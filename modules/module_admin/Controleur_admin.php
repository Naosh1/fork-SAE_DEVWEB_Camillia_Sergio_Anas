<?php

    include_once "Vue_admin.php";
    include_once "commun/accés/ProduitVenduAcces.php";
    include_once "commun/accés/CompteAcces.php";

    class Controleur_admin {
        private $vue;
        private $modeleProduitVenduAcces;

        private $modeleCompte;

        public function __construct()
        {
            $this->vue = new Vue_admin();
            $this->modeleCompte = new CompteAcces();
            $this->modeleProduitVenduAcces = new ProduitVenduAcces();
        }

        public function gererAction($action) {
            switch ($action) {
                case "accueil":
                    $contenu = $this->vue->afficherNav();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_plus_utilisateur" :
                    $contenu = $this->getVue()->form_plus();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "deconnexion" :
                    $this->deconnexion();
                    break;
            }
        }

        public function getVue()
        {
            return $this->vue;
        }

        public function deconnexion()
        {
            $this->modeleCompte->deconnexion();
        }


    }