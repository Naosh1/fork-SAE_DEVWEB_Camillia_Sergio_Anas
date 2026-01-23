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
                    header("Location: index.php?action=messagerie");
                }
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
            case 'changerStatut':
                $this->changerStatut();
                break;
            case 'vendre':
                $this->afficherPageVente();
                break;
            default:
                error_log("Action non reconnue: '$action', affichage accueil par défaut");
                $this->afficherAccueil();
                break;
        }
    }

    private function afficherAccueil()
    {
        // Récupération des données depuis le modèle
        $stats = $this->modele->getStatsAccueil();
        $dernierClient = $this->modele->getDernierClientActif();
        $ventesRecentes = $this->modele->getVentesRecentesTableau();
        $stockCritiqueListe = $this->modele->getListeStockCritique();

        // Envoi à la vue
        $this->vue->afficherAccueil($stats, $dernierClient, $ventesRecentes, $stockCritiqueListe);
    }

    private function afficherProduits()
    {
        $produits = $this->modele->listerProduits();
        $assos = $this->modele->getAssociationIdParBarman($_SESSION['id']);
        $this->vue->afficherProduits($produits, $assos);
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
        $searchProduit = $_GET['search_produit'] ?? null;
        $typeProduit = $_GET['type_produit'] ?? null;
        $clientId = $_GET['id_client'] ?? null;

        if (!$clientId) {
            $this->vue->afficherErreur("Veuillez d'abord sélectionner un client");
            return;
        }

        $types = $this->modele->getTypesProduits();

        if ($searchProduit || $typeProduit) {
            $produits = $this->modele->rechercherProduits($searchProduit, $typeProduit);
        } else {
            $produits = $this->modele->listerProduits();
        }

        $this->vue->afficherFormTransaction($produits, $types, $clientId, $searchProduit, $typeProduit, $erreur, $donneesSaisies);
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

    public function rechercherProduitAjax()
    {
        $recherche = $_GET['q'] ?? '';
        $idAsso = 1;
        $produits = $this->modele->rechercherProduitsParNom($recherche, $idAsso);
        header('Content-Type: application/json');
        echo json_encode($produits);
        exit;
    }

    public function gererAjoutBarman()
    {
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
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->vue->afficherErreur("Méthode non autorisée");
                return;
            }

            $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
            $produits = $_POST['produits'] ?? [];
            $codeValidation = $_POST['code_validation'] ?? null;

            if ($client_id <= 0) {
                $this->vue->afficherErreur("L'ID client doit être un nombre positif");
                return;
            }

            $produitsFiltered = [];
            foreach ($produits as $id => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $produitsFiltered[$id] = $qty;
                }
            }

            if (empty($produitsFiltered)) {
                $this->vue->afficherErreur("Aucun produit sélectionné");
                return;
            }

            if (!$codeValidation) {
                $this->afficherDemandeCode($client_id, $produitsFiltered);
                return;
            }

            include_once "commun/accés/CodeValidationAcces.php";
            $modeleCode = new CodeValidationAcces();
            $codeValide = $modeleCode->verifierCode($codeValidation, $client_id);

            if (!$codeValide) {
                $this->afficherDemandeCode($client_id, $produitsFiltered, "Code invalide ou expiré");
                return;
            }

            $modeleCode->invaliderCode($client_id);

            $soldeClient = $this->modele->getSoldeClient($client_id);
            if ($soldeClient === false) {
                $this->vue->afficherErreur("Client non trouvé");
                return;
            }

            $montantTotal = 0;
            $produitsValides = [];
            $erreurs = [];

            foreach ($produitsFiltered as $produit_id => $quantite) {
                $infoProduit = $this->modele->getInfoProduit($produit_id);

                if (!$infoProduit) {
                    $erreurs[] = "Produit #$produit_id non trouvé";
                    continue;
                }

                if ($infoProduit['disponibilite'] < $quantite) {
                    $erreurs[] = "{$infoProduit['nom']}: stock insuffisant ({$infoProduit['disponibilite']} disponible)";
                    continue;
                }

                $montantTotal += $infoProduit['prix'] * $quantite;
                $produitsValides[] = [
                    'id' => $produit_id,
                    'quantite' => $quantite,
                    'prix' => $infoProduit['prix']
                ];
            }

            if (!empty($erreurs)) {
                $this->afficherDemandeCode($client_id, $produitsFiltered, implode("<br>", $erreurs));
                return;
            }

            if ($soldeClient < $montantTotal) {
                $this->afficherDemandeCode($client_id, $produitsFiltered,
                    "Solde insuffisant. Solde: {$soldeClient}€, Total: {$montantTotal}€");
                return;
            }

            $vente_id = $this->modele->creerTransaction($produitsValides, $client_id, $montantTotal);

            if ($vente_id) {
                $this->vue->afficherResultatTransaction($vente_id);
            } else {
                $this->vue->afficherErreur("Erreur lors de la création de la transaction");
            }

        } catch (Exception $e) {
            error_log("Erreur traiterTransaction: " . $e->getMessage());
            $this->vue->afficherErreur("Erreur technique: " . $e->getMessage());
        }
    }

    private function changerStatut()
    {
        $venteId = $_POST['vente_id'] ?? null;
        $nouveauStatut = $_POST['nouveau_statut'] ?? null;

        if (!$venteId || !$nouveauStatut) {
            $this->vue->afficherErreur("Paramètres manquants");
            return;
        }

        if ($this->modele->changerStatutCommande($venteId, $nouveauStatut)) {
            header("Location: index.php?module=barman&action=commandesEnCours");
            exit();
        } else {
            $this->vue->afficherErreur("Erreur lors du changement de statut");
        }
    }

    private function afficherPageVente()
    {
        $searchClient = $_GET['search_client'] ?? null;
        $clientId = $_GET['id_client'] ?? null;
        $searchProduit = $_GET['search_produit'] ?? null;
        $typeProduit = $_GET['type_produit'] ?? null;

        $clients = [];
        if ($searchClient) {
            $clients = $this->modele->rechercherClientsDeMonAssociation($searchClient, $_SESSION['id']);
        }

        $clientSelectionne = null;
        if ($clientId) {
            foreach ($clients as $c) {
                if ($c['id'] == $clientId) {
                    $clientSelectionne = $c;
                    break;
                }
            }
            if (!$clientSelectionne) {
                $stmt = Connexion::getBdd()->prepare('SELECT id, nom, prenom, email, solde FROM compte WHERE id = ?');
                $stmt->execute([$clientId]);
                $clientSelectionne = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        $types = $this->modele->getTypesProduits();
        $produits = [];

        if ($clientId) {
            if ($searchProduit || $typeProduit) {
                $produits = $this->modele->rechercherProduits($searchProduit, $typeProduit);
            } else {
                $produits = $this->modele->listerProduits();
            }
        }

        $this->vue->afficherPageVente($clients, $clientSelectionne, $produits, $types, $searchClient, $searchProduit, $typeProduit);
    }

    private function afficherDemandeCode($client_id, $produits, $erreur = null)
    {
        $stmt = Connexion::getBdd()->prepare('SELECT nom, prenom FROM compte WHERE id = ?');
        $stmt->execute([$client_id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        $recap = [];
        $montantTotal = 0;

        foreach ($produits as $produit_id => $quantite) {
            $infoProduit = $this->modele->getInfoProduit($produit_id);
            if ($infoProduit) {
                $sousTotal = $infoProduit['prix'] * $quantite;
                $montantTotal += $sousTotal;
                $recap[] = [
                    'nom' => $infoProduit['nom'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $infoProduit['prix'],
                    'sous_total' => $sousTotal
                ];
            }
        }

        $this->vue->afficherDemandeCodeValidation($client, $client_id, $produits, $recap, $montantTotal, $erreur);
    }

    private function rechercherClient()
    {
        $clients = [];
        $search = $_GET['search'] ?? '';

        if ($search) {
            $clients = $this->modele->rechercherClientsDeMonAssociation($search, $_SESSION['id']);
        }

        $this->vue->afficherClients($clients, $search);
    }
}