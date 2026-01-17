<?php

    class ProduitAcheteAcces
    {
        private $bdd;

        public function __construct()
        {
            $this->bdd = Connexion::getBdd();
        }

    }