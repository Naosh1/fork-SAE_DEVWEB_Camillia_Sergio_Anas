<?php
class Controleur_barman {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleBarman();
        $this->vue = new Vue_barman();
    }

    public function gererAction($action) {
        switch ($action) {
            case 'accueil':
                $this->vue->afficherAccueil();
                break;
            case 'afficherProduits':
                $produits = $this->modele->listerProduits();
                $this->vue->afficherProduits($produits);
                break;
            case 'rechercherClient':
                $clients = [];
                if (isset($_GET['search'])) {
                    $clients = $this->modele->rechercherClient($_GET['search']);
                }
                $this->vue->afficherClients($clients);
                break;
            case 'commandesEnCours':
                $commandes = $this->modele->listerCommandesEnCours();
                $this->vue->afficherCommandes($commandes);
                break;
            case 'detailCommande':
                if (!isset($_GET['id'])) {
                    $this->vue->afficherErreur("Aucun ID de commande spécifié");
                    break;
                }
                $commande = $this->modele->getCommande($_GET['id']);
                $produits = $this->modele->getProduitsCommande($_GET['id']);
                $this->vue->afficherDetailCommande($commande, $produits);
                break;
            default:
                $this->vue->afficherAccueil();
                break;
        }
    }
}