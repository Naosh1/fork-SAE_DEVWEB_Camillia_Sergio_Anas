<?php
    class Vente {
        private $id;
        private $date_vente;
        private $montant_total;
        private $compte_id;

        public function __construct($id, $date_vente, $montant_total, $compte_id) {
            $this->id = $id;
            $this->date_vente = $date_vente;
            $this->montant_total = $montant_total;
            $this->compte_id = $compte_id;
        }

        public function getId() {
            return $this->id;
        }

        public function getDateVente() {
            return $this->date_vente;
        }

        public function getMontantTotal() {
            return $this->montant_total;
        }

        public function getCompte() {
            return $this->compte_id;
        }

        public function setMontantTotal($montant_total) {
            $this->montant_total = $montant_total;
        }

    }
