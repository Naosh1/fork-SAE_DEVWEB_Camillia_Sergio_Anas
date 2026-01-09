<?php

require_once '../models/ModeleBarman.php';
require_once '../views/Vue_barman.php';

class Controleur_barman{
    private $modele;
    private $vue;

    public function __construct(){
        $this->modele = new ModeleBarman();
        $this->vue = new Vue_barman();
    }
    public function afficherProduits(){
        $produits = $this->modele->listerProduits();
        $this->vue->afficherProduits($produits);
    }

    public function rechercherClient(){
        $clients = [];
        if (isset($_GET['search'])) {
            $clients = $this->modele->rechercherClient($_GET['search']);
        }
        $this->vue->afficherClients($clients);
    }

    public function commandesEnCours(){
        $commandes = $this->modele->listerCommandesEnCours();
        $this->vue->afficherCommandes($commandes);
    }

    public function detailCommande(){
        if (!isset($_GET['id'])) {
            return;
        }

        $commande = $this->modele->getCommande($_GET['id']);
        $produits = $this->modele->getProduitsCommande($_GET['id']);

        $this->vue->afficherDetailCommande($commande, $produits);
    }
}
