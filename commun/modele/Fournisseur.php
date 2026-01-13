<?php
    class Fournisseur {
        private $id;
        private $nom;
        private $telephone;
        private $email;

        public function __construct($id, $nom, $telephone, $email) {
            $this->id = $id;
            $this->nom = $nom;
            $this->telephone = $telephone;
            $this->email = $email;
        }

        public function getId() {
            return $this->id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getTelephone() {
            return $this->telephone;
        }

        public function getEmail() {
            return $this->email;
        }


    }