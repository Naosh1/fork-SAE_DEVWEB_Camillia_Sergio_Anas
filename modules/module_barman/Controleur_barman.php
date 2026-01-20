<?php

class Controleur_barman
{
    private $modele;

    private $vue;

    private $modeleStaff;
    private $vueStaff;

    private $modeleCommun;
    private $vueCommun;

    public function __construct()
    {
        $this->modele = new ModeleBarman();

        $this->modeleCommun = new ModeleCommun();
        $this->modeleStaff = new ModeleStaff();
        $this->vueCommun = new VueCommun();
        $this->vueStaff = new VueStaff();
        $this->vue = new Vue_barman();
    }

    public function gererAction($action)
    {
        error_log("Controleur_barman->gererAction('$action')");

        switch ($action) {
            case 'accueil':
                $this->afficherAccueil();
                break;
            case 'rechercherClientAjax':
                $this->rechercherClientAjax();
                break;
            case 'ajouterBarman':
                $this->gererAjoutBarman();
                break;
            case 'rechercherProduitAjax':
                $this->rechercherProduitAjax();
                break;
            case 'afficherProduits':
                $this->afficherProduits();
                break;
            case 'rechercherClient':
                $this->rechercherClient();
                break;
            case 'commandesEnCours':
                $this->afficherCommandes();
                break;
            case 'detailCommande':
                $this->afficherDetailCommande();
                break;
            case 'creerTransaction':
                $this->afficherFormTransaction();
                break;
            case 'traiterTransaction':
                $this->traiterTransaction();
                break;
            case 'monProfil':
                $utilisateur = $this->modeleCommun->getUtilisateur($_SESSION['id']);
                $this->vueCommun->afficherProfil($utilisateur);
                break;
            case 'messagerie':
                $messages = $this->modeleStaff->getMesMessages($_SESSION['id']);
                $this->vue->afficherNav();
                $this->vueStaff->afficherMesMessages($messages, $_SESSION['id']);
                break;
            case 'ecrireMessage':
                $destinataires = $this->modele->getToutStaff();
                $idCible = $_GET['id_dest'] ?? null;
                $sujet = $_GET['objet'] ?? "";
                $this->vue->afficherNav();
                $this->vueStaff->afficherFormulaireEnvoi($destinataires, $sujet, $idCible);
                break;

            case 'envoyerMessage':
                if (!empty($_POST['id_destinataire']) && !empty($_POST['contenu'])) {
                    $id_exp = $_SESSION['id'];
                    $id_dest = $_POST['id_destinataire'];
                    $objet = $_POST['objet'];
                    $contenu = $_POST['contenu'];

                    $this->modeleStaff->enregistrerMessage($id_exp, $id_dest, $objet, $contenu);
                    header("Location: index.php?action=messagerie");                }
                break;
            case 'listeStaff':
                $membres = $this->modeleStaff->getToutStaff();
                $this->vue->afficherClients($membres);
                break;
            case 'modifierProfil':
                break;
            case 'historiqueCommandes':
                $this->afficherHistoriqueCommandes();
                break;
            case 'derniereTransaction':
                $this->afficherDerniereTransaction();
                break;
            case 'annulerTransaction':
                $this->annulerTransaction();
                break;
            default:
                error_log("Action non reconnue: '$action', affichage accueil par défaut");
                $this->afficherAccueil();
                break;
        }
    }

    private function afficherAccueil()
    {
        $this->vue->afficherAccueil();
    }

    private function afficherProduits()
    {
        $produits = $this->modele->listerProduits();
        $assos = $this->modele->getAssociationIdParBarman($_SESSION['id']);
        $this->vue->afficherProduits($produits, $assos);
    }

    private function rechercherClient()
    {
        $clients = [];
        $search = $_GET['search'] ?? null;

        if ($search) {
            $clients = $this->modele->rechercherClient($search);
        }

        $this->vue->afficherClients($clients, $search);
    }

    private function afficherCommandes()
    {
        $commandes = $this->modele->listerCommandesEnCours();
        $this->vue->afficherCommandes($commandes);
    }

    private function afficherDetailCommande()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->vue->afficherErreur("Aucun ID de commande spécifié");
            return;
        }

        $commande = $this->modele->getCommande($id);
        $produits = $this->modele->getProduitsCommande($id);
        $this->vue->afficherDetailCommande($commande, $produits);
    }

    private function afficherHistoriqueCommandes()
    {
        $commandes = $this->modele->getHistoriqueCommandes();
        $this->vue->afficherHistoriqueCommandes($commandes);
    }

    private function afficherDerniereTransaction()
    {
        $transaction = $this->modele->getDerniereTransaction();
        $this->vue->afficherDerniereTransaction($transaction);
    }

    private function annulerTransaction()
    {
        $transaction_id = $_POST['transaction_id'] ?? null;

        if (!$transaction_id) {
            $this->vue->afficherErreur("Aucune transaction spécifiée");
            return;
        }

        if ($this->modele->annulerTransaction($transaction_id)) {
            $this->vue->afficherConfirmationAnnulation($transaction_id);
        } else {
            $this->vue->afficherErreur("Échec de l'annulation de la transaction");
        }
    }

    private function afficherFormTransaction($erreur = null, $donneesSaisies = null)
    {
        $produits = $this->modele->listerProduits();
        $this->vue->afficherFormTransaction($produits, $erreur, $donneesSaisies);
    }

    public function rechercherClientAjax()
    {
        $recherche = $_GET['q'] ?? '';
        if (strlen($recherche) < 1) {
            echo json_encode([]);
            exit;
        }
        $clients = $this->modele->rechercherClient($recherche);
        $clients = array_slice($clients, 0, 10);
        header('Content-Type: application/json');
        echo json_encode($clients);
        exit;
    }

    public function rechercherProduitAjax() {
        $recherche = $_GET['q'] ?? '';
        $idAsso = 1;

        $produits = $this->modele->rechercherProduitsParNom($recherche, $idAsso);
        header('Content-Type: application/json');
        echo json_encode($produits);
        exit;
    }

    public function gererAjoutBarman() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['asso_choisi'])) {
                header("Location: index.php?reset=1");
                exit();
            }

            $idAsso = $_SESSION['asso_choisi'];
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $mdp = $_POST['mdp'] ?? '123456';

            $succes = $this->modeleStaff->ajouterBarmanALAssociation($nom, $prenom, $email, $mdp, $idAsso);

            if ($succes) {
                $_SESSION['success'] = "Le barman a été ajouté avec succès à votre association.";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du barman.";
            }

            header("Location: index.php?module=gestionnaire&action=voirBarmans");
            exit();
        }
    }
    private function traiterTransaction()
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

    private function validerProduits($produits)
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