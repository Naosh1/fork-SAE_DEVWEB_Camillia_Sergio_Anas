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

        if (isset($_POST['action'])) {
            $action = $_POST['action'];
        } elseif (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'accueil';
        }

        error_log("Mod_barman: action = '$action'");

        $this->controleur->gererAction($action);
    }
}