<?php
include_once 'Modele_gestionnaire.php';
include_once 'Vue_gestionnaire.php';
include_once 'Controleur_gestionnaire.php';
include_once 'connexion/Connexion.php';


class Mod_gestionnaire
{
    private $controleur;


    public function __construct()
    {
        $this->controleur = new ControleurGestionnaire();
        $action = $_GET['action'] ?? 'accueil';
        $this->controleur->gererAction($action);
    }


}