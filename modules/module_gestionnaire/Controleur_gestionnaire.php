<?php
include_once 'modules/module_commun/modele_commun.php';

class ControleurGestionnaire
{
    private $modeleStaff;
    private $vueStaff;

    private $modeleCommun;
    private $vueCommun;

    private $modele;
    private $vue;


    public function __construct()
    {
        $this->modeleCommun = new ModeleCommun();
        $this->modeleStaff = new ModeleStaff();
        $this->modele = new ModeleGestionnaire();
        $this->vue = new VueGestionnaire();
        $this->vueCommun = new VueCommun();
        $this->vueStaff = new VueStaff();

    }

    public function gererAction($action)
    {
        switch ($action) {
            case 'accueil':
                $this->afficherTableauDeBord();
                break;

            case 'voirProduits':
                $this->afficherProduits();
                break;

            case 'ajouterProduit':
                $this->gererAjoutProduit();
                break;

            case 'modifierProduit':
                $this->gererModificationProduit();
                break;

            case 'supprimerProduit':
                $this->supprimerProduit();
                break;

            case 'ajouterStock':
                $this->gererAjoutStock();
                break;

            case 'profil':
                $this->afficherProfil();
                break;

            case 'updateProfil':
                $this->mettreAJourProfil();
                break;

            case 'modifierPP':
                $this->modifierPhotoProfil();
                break;

            case 'associations':
                $this->afficherAssociations();
                break;

            case 'accepterAssociation':
                $this->accepterAssociation();
                break;

            case 'voirAssociation':
            case 'gererAssociation':
                $this->afficherDetailsAssociation();
                break;

            case 'voirStockAsso':
                $this->afficherStockAssociation();
                break;

            case 'barmans':
                $this->afficherBarmans();
                break;

            case 'ajouterBarman':
                $this->gererAjoutBarman();
                break;

            case 'toggleBarman':
                $this->activerDesactiverBarman();
                break;

            case 'supprimerBarman':
                $this->supprimerBarman();
                break;

            case 'voirProfilBarman':
                $this->afficherProfilBarman();
                break;

            case 'voirBarmans':
                $this->afficherBarmansAssociation();
                break;

            case 'clients':
                $this->afficherClients();
                break;

            case 'ajouterClient':
            case 'validerAjoutClient':
                $this->gestionAjoutClient();
                break;

            case 'gererSolde':
                $this->gererSoldeClient();
                break;

            case 'voirListeClients':
                $this->afficherClientsAssociation();
                break;

            case 'fournisseurs':
                $this->afficherFournisseurs();
                break;

            case 'voirFournisseur':
                $this->commanderFournisseur();
                break;

            case 'validerLiaisonFournisseur':
                $this->validerLiaisonFournisseur();
                break;

            case 'rechercherPrix':
                $this->rechercherPrixProduit();
                break;

            case 'validerCommandeFournisseur':
                $this->validerCommandeFournisseur();
                break;
            case 'distribuer':
                $this->afficherDistribution();
                break;
            case 'validerDistribution':
                $this->validerDistribution();
                break;
            case 'mesCommandes':
                $this->afficherMesCommandes();
                break;

            case 'detailCommande':
                $this->afficherDetailCommande();
                break;

            case 'annulerCommande':
                $this->annulerCommande();
                break;

            case 'mesMessages':
                $this->afficherMessages();
                break;

            case 'ecrireMessage':
                $this->afficherFormulaireMessage();
                break;

            case 'envoyerMessageGlobal':
                $this->envoyerMessage();
                break;

            case 'faireInventaire':
                $this->afficherFormulaireInventaire();
                break;

            case 'enregistrerInventaire':
                $this->enregistrerInventaire();
                break;

            case 'statistiques':
                $this->afficherStatistiques();
                break;

            case 'produits':
                $this->afficherTousProduits();
                break;

            case 'stock':
                $this->afficherStockGeneral();
                break;

            case 'ventes':
                $this->afficherVentes();
                break;

            default:
                $this->afficherErreurAction();
                break;
        }
    }


