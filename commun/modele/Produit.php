<?php
    class Produit {
        private $id;
        private $nom;
        private $type;
        private $prix;
        private $quantiteActuelle;

        public function __construct($id, $nom, $type, $prix, $quantiteActuelle) {
            $this->id = $id;
            $this->nom = $nom;
            $this->type = $type;
            $this->prix = $prix;
            $this->quantiteActuelle = $quantiteActuelle;
        }

        public function getId() {
            return $this->id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getType() {
            return $this->type;
        }

        public function getPrix() {
            return $this->prix;
        }

        public function getQuantiteActuelle() {
            return $this->quantiteActuelle;
        }

        public function setQuantiteActuelle($quantiteActuelle) {
            $this->quantiteActuelle = $quantiteActuelle;
        }

        public function setPrix($prix) {
            $this->prix = $prix;
        }

    }
