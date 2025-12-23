<?php
    class Concerne {
        private $produit_id;
        private $inventaire_id;
        private $stock_theorique;
        private $stock_reel;
        private $perte;

        public function __construct($produit_id, $inventaire_id, $stock_theorique, $stock_reel, $perte) {
            $this->produit_id = $produit_id;
            $this->inventaire_id = $inventaire_id;
            $this->stock_theorique = $stock_theorique;
            $this->stock_reel = $stock_reel;
            $this->perte = $perte;
        }

        public function getProduitId() {
            return $this->produit_id;
        }

        public function getInventaireId() {
            return $this->inventaire_id;
        }

        public function getStockTheorique() {
            return $this->stock_theorique;
        }

        public function getStockReel() {
            return $this->stock_reel;
        }

        public function getPerte() {
            return $this->perte;
        }

        public function setStockTheorique($stock_theorique) {
            $this->stock_theorique = $stock_theorique;
        }

        public function setStockReel($stock_reel) {
            $this->stock_reel = $stock_reel;
        }

        public function setPerte($perte) {
            $this->perte = $perte;
        }

    }
