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

    // Ajout d'un getter pour la vue si nécessaire
    public function getVue() {
        return $this->vue;
    }

    public function gererAction($action)
    {
        // On ne fait plus de "return", on appelle directement les méthodes qui font "echo"
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

            case "form_commande_statut_panier_utilisateur":
                $commandes = $this->modeleProduitVenduAcces->liste_commandes_utilisateur();
                $this->vue->form_commande_statut_panier($commandes);
                break;

            case "form_historique_utilisateur":
                $donnees = $this->modeleProduitVenduAcces->historique_commandes_utilisateur();
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
        if ($idProduit) {
            $this->modeleProduit->ajouter_au_panier($idProduit);
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
        $montant = $_POST['montant'] ?? 0;
        if ($montant > 0) {
            $this->modeleCompte->recharger_solde($_SESSION['id'], $montant);
        }
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
                'nom' => $ligne['nomProduit'],
                'quantite' => $ligne['quantite']
            ];
        }
        return $commandes;
    }
}