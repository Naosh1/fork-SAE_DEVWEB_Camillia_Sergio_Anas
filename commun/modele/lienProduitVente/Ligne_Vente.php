<?php
    class Ligne_Vente {
        private $produit_id;
        private $vente_id;
        private $quantite;
        private $prix_unitaire;

        public function __construct($produit_id, $vente_id, $quantite, $prix_unitaire) {
            $this->produit_id = $produit_id;
            $this->vente_id = $vente_id;
            $this->quantite = $quantite;
            $this->prix_unitaire = $prix_unitaire;
        }

        public function getProduitId() {
            return $this->produit_id;
        }

        public function getVenteId() {
            return $this->vente_id;
        }

        public function getQuantite() {
            return $this->quantite;
        }

        public function getPrixUnitaire() {
            return $this->prix_unitaire;
        }

        public function setQuantite($quantite) {
            $this->quantite = $quantite;
        }

        public function setPrixUnitaire($prix_unitaire) {
            $this->prix_unitaire = $prix_unitaire;
        }


    }
