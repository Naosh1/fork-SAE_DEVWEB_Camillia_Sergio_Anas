<?php

include_once "modele_connexion.php";
include_once "vue.connexion.php";

class ControllerConnexion
{

    private $modele;
    private $vue;
    private $action;

    function __construct()
    {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : 'connexion';

        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
    }

    public function executerAction()
    {
        switch ($this->action) {
            case 'connexion':
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $this->modele->connexion();
                    $this->vue->afficher_liste_nav();
                } else {
                    $this->vue->afficherConnexionFormulaire();
                }
                break;
            case 'inscription':
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $this->modele->inscription();
                    $this->vue->afficher_liste_nav();
                } else {
                    $this->vue->afficherInscriptionFormulaire();
                }
                break;
                case 'deconnexion':
                    $this->modele->deconnexion();
        }

        $this->vue->afficherVue();
    }
}