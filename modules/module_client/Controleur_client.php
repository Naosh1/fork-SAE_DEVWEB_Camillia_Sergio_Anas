<?php

    include_once "Vue_client.php";
    include_once "../commun/accés/CompteAcces.php";

    class Controleur_client {
        private $vue;
        private $modele;

        public function __construct() {
            $this->vue = new Vue_client();
            $this->modele = new CompteAcces();
        }

        public function getVue() {
            return $this->vue;
        }

        public function ajout() {
            $this->modele->enregistrerCompte();
        }

        public function connexion() {
            $this->modele->connexion();
        }

        public function modification() {
            $this->modele->modification();
        }

        public function deconnexion() {
            $this->modele->deconnexion();
        }

        public function rechargement() {
            $this->modele->rechargement();
        }

        public function soldeEspace() {
            return $this->modele->getSolde();
        }

        public function historiqueRechargements() {
            return $this->modele->getHistoriqueRechargements($_SESSION['id']);
        }

    }