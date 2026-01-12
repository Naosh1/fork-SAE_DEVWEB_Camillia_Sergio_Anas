<?php
include_once "Controleur_client.php";
include_once __DIR__ . '/../../vue_generique.php';

class Mod_client {

    private $controleur;
    private $action;
    private $erreur;

    public function __construct() {
        $this->controleur = new Controleur_client();
        $this->action = $_GET["action"] ?? "menu";
        $this->erreur = $_GET["erreur"] ?? null;

        switch ($this->action) {

            case "menu":
                VueGenerique::setAffichage("<h1>Bienvenue</h1>");
                break;

            case "form_inscription_utilisateur":
                $contenu = $this->controleur->getVue()->form_inscription();
                VueGenerique::setAffichage($contenu);
                break;

            case "form_connexion_utilisateur":
                $contenu = $this->controleur->getVue()->form_connexion();
                VueGenerique::setAffichage($contenu);
                break;

            case "form_compteBonLogin_utilisateur":
                $contenu = $this->controleur->getVue()->form_compteBon();
                VueGenerique::setAffichage($contenu);
                break;

            case "form_connexionReussie_utilisateur":
                $contenu = $this->controleur->getVue()->form_connexionReussie();
                VueGenerique::setAffichage($contenu);
                break;

            case "form_modification_utilisateur":
                $contenu = $this->controleur->getVue()->form_modification();
                VueGenerique::setAffichage($contenu);
                break;

            case "form_modificationReussie_utilisateur":
                $contenu = $this->controleur->getVue()->form_modificationReussie();
                VueGenerique::setAffichage($contenu);
                break;

            case "ajout_utilisateur":
                $this->controleur->ajout();
                break;

            case "verif_connexion":
                $this->controleur->connexion();
                break;

            case "verif_modification":
                $this->controleur->modification();
                break;

            case "erreur":
                switch ($this->erreur) {
                    case "loginPasBon_utilisateur":
                        VueGenerique::setAffichage(
                            $this->controleur->getVue()->form_comptePasBonLogin()
                        );
                        break;
                }
                break;
        }
    }
}
