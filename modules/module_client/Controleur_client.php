<?php

    include_once "Vue_client.php";
    include_once "commun/accés/CompteAcces.php";
    include_once "commun/accés/ProduitAcces.php";

    class Controleur_client {
        private $vue;
        private $modeleCompte;
        private $modeleProduit;

        public function __construct() {
            $this->vue = new Vue_client();
            $this->modeleCompte = new CompteAcces();
            $this->modeleProduit = new ProduitAcces();
        }

        public function getVue() {
            return $this->vue;
        }

        public function ajout() {
            $this->modeleCompte->enregistrerCompte();
        }

        public function connexion() {
            $this->modeleCompte->connexion();
        }

        public function modification() {
            $this->modeleCompte->modification();
        }

        public function deconnexion() {
            $this->modeleCompte->deconnexion();
        }

        public function rechargement() {
            $this->modeleCompte->rechargement();
        }

        public function soldeEspace() {
            return $this->modeleCompte->getSolde();
        }

        public function historiqueRechargements() {
            return $this->modeleCompte->getHistoriqueRechargements($_SESSION['id']);
        }

        public function lesProduits() {
            return $this->modeleProduit->tousLesProduits();
        }

        public function ajouter_panier() {
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

        public function panier() {
            $panier_details = [];
            $total_general = 0;

            if (!empty($_SESSION['panier'])) {
                foreach ($_SESSION['panier'] as $id => $quantite) {
                    $produit = $this->modeleProduit->rechercheProduitParID($id);
                    if ($produit) {
                        $sous_total = $produit['prix'] * $quantite;
                        $total_general += $sous_total;
                        $panier_details[] = [
                            'id'    => $id,
                            'nom'   => $produit['nom'],
                            'prix'  => $produit['prix'],
                            'qte'   => $quantite,
                            'sous_total' => $sous_total
                        ];
                    }
                }
            }
            // On retourne les données calculées pour que le Mod puisse les passer à la vue
            return ['details' => $panier_details, 'total' => $total_general];
        }

    }