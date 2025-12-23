<?php
    class Achat {
        private $id;
        private $date_achat;
        private $prix_total;
        private $fournisseur_id;

        public function __construct($id, $date_achat, $prix_total, $fournisseur_id) {
            $this->id = $id;
            $this->date_achat = $date_achat;
            $this->prix_total = $prix_total;
            $this->fournisseur_id = $fournisseur_id;
        }

        public function getId() {
            return $this->id;
        }

        public function getDateAchat() {
            return $this->date_achat;
        }

        public function getPrixTotal() {
            return $this->prix_total;
        }

        public function getFournisseur() {
            return $this->fournisseur_id;
        }

        public function setPrixTotal($prix_total) {
            $this->prix_total = $prix_total;
        }
    }
