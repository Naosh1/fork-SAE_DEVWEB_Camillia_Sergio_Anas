<?php
    class Inventaire {
        private $id;
        private $date_inventaire;
        private $association_id;

        public function __construct($id, $date_inventaire, $association_id) {
            $this->id = $id;
            $this->date_inventaire = $date_inventaire;
            $this->association_id = $association_id;
        }

        public function getId() {
            return $this->id;
        }

        public function getDateInventaire() {
            return $this->date_inventaire;
        }

        public function getAssociation() {
            return $this->association_id;
        }

    }
