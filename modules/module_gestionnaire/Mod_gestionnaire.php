<?php
include_once 'Controleur_gestionnaire.php';

class Mod_gestionnaire
{
    private $controleur;


    public function __construct()
    {
        $this->controleur = new ControleurGestionnaire();
        $this->controleur->gererAction($_GET['action']);
    }


}