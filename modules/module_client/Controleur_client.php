<?php

include_once "Vue_client.php";
include_once "commun/accés/CompteAcces.php";
include_once "commun/accés/ProduitAcces.php";


class Controleur_client
{
    private $vue;
    private $modeleCompte;
    private $modeleProduit;

    public function __construct()
    {
        $this->vue = new Vue_client();
        $this->modeleCompte = new CompteAcces();
        $this->modeleProduit = new ProduitAcces();
    }


    public function gererAction($action)
    {
        switch ($action) {
            case "accueil" :
                $contenu = $this->vue->afficherNav();
                VueGenerique::setAffichage($contenu);
                break;
                $contenu = $this->vue->form_espace($this->soldeEspace(), $this->historiqueRechargements());
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
            case
            "form_connexion_utilisateur" :
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
                    $contenu = $this->controleur->getVue()->form_demandeConnexion();
                    VueGenerique::setAffichage($contenu);
                } else {
                    $contenu = $this->controleur->getVue()->form_plus();
                    VueGenerique::setAffichage($contenu);
                }
                break;
            case "form_produits_utilisateur" :
                $contenu = $this->controleur->getVue()->form_liste_produits($this->controleur->lesProduits());
                VueGenerique::setAffichage($contenu);
                break;
            case "form_panier_utilisateur" :
                $donneesPanier = $this->controleur->panier();
                $contenu = $this->controleur->getVue()->form_panier_utilisateur(
                    $donneesPanier['details'],
                    $donneesPanier['total']
                );
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
            case "verif_rechargement" :
                $this->controleur->rechargement();
                break;
            case "ajouter_panier" :
                $this->controleur->ajouter_panier();
                break;
            case "deconnexion" :
                $this->deconnexion();
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
                    //case "montantInvalide_utilisateur" :
                    //    $contenu = $this->controleur->getVue()->form_montantInvalide();
                    //    VueGenerique::setAffichage($contenu);
                    //    break;
                }
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

        if ($id) {
            // On vérifie en base de données si le produit existe via le modèle
            $produit = $this->modeleProduit->rechercheProduitParID($id);

            if ($produit && $produit['quantiteActuelle'] > 0) {
                // Initialisation du panier en session si vide
                if (!isset($_SESSION['panier'])) {
                    $_SESSION['panier'] = [];
                }

                // Si le produit est déjà dans le panier, on augmente la quantité
                if (isset($_SESSION['panier'][$id])) {
                    $_SESSION['panier'][$id]++;
                } else {
                    $_SESSION['panier'][$id] = 1;
                }
            }
        }

        // Redirection vers la liste pour éviter de renvoyer le formulaire en actualisant
        header("Location: index.php?module=client&action=form_panier_utilisateur");
        exit();
    }

    public function panier()
    {
        $panier_details = [];
        $total_general = 0;

        if (!empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $id => $quantite) {
                $produit = $this->modeleProduit->rechercheProduitParID($id);
                if ($produit) {
                    $sous_total = $produit['prix'] * $quantite;
                    $total_general += $sous_total;
                    $panier_details[] = [
                        'id' => $id,
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'qte' => $quantite,
                        'sous_total' => $sous_total
                    ];
                }
            }
        }
        // On retourne les données calculées pour que le Mod puisse les passer à la vue
        return ['details' => $panier_details, 'total' => $total_general];
    }

}