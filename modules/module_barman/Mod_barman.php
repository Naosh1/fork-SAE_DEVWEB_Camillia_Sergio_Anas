<?php
    include_once 'Modele_barman.php';
    include_once 'Vue_barman.php';
    include_once 'Controleur_barman.php';
    include_once 'connexion/Connexion.php';

class Mod_barman {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new Controleur_barman();
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "accueil";
        $this->erreur = isset($_GET["erreur"]) ? $_GET["erreur"] : " ";

        switch ($this->action) {
            case 'accueil':
                $contenu = $this->controleur->getVue()->afficherAccueil();
                VueGenerique::setAffichage($contenu);
                break;
            case 'afficherProduits':
                $contenu = $this->controleur->getVue()->afficherProduits($this->controleur->listeProduits());
                VueGenerique::setAffichage($contenu);
                break;
            case 'rechercherClient':
                $contenu = $this->controleur->rechercherClient();
                VueGenerique::setAffichage($contenu);
                break;
            case 'commandesEnCours':
                $contenu = $this->controleur->afficherCommandes();
                VueGenerique::setAffichage($contenu);
                break;
            case 'detailCommande':
                $contenu = $this->controleur->afficherDetailCommande();
                VueGenerique::setAffichage($contenu);
                break;
            case 'creerTransaction':
                $contenu = $this->controleur->afficherFormTransaction();
                VueGenerique::setAffichage($contenu);
                break;
            case 'traiterTransaction':
                $contenu = $this->controleur->traiterTransaction();
                VueGenerique::setAffichage($contenu);
                break;
            case 'historiqueCommandes':
                $contenu = $this->controleur->getVue()->afficherHistoriqueCommandes($this->controleur->historiqueDeCommandes());
                VueGenerique::setAffichage($contenu);
                break;
            case 'derniereTransaction':
                $contenu = $this->controleur->getVue()->afficherDerniereTransaction($this->controleur->derniereTransaction());
                VueGenerique::setAffichage($contenu);
                break;
            case 'annulerTransaction':
                $contenu = $this->controleur->annulerTransaction();
                VueGenerique::setAffichage($contenu);
                break;
        }

    }
}