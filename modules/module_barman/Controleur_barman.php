<?php
class Controleur_barman {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleBarman();
        $this->vue = new Vue_barman();
    }

    public function getVue() {
        return $this->vue;
    }

    public function listeProduits() {
        return $this->modele->listerProduits();
    }

    public function rechercherClient()
    {
        $clients = [];
        $search = $_GET['search'] ?? null;

        if ($search) {
            $clients = $this->modele->rechercherClient($search);
        }

        return $this->vue->afficherClients($clients);
    }

    public function afficherCommandes()
    {
        $commandes = $this->modele->listerCommandesEnCours();
        return $this->vue->afficherCommandes($commandes);
    }

    public function afficherDetailCommande()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $resultat = $this->vue->afficherErreur("Aucun ID de commande spécifié");
        }
        else {
            $commande = $this->modele->getCommande($id);
            $produits = $this->modele->getProduitsCommande($id);

            $resultat = $this->vue->afficherDetailCommande($commande, $produits);
        }

        return $resultat;
    }

    public function historiqueDeCommandes() {
        return $this->modele->getHistoriqueCommandes();
    }

    public function derniereTransaction() {
       return $this->modele->getDerniereTransaction();
    }

    public function annulerTransaction() {
        $transaction_id = $_POST['transaction_id'] ?? null;

        if (!$transaction_id) {
            $resulat = $this->vue->afficherErreur("Aucune transaction spécifiée");
        }
        else {
            if ($this->modele->annulerTransaction($transaction_id)) {
                $resulat = $this->vue->afficherConfirmationAnnulation($transaction_id);
            } else {
                $resulat = $this->vue->afficherErreur("Échec de l'annulation de la transaction");
            }
        }
        return $resulat;
    }

    public function afficherFormTransaction($erreur = null, $donneesSaisies = null)
    {
        $produits = $this->modele->listerProduits();
        return $this->vue->afficherFormTransaction($produits, $erreur, $donneesSaisies);
    }

    public function traiterTransaction()
    {
        try {
            error_log("DEBUG: POST reçu: " . print_r($_POST, true));

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->vue->afficherErreur("Méthode non autorisée");
                return;
            }

            $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
            $produits = $_POST['produits'] ?? [];

            if ($client_id <= 0) {
                $this->afficherFormTransaction("L'ID client doit être un nombre positif", $_POST);
                return;
            }

            if (empty($produits) || !is_array($produits)) {
                $this->afficherFormTransaction("Aucun produit sélectionné", $_POST);
                return;
            }

            $soldeClient = $this->modele->getSoldeClient($client_id);
            if ($soldeClient === false) {
                $this->afficherFormTransaction("Client non trouvé (ID: $client_id)", $_POST);
                return;
            }

            $resultatValidation = $this->validerProduits($produits);

            if (!empty($resultatValidation['erreurs'])) {
                $this->afficherFormTransaction(implode("<br>", $resultatValidation['erreurs']), $_POST);
                return;
            }

            if (empty($resultatValidation['produitsValides'])) {
                $this->afficherFormTransaction("Aucun produit valide", $_POST);
                return;
            }

            $montantTotal = $resultatValidation['montantTotal'];
            if ($soldeClient < $montantTotal) {
                $erreurSolde = "Solde insuffisant. Solde du client: $soldeClient €, Total transaction: $montantTotal €";
                $this->afficherFormTransaction($erreurSolde, $_POST);
                return;
            }

            $vente_id = $this->modele->creerTransaction($resultatValidation['produitsValides'], $client_id, $montantTotal);

            if ($vente_id) {
                error_log("DEBUG: Transaction créée avec ID: " . $vente_id);
                $this->vue->afficherResultatTransaction($vente_id);
            } else {
                error_log("DEBUG: Échec de creerTransaction");
                $this->afficherFormTransaction("Erreur lors de la création de la transaction", $_POST);
            }

        } catch (Exception $e) {
            error_log("DEBUG: Exception attrapée: " . $e->getMessage());
            $this->afficherFormTransaction("Erreur technique: " . $e->getMessage(), $_POST);
        }
    }

    public function validerProduits($produits)
    {
        $produitsValides = [];
        $erreurs = [];
        $produitsIds = [];
        $montantTotal = 0;

        foreach ($produits as $index => $produit) {
            $produit_id = isset($produit['id']) ? (int)$produit['id'] : 0;
            $quantite = isset($produit['quantite']) ? (int)$produit['quantite'] : 0;

            if ($produit_id <= 0) {
                $erreurs[] = "Produit #" . ($index + 1) . ": ID invalide";
                continue;
            }

            if ($quantite <= 0) {
                $erreurs[] = "Produit #" . ($index + 1) . ": Quantité invalide";
                continue;
            }

            if (in_array($produit_id, $produitsIds)) {
                $erreurs[] = "Le produit ID $produit_id est en double";
                continue;
            }
            $produitsIds[] = $produit_id;

            $infoProduit = $this->modele->getInfoProduit($produit_id);
            if (!$infoProduit) {
                $erreurs[] = "Produit ID $produit_id non trouvé";
                continue;
            }

            if ($quantite > $infoProduit['disponibilite']) {
                $erreurs[] = "Stock insuffisant pour " . $infoProduit['nom'] . " (demandé: $quantite, disponible: " . $infoProduit['disponibilite'] . ")";
                continue;
            }

            $produitsValides[] = [
                'id' => $produit_id,
                'quantite' => $quantite,
                'prix' => (float)$infoProduit['prix']
            ];

            $montantTotal += $quantite * $infoProduit['prix'];
        }

        return [
            'produitsValides' => $produitsValides,
            'erreurs' => $erreurs,
            'montantTotal' => $montantTotal
        ];
    }
}