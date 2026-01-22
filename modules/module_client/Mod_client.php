<?php

    include_once "Controleur_client.php";

class Mod_client {
    private $controleur;

    public function __construct() {
        $this->controleur = new Controleur_client();
        $action = $_GET['action'] ?? 'espace';
        $this->controleur->gererAction($action);
    }


}