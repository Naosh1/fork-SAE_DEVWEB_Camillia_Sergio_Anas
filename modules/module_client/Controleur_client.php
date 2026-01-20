<?php

    include_once "Vue_client.php";
    include_once "commun/accés/CompteAcces.php";
    include_once "commun/accés/ProduitAcces.php";
    include_once "commun/accés/ProduitVenduAcces.php";

    class Controleur_client
    {
        private $vue;
        private $modeleCompte;
        private $modeleProduit;
        private $modeleProduitVenduAcces;

        public function __construct()
        {
            $this->vue = new Vue_client();
            $this->modeleCompte = new CompteAcces();
            $this->modeleProduit = new ProduitAcces();
            $this->modeleProduitVenduAcces = new ProduitVenduAcces();
        }

        public function gererAction($action)
        {
            switch ($action) {
                case "accueil" :
                    $contenu = $this->vue->afficherNav();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "espace" :
                    $contenu = $this->getVue()->form_espace($this->soldeEspace(), $this->historiqueRechargements());
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_inscription_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->getVue()->form_inscription();
                        VueGenerique::setAffichage($contenu);
                    } else {
                        $contenu = $this->getVue()->form_dejaConnecte();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_connexion_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->getVue()->form_connexion();
                        VueGenerique::setAffichage($contenu);
                    } else {
                        $contenu = $this->getVue()->form_dejaConnecte();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_compteBonLogin_utilisateur" :
                    $contenu = $this->getVue()->form_compteBon();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_connexionReussie_utilisateur" :
                    $contenu = $this->getVue()->form_connexionReussie();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_modification_utilisateur" :
                    $contenu = $this->getVue()->form_modification();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_modificationReussie_utilisateur" :
                    $contenu = $this->getVue()->form_modificationReussie();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "rechargementReussi_utilisateur" :
                    $contenu = $this->getVue()->form_rechargementReussi();
                    VueGenerique::setAffichage($contenu);
                    break;
                //case "form_deconnexion_utilisateur" :
                //    $contenu = $this->controleur->getVue()->form_deconnexion();
                //    VueGenerique::setAffichage($contenu);
                //    break;
                case "form_deconnexionReussie_utilisateur" :
                    $contenu = $this->getVue()->form_deconnexionReussie();
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_rechargement_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->getVue()->form_demandeConnexion();
                        VueGenerique::setAffichage($contenu);
                    } else {
                        $contenu = $this->getVue()->form_rechargement();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_plus_utilisateur" :
                    if (isset($_SESSION) && count($_SESSION) == 0) {
                        $contenu = $this->getVue()->form_demandeConnexion();
                        VueGenerique::setAffichage($contenu);
                    } else {
                        $contenu = $this->getVue()->form_plus();
                        VueGenerique::setAffichage($contenu);
                    }
                    break;
                case "form_produits_utilisateur" :
                    $contenu = $this->getVue()->form_liste_produits($this->lesProduits());
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_panier_utilisateur" :
                    $donneesPanier = $this->panier();
                    $contenu = $this->getVue()->form_panier_utilisateur(
                        $donneesPanier['details'],
                        $donneesPanier['total']
                    );
                    VueGenerique::setAffichage($contenu);
                    break;
                case "form_commande_statut_panier_utilisateur" :
                    $contenu = $this->getVue()->form_commande_statut_panier($this->modeleProduitVenduAcces->getStatutCommandesClient($_SESSION['id']));
                    VueGenerique::setAffichage($contenu);
                    break;
                case "ajout_utilisateur" :
                    $this->ajout();
                    break;
                case "verif_connexion" :
                    $this->connexion();
                    break;
                case "verif_modification" :
                    $this->modification();
                    break;
                case "verif_rechargement" :
                    $this->rechargement();
                    break;
                case "ajouter_panier" :
                    $this->ajouter_panier();
                    break;
                case "enlever_panier" :
                    $this->enlever_panier();
                    break;
                case "valider_commande" :
                    $this->valider_commande();
                    break;
                case "enlever_commande" :
                    $this->enlever_commande();
                    break;
                case "deconnexion" :
                    $this->deconnexion();
                    break;
                case "erreur" :
                    switch ($this) {
                        case "loginPasBon_utilisateur" :
                            $contenu = $this->getVue()->form_comptePasBonLogin();
                            VueGenerique::setAffichage($contenu);
                            break;
                        case "mdpPasBon_utilisateur" :
                            $contenu = $this->getVue()->form_mdpPasBon();
                            VueGenerique::setAffichage($contenu);
                            break;
                        case "connexionPasBon_utilisateur" :
                            $contenu = $this->getVue()->form_connexionPasBon();
                            VueGenerique::setAffichage($contenu);
                            break;
                        case "personneEstConnectee_utilisateur" :
                            $contenu = $this->getVue()->form_personneEstConnectee();
                            VueGenerique::setAffichage($contenu);
                            break;
                        case "emailDejaUtilise_utilisateur" :
                            $contenu = $this->getVue()->form_emailDejaUtilise();
                            VueGenerique::setAffichage($contenu);
                            break;
                        //case "montantInvalide_utilisateur" :
                        //    $contenu = $this->controleur->getVue()->form_montantInvalide();
                        //    VueGenerique::setAffichage($contenu);
                        //    break;
                    }
                    break;
            }
        }

        public function getVue()
        {
            return $this->vue;
        }

        public function ajout()
        {
            $this->modeleCompte->enregistrerCompte();
        }

        public function connexion()
        {
            $this->modeleCompte->connexion();
        }

        public function modification()
        {
            $this->modeleCompte->modification();
        }

        public function deconnexion()
        {
            $this->modeleCompte->deconnexion();
        }

        public function rechargement()
        {
            $this->modeleCompte->rechargement();
        }

        public function soldeEspace()
        {
            return $this->modeleCompte->getSolde();
        }

        public function historiqueRechargements()
        {
            return $this->modeleCompte->getHistoriqueRechargements($_SESSION['id']);
        }

        public function lesProduits()
        {
            return $this->modeleProduit->tousLesProduits();
        }

        public function ajouter_panier()
        {
            $id = isset($_POST['idProduit']) ? (int)$_POST['idProduit'] : null;

            $this->modeleProduit->ajouter_panier($id);

            header("Location: index.php?module=client&action=form_produits_utilisateur");
            exit();
        }

        public function enlever_panier()
        {
            $id = isset($_POST['idProduit']) ? (int)$_POST['idProduit'] : null;

            $this->modeleProduit->enlever_panier($id);

            header("Location: index.php?module=client&action=form_panier_utilisateur");
            exit();
        }

        public function panier()
        {
            return $this->modeleProduit->panier();
        }

        public function valider_commande() {
            $this->modeleProduitVenduAcces->valider_commande();

            header("Location: index.php?module=client&action=form_commande_statut_panier_utilisateur");
            exit();
        }

        public function enlever_commande()
        {
            $venteId = isset($_POST['vente_id']) ? (int)$_POST['vente_id'] : null;

            $this->modeleProduitVenduAcces->enlever_commande($venteId);

            header("Location: index.php?module=client&action=form_commande_statut_panier_utilisateur");
            exit();
        }

}