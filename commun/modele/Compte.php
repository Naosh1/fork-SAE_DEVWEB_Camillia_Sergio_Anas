<?php
    class Compte {
        private $id;
        private $nom;
        private $prenom;
        private $email;
        private $mdp;
        private $solde;
        private $role;

        public function __construct($id, $nom, $prenom, $email, $mdp, $solde, $role)  {
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mdp = $mdp;
            $this->solde = $solde;
            $this->role = $role;
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

        public function getRole() {
            return $this->role;
        }

        public function setSolde($solde) {
            $this->solde = $solde;
        }

        public function setRole($role) {
            $this->role = $role;
        }
    }
