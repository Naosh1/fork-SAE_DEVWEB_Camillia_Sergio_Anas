<?php

    include_once 'Composants/CompMenu/Vue_Menu.php';

    class Cont_Menu {
        private $vue;

        public function __construct() {
            $this->vue = new Vue_Menu();
        }

        public function exec() {
            $this->vue->gen_menu();
        }

        public function getAffichage() {
            return $this->vue->affichage;
        }
    }