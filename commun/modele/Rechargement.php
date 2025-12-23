<?php
    class Rechargement {
        private $id;
        private $valeur;
        private $date_rechargement;
        private $compte_id;

        public function __construct($id, $valeur, $date_rechargement, $compte_id) {
            $this->id = $id;
            $this->valeur = $valeur;
            $this->date_rechargement = $date_rechargement;
            $this->compte_id = $compte_id;
        }

        public function getId() {
            return $this->id;
        }

        public function getValeur() {
            return $this->valeur;
        }

        public function getDateRechargement() {
            return $this->date_rechargement;
        }

        public function getCompte() {
            return $this->compte_id;
        }

    }
