<?php
include_once 'Modele_barman.php';
include_once 'Vue_barman.php';
include_once 'Controleur_barman.php';
include_once 'connexion/Connexion.php';


class Mod_barman
{
    private $controleur;


    public function __construct()
    {
        $this->controleur = new Controleur_Barman();
        $action = isset($_GET['action']) ? $_GET['action'] : 'accueil';
        $this->controleur->gererAction($action);
    }


}