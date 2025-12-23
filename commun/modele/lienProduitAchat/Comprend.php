<?php
    class Comprend {
        private $produit_id;
        private $achat_id;
        private $quantite;
        private $prix_achat_unitaire;

        public function __construct($produit_id, $achat_id, $quantite, $prix_achat_unitaire) {
            $this->produit_id = $produit_id;
            $this->achat_id = $achat_id;
            $this->quantite = $quantite;
            $this->prix_achat_unitaire = $prix_achat_unitaire;
        }

        public function getProduitId() {
            return $this->produit_id;
        }

        public function getAchatId() {
            return $this->achat_id;
        }

        public function getQuantite() {
            return $this->quantite;
        }

        public function getPrixAchatUnitaire() {
            return $this->prix_achat_unitaire;
        }

        public function setQuantite($quantite) {
            $this->quantite = $quantite;
        }

        public function setPrixAchatUnitaire($prix_achat_unitaire) {
            $this->prix_achat_unitaire = $prix_achat_unitaire;
        }



    }
