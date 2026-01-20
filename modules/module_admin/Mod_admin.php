<?php

include_once "Controleur_admin.php";
    class Mod_admin {

        private $controleur;

        public function __construct() {
            $this->controleur = new Controleur_admin();
            $action = $_GET['action'] ?? 'accueil';
            $this->controleur->gererAction($action);
        }

    }