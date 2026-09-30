<?php

include_once "Vue_client.php";
include_once "commun/modele/CompteAcces.php";
include_once "commun/modele/ProduitAcces.php";
include_once "commun/modele/ProduitVenduAcces.php";
include_once "commun/modele/CodeValidationAcces.php";
class Controleur_client
{
    private $vue;
    private $modeleCompte;
    private $modeleProduit;
    private $modeleProduitVenduAcces;
    private $modeleCodeValidation;

    public function __construct()
    {
        $this->vue = new Vue_client();
        $this->modeleCompte = new CompteAcces();
        $this->modeleProduit = new ProduitAcces();
        $this->modeleProduitVenduAcces = new ProduitVenduAcces();
        $this->modeleCodeValidation = new CodeValidationAcces();
    }

    // Ajout d'un getter pour la vue si nécessaire
    public function getVue() {
        return $this->vue;
    }

    public function gererAction($action)
    {
        switch ($action) {
            case "accueil":
                $this->vue->afficherNav();
                $this->vue->afficherFooter();
                break;

            case "espace":
                $this->vue->form_espace($this->soldeEspace(), $this->historiqueRechargements());
                break;

            case "form_modification_utilisateur":
                $this->vue->form_modification();
                break;

            case "form_produits_utilisateur":
                $produits = $this->modeleProduit->liste_produits();
                $this->vue->form_liste_produits($produits);
                break;

            case "form_panier_utilisateur":
                $panier = $this->panier();
                $total = $this->modeleProduit->calculer_total_panier($panier);
                $this->vue->form_panier_utilisateur($panier, $total);
                break;

            case "form_rechargement_utilisateur":
                $this->vue->form_rechargement();
                break;

            case "form_commande_statut_panier_utilisateur" :
                $contenu = $this->getVue()->form_commande_statut_panier($this->modeleProduitVenduAcces->getStatutCommandesClient($_SESSION['id']));
                VueGenerique::setAffichage($contenu);
                break;
            case "form_historique_utilisateur":
                $donnees = $this->modeleProduitVenduAcces->getCommandesClient($_SESSION['id']);
                $historique = $this->getHistorique($donnees);
                $this->vue->form_historique($historique);
                break;
            // Actions de traitement (redirections)
            case "ajouter_panier":
                $this->ajouter_panier();
                break;

            case "valider_commande":
                $this->valider_commande();
                break;

            case "verif_rechargement":
                $this->verif_rechargement();
                break;
            case "enlever_panier":
                $this->enlever_panier();
                break;
            case "enlever_commande":
                $this->enlever_commande();
                break;
            case "modifier_quantite_panier":
                $this->modifier_quantite_panier();
                break;
            case "genererCode":
                $this->genererCode();
                break;

            case "afficherCode":
                $this->afficherCode();
                break;
            default:
                $this->vue->form_espace($this->soldeEspace(), $this->historiqueRechargements());
                break;
        }
    }

    // --- MÉTHODES DE DONNÉES ---

    public function soldeEspace() {
        return $this->modeleCompte->get_solde($_SESSION['id']);
    }

    public function historiqueRechargements() {
        return $this->modeleCompte->get_historique_rechargements($_SESSION['id']);
    }

    public function ajouter_panier() {
        $idProduit = isset($_POST['idProduit']) ? (int)$_POST['idProduit'] : null;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;

        if ($idProduit && $quantite > 0) {
            $this->modeleProduit->ajouter_au_panier($idProduit, $quantite);
        }
        header("Location: index.php?module=client&action=form_produits_utilisateur");
        exit();
    }

    public function panier() {
        return $this->modeleProduit->panier();
    }

    public function valider_commande() {
        $this->modeleProduitVenduAcces->valider_commande();
        header("Location: index.php?module=client&action=form_commande_statut_panier_utilisateur");
        exit();
    }

    public function verif_rechargement() {
        // Un client ne peut pas créditer lui-même son compte (aucun paiement n'est vérifié) :
        // le rechargement est effectué par un barman ou un gestionnaire.
        header("Location: index.php?module=client&action=espace");
        exit();
    }

    public function getHistorique($donnees) {
        $commandes = [];
        foreach ($donnees as $ligne) {
            $id = $ligne['vente_id'];
            if (!isset($commandes[$id])) {
                $commandes[$id] = [
                    'vente_id' => $id,
                    'date' => $ligne['dateVente'],
                    'montant' => $ligne['montant'],
                    'produits' => []
                ];
            }
            $commandes[$id]['produits'][] = [
                'nom' => $ligne['nom_produit'],
                'quantite' => $ligne['quantite'],
                'prix' => $ligne['prix_unitaire'] ?? 0
            ];
        }
        return $commandes;
    }
    public function enlever_panier()
    {
        $idProduit = isset($_POST['idProduit']) ? (int)$_POST['idProduit'] : null;
        if ($idProduit) {
            $this->modeleProduit->enlever_panier($idProduit);
        }
        header("Location: index.php?module=client&action=form_panier_utilisateur");
        exit();
    }
    public function enlever_commande()
    {
        $venteId = isset($_POST['vente_id']) ? (int)$_POST['vente_id'] : null;
        if ($venteId) {
            $this->modeleProduitVenduAcces->enlever_commande($venteId);
        }
        header("Location: index.php?module=client&action=form_commande_statut_panier_utilisateur");
        exit();
    }
    public function modifier_quantite_panier()
    {
        $idProduit = isset($_POST['idProduit']) ? (int)$_POST['idProduit'] : null;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;

        if ($idProduit && $quantite > 0) {
            $this->modeleProduit->modifier_quantite_panier($idProduit, $quantite);
        }

        header("Location: index.php?module=client&action=form_panier_utilisateur");
        exit();
    }
    public function genererCode()
    {
        $resultat = $this->modeleCodeValidation->genererCode($_SESSION['id']);

        // Stocker en session pour affichage
        $_SESSION['code_actuel'] = $resultat;

        header("Location: index.php?module=client&action=afficherCode");
        exit();
    }

    public function afficherCode()
    {
        $codeData = $_SESSION['code_actuel'] ?? null;

        // Si pas de code en session, vérifier s'il y en a un valide en base
        if (!$codeData) {
            $codeValide = $this->modeleCodeValidation->getCodeActuel($_SESSION['id']);
            if ($codeValide) {
                $codeData = $codeValide;
            }
        }

        $this->vue->afficherCodeGenere($codeData);
    }
}