    private function afficherTableauDeBord()
    {
        $idGest = $_SESSION['id'];

        $assoUnique = $this->modele->getAssociationsParGestionnaire($idGest);
        $associations = ($assoUnique) ? [$assoUnique] : [];

        $alertes = $this->modele->getStockCritique($idGest);
        $topProduits = $this->modele->getTopProduits($idGest);
        $totalPertesGlobal = $this->modele->getTotalPertes($idGest);

        $totalRecettesGlobal = 0;
        $beneficeTotalNet = 0;
        $tousLesBarmans = [];

        foreach ($associations as $asso) {
            $barmansAsso = $this->modele->getBarmansDeMonAssociation($idGest);

            foreach ($barmansAsso as $b) {
                $tousLesBarmans[$b['id']] = $b;
            }

            $statsAsso = $this->modele->getBenefices($asso['id']);
            $totalRecettesGlobal += $statsAsso['recettes'];
            $beneficeTotalNet += $statsAsso['benefice_net'];
        }

        $statsQuotidiennes = $this->modele->getStatsEvolutionSeptJours($idGest);

        $data = [
            'associations' => $associations,
            'alertes' => $alertes,
            'topProduits' => $topProduits,
            'totalPertes' => $totalPertesGlobal,
            'totalRecettes' => $totalRecettesGlobal,
            'beneficeNet' => $beneficeTotalNet,
            'nbBarmans' => count($tousLesBarmans),
            'courbes' => [
                'labels' => $statsQuotidiennes['dates'] ?? [],
                'tresorerie' => $statsQuotidiennes['recettes'] ?? [],
                'pertes' => $statsQuotidiennes['pertes'] ?? [],
                'benefices' => $statsQuotidiennes['benefices'] ?? []
            ]
        ];

        $this->vue->afficherTableauDeBordAccueil($_SESSION['prenom'], $data);
    }

    private function afficherProduits()
    {
        $id_asso = $_GET['id'] ?? null;
        $id_gest = $_SESSION['id'];
        $tri = $_GET['tri'] ?? 'nom';
        $produits = $this->modele->getProduitsFiltres($id_gest, $id_asso, $tri);
        $associations = $this->modele->getAssociationsParGestionnaire($id_gest);

        $titre = "Stock Global";
        if ($id_asso && !empty($produits)) {
            $titre = "Stock : " . $produits[0]['nom_association'];
        }

        $this->vue->afficherProduits($produits, $associations, $titre);
    }

    private function gererAjoutProduit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id_compte = $_SESSION['id'] ?? null;
            $associations = $this->modele->getAssociationsParGestionnaire($id_compte);
            $this->vue->formulaireAjoutProduit($associations);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $type = $_POST['type'];
            $prix = floatval($_POST['prix']);
            $stock = intval($_POST['stock']);
            $assoId = $_POST['association_id'] ?? null;

            $produitId = $this->modele->ajouterProduit($nom, $type, $prix, $stock);

            if ($produitId && $assoId) {
                $this->modele->lierProduitAssociation($produitId, $assoId);
                $_SESSION['success'] = "Produit '$nom' ajouté avec succès.";
            } else {
                $_SESSION['error'] = "Échec de l'ajout du produit.";
            }

