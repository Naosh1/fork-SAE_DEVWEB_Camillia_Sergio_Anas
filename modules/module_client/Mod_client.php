<?php

    include_once "Controleur_client.php";

    class Mod_client {
        private $controleur;
        private $action;

        public function __construct() {
            $this->controleur = new Controleur_client();
            $this->action = isset($_GET["action"]) ? $_GET["action"] : "pas d'action";

            switch($this->action) {
                case "menu":
                    $contenu = "Bienvenue";
                    VueGenerique::setAffichage($contenu);
                    break;
                case "inscription_utilisateur":
                    $contenu = $this->controleur->getVue()->form_inscription();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "ajout_utilisateur":
                    $this->controleur->ajout();
                    break;
            }
        }


    }