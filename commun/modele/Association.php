<?php
    class Association {
        private $id;
        private $nom;
        private $adresse;
        private $email;
        private $telephone;
        private $solde;

        public function __construct($id, $nom, $adresse, $email, $telephone, $solde) {
            $this->id = $id;
            $this->nom = $nom;
            $this->adresse = $adresse;
            $this->email = $email;
            $this->telephone = $telephone;
            $this->solde = $solde;
        }

        public function getId() {
            return $this->id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getAdresse() {
            return $this->adresse;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getTelephone() {
            return $this->telephone;
        }

        public function getSolde() {
            return $this->solde;
        }

        public function setSolde($solde) {
            $this->solde = $solde;
        }

    }
