<?php
    class Compte {
        private $id;
        private $nom;
        private $prenom;
        private $email;
        private $mdp;
        private $solde;

        public function __construct($id, $nom, $prenom, $email, $mdp, $solde)  {
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mdp = $mdp;
            $this->solde = $solde;
        }

        public function getId() {
            return $this->id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getPrenom() {
            return $this->prenom;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getMdp() {
            return $this->mdp;
        }

        public function getSolde() {
            return $this->solde;
        }

        public function setSolde($solde) {
            $this->solde = $solde;
        }
    }
