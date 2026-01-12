<?php
class Controleur_barman {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleBarman();
        $this->vue = new Vue_barman();
    }

    public function gererAction($action)
    {
        error_log("Controleur_barman->gererAction('$action')");

        switch ($action) {
            case 'accueil':
                $this->vue->afficherAccueil();
                break;
            case 'afficherProduits':
                $produits = $this->modele->listerProduits();
                $this->vue->afficherProduits($produits);
                break;
            case 'rechercherClient':
                $clients = [];
                if (isset($_GET['search'])) {
                    $clients = $this->modele->rechercherClient($_GET['search']);
                }
                $this->vue->afficherClients($clients);
                break;
            case 'commandesEnCours':
                $commandes = $this->modele->listerCommandesEnCours();
                $this->vue->afficherCommandes($commandes);
                break;
            case 'detailCommande':
                if (!isset($_GET['id'])) {
                    $this->vue->afficherErreur("Aucun ID de commande spécifié");
                    break;
                }
                $commande = $this->modele->getCommande($_GET['id']);
                $produits = $this->modele->getProduitsCommande($_GET['id']);
                $this->vue->afficherDetailCommande($commande, $produits);
                break;
            case 'creerTransaction':
                $produits = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produits);
                break;
            case 'traiterTransaction':
                error_log("Case traiterTransaction appelé");
                $this->traiterTransaction();
                break;
            default:
                error_log("Action non reconnue: '$action', affichage accueil par défaut");
                $this->vue->afficherAccueil();
                break;
        }
    }

    private function traiterTransaction() {
        try {
            // DEBUG: Vérifier ce qui est reçu
            error_log("DEBUG: POST reçu: " . print_r($_POST, true));

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->vue->afficherErreur("Méthode non autorisée");
                return;
            }

            $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
            $produits = $_POST['produits'] ?? [];

            error_log("DEBUG: Client ID: " . $client_id);
            error_log("DEBUG: Produits reçus: " . print_r($produits, true));

            if ($client_id <= 0) {
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, "L'ID client doit être un nombre positif", $_POST);
                return;
            }

            if (empty($produits) || !is_array($produits)) {
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, "Aucun produit sélectionné", $_POST);
                return;
            }

            $soldeClient = $this->modele->getSoldeClient($client_id);
            error_log("DEBUG: Solde client récupéré: " . $soldeClient);

            if ($soldeClient === false) {
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, "Client non trouvé (ID: $client_id)", $_POST);
                return;
            }

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

            if (!empty($erreurs)) {
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, implode("<br>", $erreurs), $_POST);
                return;
            }

            if (empty($produitsValides)) {
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, "Aucun produit valide", $_POST);
                return;
            }

            error_log("DEBUG: Montant total calculé: " . $montantTotal);
            error_log("DEBUG: Solde client: " . $soldeClient);

            if ($soldeClient < $montantTotal) {
                $produitsListe = $this->modele->listerProduits();
                $erreurSolde = "Solde insuffisant. Solde du client: $soldeClient €, Total transaction: $montantTotal €";
                $this->vue->afficherFormTransaction($produitsListe, $erreurSolde, $_POST);
                return;
            }

            error_log("DEBUG: Appel de creerTransaction...");
            $resultat = $this->modele->creerTransaction($produitsValides, $client_id);
            error_log("DEBUG: Résultat de creerTransaction: " . ($resultat ? $resultat : "false"));

            if ($resultat) {
                error_log("DEBUG: Transaction créée avec ID: " . $resultat);
                foreach ($produitsValides as $produit) {
                    $this->modele->updateStock($produit['id'], $produit['quantite']);
                }

                $this->vue->afficherResultatTransaction($resultat);
            } else {
                error_log("DEBUG: Échec de creerTransaction");
                $produitsListe = $this->modele->listerProduits();
                $this->vue->afficherFormTransaction($produitsListe, "Erreur lors de la création de la transaction (solde insuffisant ou autre erreur)", $_POST);
            }

        } catch (Exception $e) {
            error_log("DEBUG: Exception attrapée: " . $e->getMessage());
            $produitsListe = $this->modele->listerProduits();
            $this->vue->afficherFormTransaction($produitsListe, "Erreur technique: " . $e->getMessage(), $_POST);
        }
    }
}