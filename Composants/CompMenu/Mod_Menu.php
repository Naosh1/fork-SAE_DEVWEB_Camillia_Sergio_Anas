<?php

    include_once 'Composants/CompMenu/Cont_Menu.php';

    class Mod_Menu {
        private $controleur;
        private $affichage;

        public function __construct() {
            $this->controleur = new Cont_Menu();
            $this->controleur->exec();
            $this->affichage = $this->controleur->getAffichage();
        }

        public function affiche() {
            return $this->affichage;
        }
    }