            header('Location: index.php?action=voirProduits');
            exit();
        }
    }

    private function gererModificationProduit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $produit = $this->modele->getProduitParId($_GET['id']);
            if ($produit) {
                $this->vue->formulaireModificationProduit($produit);
            } else {
                $_SESSION['error'] = "Produit non trouvé";
                header('Location: index.php?action=produits');
                exit();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->modele->modifierProduit(
                $_POST['id'],
                $_POST['nom'],
                $_POST['type'],
                $_POST['prix'],
                $_POST['stock']
            );
            if ($success) {
                $_SESSION['success'] = "Produit modifié avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification";
            }
            header('Location: index.php?action=produits');
            exit();
        }
    }

    private function supprimerProduit()
    {
        if (isset($_GET['id'])) {
            $success = $this->modele->supprimerProduit($_GET['id']);
            if ($success) {
                $_SESSION['success'] = "Produit supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression";
            }
            header('Location: index.php?action=produits');
            exit();
        }
    }

    private function gererAjoutStock()
    {
        $id_produit = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $produit = $this->modele->getProduitParId($id_produit);
            $this->vue->formulaireStock($produit);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantiteAjoutee = intval($_POST['quantite']);
            $id = $_POST['id_produit'];
            $this->modele->updateStock($id, $quantiteAjoutee);
            header('Location: index.php?action=voirProduits');
            exit();
        }
    }

    private function afficherProfil()
    {
        $id_user = $_SESSION['id'];
        $user = $this->modele->getUtilisateur($id_user);
        $_SESSION['photo'] = $user['photo'];
        $this->vue->afficherProfil($user);
    }

    private function mettreAJourProfil()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['id'];
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $tel = preg_replace('/[^0-9+]/', '', $_POST['tel']);

            if (strlen($tel) > 15) {
                header("Location: index.php?action=profil&error=tel_trop_long");
                exit();
            }

            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $user = $this->modele->getUtilisateur($id_user);
            $password_hash = null;

            if (!empty($old_password)) {
                if (!password_verify($old_password, $user['mdp'])) {
                    header("Location: index.php?action=profil&error=password_incorrect");
                    exit();
                }
                if (!empty($new_password)) {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                }
            }

            if ($this->modele->updateUserInfos($id_user, $nom, $prenom, $email, $tel, $password_hash)) {
                $_SESSION['prenom'] = $prenom;
                header("Location: index.php?action=profil&success=1");
            } else {
                header("Location: index.php?action=profil&error=update_failed");
            }
            exit();
        }
    }

    private function modifierPhotoProfil()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
            $id_user = $_SESSION['id'];
            $file = $_FILES['profile_picture'];

            if ($file['error'] === 0) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!is_dir("uploads/profiles/")) {
                    mkdir("uploads/profiles/", 0777, true);
                }

                $nom_image = "pp_" . $id_user . "_" . time() . "." . $ext;

                if (move_uploaded_file($file['tmp_name'], "uploads/profiles/" . $nom_image)) {
                    $this->modele->updateUserPhoto($id_user, $nom_image);
                    $_SESSION['photo'] = $nom_image;
                    $_SESSION['success_msg'] = "Photo mise à jour avec succès !";
                }
            }
            header("Location: index.php?action=profil");
            exit();
        }
    }

    private function afficherAssociations()
    {
        $id_gestionnaire = $_SESSION['id'] ?? '';

        // On utilise la fonction qui fait le fetchAll() pour éviter que la vue soit vide
        $associations = $this->modele->getAssociationsGerees($id_gestionnaire);

        // On passe le résultat à la vue
        $this->vue->afficherAssociationsValidees($associations);
    }
    private function accepterAssociation()
    {
        if (isset($_GET['id'])) {
            $assoId = $_GET['id'];
            $association = $this->modele->getAssociationParId($assoId);

            if (!$association) {
                $_SESSION['error'] = "Association non trouvée";
                header('Location: index.php?action=associations');
                exit();
            }

            if ($association['status'] !== 'validee') {
                $_SESSION['error'] = "Association non validée";
                header('Location: index.php?action=associations');
                exit();
            }

            $idGestionnaire = $_SESSION['id'] ?? null;
            if ($idGestionnaire) {
                $this->modele->accepterAssociation($assoId, $idGestionnaire);
                $_SESSION['success'] = "Association acceptée avec succès";
            }

            header('Location: index.php?action=associations');
            exit();
        }
    }

    private function afficherDetailsAssociation()
    {
        if (isset($_GET['id'])) {
            $assoId = $_GET['id'];
            $assos = $this->modele->getDetailsAssos($assoId);

            if ($assos) {
                $this->vue->afficherDetailsAssos($assos);
            } else {
                $_SESSION['error'] = "Association introuvable.";
                header('Location: index.php?action=associations');
                exit();
            }
        } else {
            $_SESSION['error'] = "ID manquant.";
            header('Location: index.php?action=associations');
            exit();
        }
    }

    private function afficherStockAssociation()
    {
        $id_asso = $_GET['id_asso'] ?? null;
        $produits = $this->modele->getProduitsFiltres(null, $id_asso);
        $this->vue->afficherProduits($produits, "Stock de l'Association");
    }

    private function afficherBarmans()
    {
        $idGestionnaire = $_SESSION['id'];

        $assoUnique = $this->modele->getAssociationsParGestionnaire($idGestionnaire);
        $associations = ($assoUnique && isset($assoUnique['id'])) ? [$assoUnique] : $assoUnique;

        $barmans = $this->modele->getBarmansDeMonAssociation($idGestionnaire);
        $this->vue->afficherBarmans($barmans, $associations);
    }

    private function gererAjoutBarman()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = $_POST['client_id'] ?? null;
            $assoId = $_POST['association_id'] ?? null;

            if ($clientId && $assoId) {
                $res = $this->modele->ajouterClientCommeBarman($clientId, $assoId);

                if ($res) {
                    $this->vue->afficherSuccesPromotion();
                    exit();
                } else {
                    $_SESSION['error'] = "Ce membre est déjà barman ou une erreur est survenue.";
                    header('Location: index.php?action=barmans');
                    exit();
                }
            }
        } else {
            $q = $_GET['q'] ?? '';
            $clients = $this->modele->rechercherClients($q);
            $assoData = $this->modele->getAssociationsParGestionnaire($_SESSION['id']);
            $associations = (isset($assoData['id'])) ? [$assoData] : $assoData;

            $this->vue->formulaireAjouterBarman($clients, $associations);
        }
    }

    private function activerDesactiverBarman()
    {
        if (isset($_GET['id'])) {
            $estActif = $this->modele->estBarmanActif($_GET['id']);
            $success = $this->modele->activerDesactiverBarman($_GET['id'], !$estActif);

            if ($success) {
                $_SESSION['success'] = "Barman " . (!$estActif ? "activé" : "désactivé") . " avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du statut";
            }
            header('Location: index.php?action=barmans');
            exit();
        }
    }

    private function supprimerBarman()
    {
        if (isset($_GET['id'])) {
            $success = $this->modele->supprimerBarman($_GET['id']);
            if ($success) {
                $_SESSION['success'] = "Barman supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression";
            }
            header('Location: index.php?action=barmans');
            exit();
        }
    }

    private function afficherProfilBarman()
    {
        $id_cible = $_GET['id'] ?? null;
        if ($id_cible) {
            $infosBarman = $this->modele->getBarmanParId($id_cible);
            if (!$infosBarman) {
                $_SESSION['error'] = "Données introuvables pour l'ID : " . $id_cible;
                header('Location: index.php?action=barmans');
                exit();
            }

            $this->vue->afficherProfilBarman($infosBarman);
        } else {
            $_SESSION['error'] = "Aucun ID spécifié pour la consultation.";
            header('Location: index.php?action=barmans');
            exit();
        }
    }
    public function getBarmansParAssociation($id_gestionnaire)
    {
        try {
            $sql = "SELECT c.*, asso.nom AS nom_association 
                FROM compte c
                JOIN appartient a ON c.id = a.compte_id
                JOIN association asso ON a.association_id = asso.id
                JOIN gestionne g ON asso.id = g.association_id
                WHERE g.compte_id = ? AND a.role = 'barman'";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$id_gestionnaire]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function afficherBarmansAssociation()
    {
        $id_gestionnaire = $_SESSION['id'];
        $asso = $this->modele->getAssociationsParGestionnaire($id_gestionnaire);

        if ($asso && isset($asso['id'])) {
            $id_asso = $asso['id'];

            $barmans = $this->modele->getBarmansParAssociation($id_asso);

            $associations = [$asso];

            $this->vue->afficherBarmans($barmans, $associations);
        } else {
            $this->vue->afficherBarmans([], []);
        }
    }

    private function gererSoldeClient()
    {
        $id = $_GET['id'] ?? $_POST['id_personne'] ?? null;
        if (!$id) {
            header("Location: index.php?action=clients");
            exit();
        }
        $personne = $this->modele->getClientParId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $montant = floatval($_POST['montant'] ?? 0);
            $operation = $_POST['operation'] ?? null;
            $idAsso = $_POST['id_asso'] ?? $personne['association_id'] ?? null;

            if ($montant > 0) {
                $nouveauSolde = ($operation === 'ajouter')
                    ? $personne['solde'] + $montant
                    : $personne['solde'] - $montant;

                if ($this->modele->changerSoldeClient($id, $nouveauSolde)) {
                    $_SESSION['success'] = "Solde mis à jour.";
                }
            }
            if ($idAsso) {
                header("Location: index.php?action=voirListeClients&id=" . $idAsso);
            } else {
                header("Location: index.php?action=clients");
            }
            exit();
        }

        $this->vue->afficherFormulaireSolde($personne);
    }

    private function afficherClients()
    {
        $utilisateurs = $this->modele->getTousLesClients();
        $this->vue->afficherUtilisateurs($utilisateurs);
    }

    public function gestionAjoutClient()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_client = $_POST['id_client'] ?? null;
            $id_assos = $_POST['id_assos'] ?? null;

            if ($id_client && $id_assos) {
                $success = $this->modele->ajouterClient($id_client, $id_assos);
                if ($success) {
                    // Redirige vers l'accueil ou la liste des clients
                    header("Location: index.php?action=accueil&success=client_ajoute");
                    exit();
                } else {
                    $this->preparerVueAjout("Ce client fait déjà partie de cette association ou erreur technique.");
                }
            } else {
                $this->preparerVueAjout("Veuillez remplir tous les champs.");
            }
        } else {
            $this->preparerVueAjout();
        }
    }

    private function preparerVueAjout($erreur = null)
    {
        $id_gest = $_SESSION['id'];
        // On utilise getAssociationsGerees car elle fait un fetchAll()
        $associations = $this->modele->getAssociationsGerees($id_gest);
        $clients = $this->modele->getClientSansAssos();

        $this->vue->afficherFormulaireAjoutClient($associations, $clients, $erreur);
    }
    private function afficherClientsAssociation()
    {
        $idAsso = $_GET['id'] ?? $_SESSION['id_association'] ?? null;
        $association = $this->modele->getAssociationParId($idAsso);

        if ($association) {
            $clients = $this->modele->getClientsParAssociation($association['id']);
            $this->vue->afficherClients($clients);
        } else {
            $_SESSION['error'] = "Aucune association trouvée.";
            header("Location: index.php?action=accueil");
            exit;
        }
    }


    private function afficherFournisseurs()
    {
        $fournisseurs = $this->modele->getTousLesFournisseurs();


        $idGest = $_SESSION['id'];
        $associations = $this->modele->getAssociationsGerees($idGest);

        $this->vue->afficherFournisseurs($fournisseurs, $associations);
    }

    private function afficherDetailsFournisseur()
    {
        $id = $_GET['id'];
        $tri = $_GET['tri'] ?? 'nom';
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';

        $fournisseur = $this->modele->getFournisseurParId($id);
        $produits = $this->modele->getProduitsFournisseur($id, $tri, $search, $type);

        $this->vue->afficherDetailsFournisseur($fournisseur, $produits);
    }

    private function rechercherPrixProduit()
    {
        $recherche = $_GET['q'] ?? null;
        $resultats = [];

        if ($recherche) {
            $resultats = $this->modele->rechercherProduitGlobal($recherche);
        }
        $this->vue->afficherRechercheGlobale($resultats, $recherche);
    }

    public function validerLiaisonFournisseur()
    {
        $id_f = filter_input(INPUT_POST, 'id_fournisseur', FILTER_VALIDATE_INT);
        $id_p = filter_input(INPUT_POST, 'id_produit', FILTER_VALIDATE_INT);
        $prix = filter_input(INPUT_POST, 'prix_achat', FILTER_VALIDATE_FLOAT);
        $delai = filter_input(INPUT_POST, 'delai', FILTER_VALIDATE_INT);

        if ($id_f && $id_p && $prix !== false) {
            $success = $this->modele->ajouterLienFournisseur($id_f, $id_p, $prix, $delai);

            if ($success) {
                header("Location: index.php?action=voirFournisseur&id=$id_f&message=Produit ajouté");
                exit();
            } else {
                $erreur = "Ce produit est déjà lié à ce fournisseur ou erreur technique.";
            }
        } else {
            $erreur = "Veuillez remplir tous les champs correctement.";
        }

        $this->vue->afficherFormulaireLiaisonFournisseur(
            $this->modele->getTousLesFournisseurs(),
            $this->modele->getProduitsTousLesFournisseurs(),
            $erreur
        );
    }

    private function afficherMesCommandes()
    {
        $idGestionnaire = $_SESSION['id'];
        $commandes = $this->modele->getCommandesParGestionnaire($idGestionnaire);
        $this->vue->afficherListeCommandes($commandes);
    }

    public function validerDistribution()
    {
        $idAsso = $_POST['id_association'] ?? null;
        $distribution = $_POST['distribution'] ?? [];

        if ($idAsso && !empty($distribution)) {
            foreach ($distribution as $idProduit => $quantite) {
                if ($quantite > 0) {
                    $this->modele->distribuerStock($idAsso, $idProduit, $quantite);
                }
            }
            header("Location: index.php?action=mesCommandes&success=distribue");
            exit;
        }
    }

    private function afficherDistribution()
    {
        $idGest = $_SESSION['id'];

        $associations = $this->modele->getAssociationsParGestionnaire($idGest);
        $reserve = $this->modele->getStockReserveGlobal();

        $commandesFournisseurs = $this->modele->getCommandesFournisseursRecentes($idGest);

        $historiqueAchats = $this->modele->getHistoriqueAchatsComplet($idGest);

        $this->vue->afficherAffectationStock($associations, $reserve, $commandesFournisseurs, $historiqueAchats);
    }


    public function validerCommandeFournisseur()
    {
        $idAsso = $_POST['id_association'] ?? null;
        $idFournisseur = $_POST['id_fournisseur'] ?? null;
        $produitsPost = $_POST['produits'] ?? [];

        if (empty($idAsso) || empty($idFournisseur)) {
            die("Erreur : Données manquantes (Asso ou Fournisseur).");
        }

        try {
            $this->modele->getBdd()->beginTransaction();

            $idCommande = $this->modele->creerEnteteCommande($idFournisseur, $idAsso);

            $totalGlobal = 0;
            $aDesProduits = false;

            foreach ($produitsPost as $idProduit => $details) {
                $qte = intval($details['quantite'] ?? 0);

                if ($qte > 0) {
                    $infos = $this->modele->getPrixAchatFournisseur($idFournisseur, $idProduit);

                    if ($infos && isset($infos['prix_achat'])) {
                        $prix = $infos['prix_achat'];
                        $sousTotal = $prix * $qte;
                        $totalGlobal += $sousTotal;

                        $this->modele->ajouterLigneCommande($idCommande, $idProduit, $qte, $prix);
                        $aDesProduits = true;
                    } else {
                        throw new Exception("Prix introuvable pour le produit ID: $idProduit");
                    }
                }
            }

            if ($aDesProduits && $totalGlobal > 0) {
                $this->modele->majMontantTotalCommande($idCommande, $totalGlobal);
                $this->modele->debiterSoldeAssociation($idAsso, $totalGlobal);
                $this->modele->getBdd()->commit();
                $this->vue->afficherSuccesCommande();

            } else {
                if ($this->modele->getBdd()->inTransaction()) {
                    $this->modele->getBdd()->rollBack();
                }
                die("Erreur : Aucun produit sélectionné.");
            }

        } catch (Exception $e) {
            if ($this->modele->getBdd()->inTransaction()) {
                $this->modele->getBdd()->rollBack();
            }
            die("Erreur lors de la validation : " . $e->getMessage());
        }
    }

    public function commanderFournisseur()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $fournisseur = $this->modele->getFournisseurParId($id);
            $produits = $this->modele->getProduitsFournisseur($id);

            $this->vue->afficherDetailsFournisseur($fournisseur, $produits);
        }
    }

    private function afficherDetailCommande()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Commande non spécifiée.";
            header('Location: index.php?action=mesCommandes');
            exit();
        }

        $idCommande = $_GET['id'];
        $commande = $this->modele->getDetailCommande($idCommande);

        if (!$commande) {
            $_SESSION['error'] = "Commande introuvable.";
            header('Location: index.php?action=mesCommandes');
            exit();
        }

        $produitsCommande = $this->modele->getProduitsCommande($idCommande);

        $this->vue->afficherDetailCommande($commande, $produitsCommande);
    }


    private function annulerCommande()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Commande non spécifiée.";
            header('Location: index.php?action=mesCommandes');
            exit();
        }

        $idCommande = $_GET['id'];
        $commande = $this->modele->getDetailCommande($idCommande);

        if (!$commande) {
            $_SESSION['error'] = "Commande introuvable.";
            header('Location: index.php?action=mesCommandes');
            exit();
        }

        if ($commande['statut'] !== 'en_attente') {
            $_SESSION['error'] = "Cette commande ne peut plus être annulée.";
            header("Location: index.php?action=detailCommande&id=$idCommande");
            exit();
        }

        if ($this->modele->annulerCommande($idCommande)) {
            $_SESSION['success'] = "Commande #$idCommande annulée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'annulation de la commande.";
        }

        header('Location: index.php?action=mesCommandes');
        exit();
    }

    private function afficherMessages()
    {
        $id_gestionnaire = $_SESSION['id'];
        $messages = $this->modele->getMesMessages($id_gestionnaire);
        $this->vue->afficherMesMessages($messages, $id_gestionnaire);
    }

    private function afficherFormulaireMessage()
    {
        $barmans = $this->modele->getListeBarmans($_SESSION['id']);
        $idCible = $_GET['id_dest'] ?? null;
        $sujet = $_GET['objet'] ?? "";
        $this->vue->afficherFormulaireEnvoi($barmans, $sujet, $idCible);
    }

    private function envoyerMessage()
    {
        $id_expediteur = $_SESSION['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_destinataire = $_POST['id_destinataire'] ?? null;
            $objet = $_POST['objet'] ?? null;
            $contenu = $_POST['contenu'] ?? null;

            if ($id_expediteur && $id_destinataire && !empty($objet) && !empty($contenu)) {
                $succes = $this->modele->enregistrerMessage($id_expediteur, $id_destinataire, $objet, $contenu);
                if ($succes) {
                    header("Location: index.php?action=mesMessages&statut=envoye");
                    exit();
                }
            }
        }
        header("Location: index.php?action=ecrireMessage&error=champs_manquants");
        exit();
    }

    private function afficherFormulaireInventaire()
    {
        $id_assos = $_GET['id'] ?? $_SESSION['id_assos'];
        $produits = $this->modele->getProduitsParAssociation($id_assos);
        $this->vue->formulaireInventaire($produits, $id_assos);
    }

    private function enregistrerInventaire()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_assos = $_POST['association_id'];
            $stocksReels = $_POST['stock_reel'];

            $success = $this->modele->validerInventaire($id_assos, $stocksReels);

            if ($success) {
                $_SESSION['success'] = "Inventaire enregistré et stocks mis à jour.";
            } else {
                $_SESSION['error'] = "Erreur lors de l'enregistrement de l'inventaire.";
            }
            header("Location: index.php?action=gererAssociation&id=$id_assos");
            exit();
        }
    }

    private function afficherStatistiques()
    {
        $stats = [
            'totalVentes' => $this->modele->getTotalVentes(),
            'nbProduits' => $this->modele->getNbProduits(),
            'nbUtilisateurs' => $this->modele->getNbUtilisateurs(),
            'nbAssociations' => $this->modele->getNbAssociations(),
            'nbBarmans' => $this->modele->getNbBarmans()
        ];
        $this->vue->afficherStatistiques($stats);
    }

    private function afficherTousProduits()
    {
        $id_assos = $_SESSION['id'] ?? '';
        $produits = $this->modele->getProduitsParAssociation($id_assos);
        $this->vue->afficherProduits($produits, $id_assos);
    }

    private function afficherStockGeneral()
    {
        $stocks = $this->modele->getStock();
        $this->vue->afficherStock($stocks);
    }

    private function afficherVentes()
    {
        $ventes = $this->modele->getVentes();
        $this->vue->afficherVentes($ventes);
    }

    private function afficherErreurAction()
    {
        $message = "L'action demandée n'existe pas.";
        include 'templates/vue_erreur.php';
    }
}