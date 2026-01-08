<?php

    include_once "Controleur_client.php";

    class Mod_client {
        private $controleur;
        private $action;
        private $erreur;

        public function __construct() {
            $this->controleur = new Controleur_client();
            $this->action = isset($_GET["action"]) ? $_GET["action"] : "menu";
            $this->erreur = isset($_GET["erreur"]) ? $_GET["erreur"] : " ";

            switch($this->action) {
                case "menu" :
                    $contenu = "Bienvenue sur le site de la buvette";
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_inscription_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->controleur->getVue()->form_inscription();
                        VueGenerique::setAffichage($contenu);
                    }
                    else {
                        $contenu = $this->controleur->getVue()->form_dejaConnecte();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_connexion_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->controleur->getVue()->form_connexion();
                        VueGenerique::setAffichage($contenu);
                    }
                    else {
                        $contenu = $this->controleur->getVue()->form_dejaConnecte();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_compteBonLogin_utilisateur" :
                    $contenu = $this->controleur->getVue()->form_compteBon();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_connexionReussie_utilisateur" :
                    $contenu = $this->controleur->getVue()->form_connexionReussie();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_modification_utilisateur" :
                    $contenu = $this->controleur->getVue()->form_modification();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_modificationReussie_utilisateur" :
                    $contenu = $this->controleur->getVue()->form_modificationReussie();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_deconnexionReussie_utilisateur" :
                    $contenu = $this->controleur->getVue()->form_deconnexionReussie_utilisateur();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "ajout_utilisateur" :
                    $this->controleur->ajout();
                    break;
                case "verif_connexion" :
                    $this->controleur->connexion();
                    break;
                case "verif_modification" :
                    $this->controleur->modification();
                    break;
                case "deconnexion" :
                    $this->controleur->deconnexion();
                    break;
                case "erreur" :
                     switch ($this->erreur) {
                         case "loginPasBon_utilisateur" :
                             $contenu = $this->controleur->getVue()->form_comptePasBonLogin();
                             VueGenerique::setAffichage($contenu);
                             break;
                         case "mdpPasBon_utilisateur" :
                             $contenu = $this->controleur->getVue()->form_mdpPasBon();
                             VueGenerique::setAffichage($contenu);
                             break;
                         case "connexionPasBon_utilisateur" :
                             $contenu = $this->controleur->getVue()->form_connexionPasBon();
                             VueGenerique::setAffichage($contenu);
                             break;
                         case "personneEstConnectee_utilisateur" :
                             $contenu = $this->controleur->getVue()->form_personneEstConnectee();
                             VueGenerique::setAffichage($contenu);
                             break;
                         case "emailDejaUtilise_utilisateur" :
                             $contenu = $this->controleur->getVue()->form_emailDejaUtilise();
                             VueGenerique::setAffichage($contenu);
                             break;
                     }
                     break;


            }
        }


    }