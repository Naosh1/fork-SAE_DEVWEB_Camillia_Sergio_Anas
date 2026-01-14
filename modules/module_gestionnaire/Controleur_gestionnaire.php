<?php

class ControleurGestionnaire
{
    private $modele;
    private $vue;

    public function __construct()
    {
        $this->modele = new ModeleGestionnaire();
        $this->vue = new VueGestionnaire();
    }

    public function gererAction($action)
    {
        switch ($action) {
            case 'accueil':
                $idGest = $_SESSION['id'];

                $associations = $this->modele->getAssociationsParGestionnaire($idGest);
                $alertes = $this->modele->getStockCritique($idGest);
                $top = $this->modele->getTopProduits($idGest);
                $pertes = $this->modele->getTotalPertes($idGest);

                $barmans = $this->modele->getBarmans();

                $data = [
                    'associations' => $associations,
                    'alertes' => $alertes,
                    'topProduits' => $top,
                    'totalPertes' => $pertes,
                    'nbBarmans' => count($barmans),
                    'nbAssos' => count($associations)
                ];

                $this->vue->afficherTableauDeBordAccueil($_SESSION['prenom'], $data);
                break;
            case 'profil':
                $id_user = $_SESSION['id'];
                $user = $this->modele->getUtilisateur($id_user);
                $_SESSION['photo'] = $user['photo'];
                $this->vue->afficherProfil($user);
                break;
            case 'updateProfil':
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
                break;

            case 'modifierPP':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
                    $id_user = $_SESSION['id'];
                    $file = $_FILES['profile_picture'];

                    if ($file['error'] === 0) {
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
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
                break;
            case 'produits':
                $id_assos = $_SESSION['id'] ?? '';
                $produits = $this->modele->getProduitsParAssociation($id_assos);
                $this->vue->afficherProduits($produits, $id_assos);
                break;
            case 'voirStockAsso':
                $id_asso = $_GET['id_asso'] ?? null;
                $produits = $this->modele->getProduitsFiltres(null, $id_asso);
                $this->vue->afficherProduits($produits, "Stock de l'Association");
                break;
            case 'ajouterProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $id_compte = $_SESSION['id'] ?? $_SESSION['id'] ?? null;

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
                break;

            case 'modifierProduit':
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
                break;

            case 'supprimerProduit':
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
                break;
            case 'accepterAssociation':
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
                break;


            case 'voirAssociation':
            case 'gererAssociation':
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
                break;


            case 'fournisseurs':
                $fournisseurs = $this->modele->getTousLesFournisseurs();

                $this->vue->afficherFournisseurs($fournisseurs);
                break;

            case 'supprimerFournisseur':
                if (isset($_GET['id'])) {
                    if ($this->modele->supprimerFournisseur($_GET['id'])) {
                        $_SESSION['success'] = "Fournisseur supprimé.";
                    }
                    header('Location: index.php?action=fournisseurs');
                    exit();
                }
                break;
            case 'associations':
                $id_gestionnaire = $_SESSION['id'] ?? '';
                $associations = $this->modele->getAssociationsParGestionnaire($id_gestionnaire);
                $this->vue->afficherAssociationsValidees($associations);
                break;
            case 'voirProduits':
                $id_asso = $_GET['id'] ?? null;
                $id_gest = $_SESSION['id'];

                if ($id_asso) {
                    $produits = $this->modele->getProduitsParAssociation($id_asso);
                } else {
                    $produits = $this->modele->getProduitsFiltres($id_gest);
                }

                $associations = $this->modele->getAssociationsParGestionnaire($id_gest);
                $this->vue->afficherProduits($produits, $associations);
                break;
            case 'voirBarmans':
                $id_assos = $_GET['id'] ?? null;
                $barmans = $this->modele->getBarmansParAssociation($id_assos);
                $this->vue->afficherBarmans($barmans, $id_assos);

                break;
            case 'barmans':
                $idGestionnaire = $_SESSION['id'];

                $associations = $this->modele->getAssociationsParGestionnaire($idGestionnaire);

                $barmans = $this->modele->getBarmans();

                $this->vue->afficherBarmans($barmans, $associations);
                break;

            case 'ajouterBarman':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $clientId = $_POST['client_id'] ?? null;
                    $assoId = $_POST['association_id'] ?? null;

                    if ($clientId && $assoId) {
                        $res = $this->modele->ajouterClientCommeBarman($clientId, $assoId);

                        if ($res) {
                            $_SESSION['success'] = "Barman ajouté avec succès";
                            header('Location: index.php?action=barmans');
                            exit();
                        } else {
                            die("Erreur lors de l'insertion en base de données.");
                        }
                    } else {
                        header('Location: index.php?action=ajouterBarman&error=missing_data');
                        exit();
                    }
                } else {
                    $q = $_GET['q'] ?? '';
                    $clients = $this->modele->rechercherClients($q);
                    $associations = $this->modele->getAssociationsParGestionnaire($_SESSION['id']);
                    $this->vue->formulaireAjouterBarman($clients, $associations);
                }
                break;

            case 'afficherBarmans':
                $barmans = $this->modele->getBarmans();
                $associations = $this->modele->getAssociations();
                $this->vue->afficherBarmans($barmans, $associations);
                break;
            case 'toggleBarman':
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
                break;

            case 'supprimerBarman':
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
                break;

            case 'stock':
                $stocks = $this->modele->getStock();
                $this->vue->afficherStock($stocks);
                break;

            case 'ventes':
                $ventes = $this->modele->getVentes();
                $this->vue->afficherVentes($ventes);
                break;

            case 'clients':
                $utilisateurs = $this->modele->getTousLesClients();
                $this->vue->afficherUtilisateurs($utilisateurs);
                break;
            case 'voirListeClients':
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
                break;

            case 'statistiques':
                $stats = [
                    'totalVentes' => $this->modele->getTotalVentes(),
                    'nbProduits' => $this->modele->getNbProduits(),
                    'nbUtilisateurs' => $this->modele->getNbUtilisateurs(),
                    'nbAssociations' => $this->modele->getNbAssociations(),
                    'nbBarmans' => $this->modele->getNbBarmans()
                ];
                $this->vue->afficherStatistiques($stats);
                break;

            case 'ajouterStock':
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
                break;
            case 'faireInventaire':
                $id_assos = $_GET['id'] ?? $_SESSION['id_assos'];
                $produits = $this->modele->getProduitsParAssociation($id_assos);
                $this->vue->formulaireInventaire($produits, $id_assos);
                break;

            case 'enregistrerInventaire':
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
                break;

            default:
                $message = "L'action demandée n'existe pas.";
                include 'templates/vue_erreur.php';
                break;

        }
    }
}