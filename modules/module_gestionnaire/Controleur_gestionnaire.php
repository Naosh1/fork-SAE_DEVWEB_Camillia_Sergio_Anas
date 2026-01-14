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
                $prenom = $_SESSION['prenom'] ?? 'Gestionnaire';
                $id_gestionnaire = $_SESSION['id'] ?? null;

                $associations = [];
                if ($id_gestionnaire !== null) {
                    $associations = $this->modele->getAssociationsParGestionnaire($id_gestionnaire);
                }
                $this->vue->afficherTableauDeBordAccueil($prenom, $associations);
                break;

            case 'produits':
                $id_assos = $_SESSION['id_assos'] ?? '';
                $produits = $this->modele->getProduitsParAssociation($id_assos);
                $this->vue->afficherProduits($produits);
                break;

            case 'ajouterProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $id_gestionnaire = $_SESSION['id_gestionnaire'] ?? '';
                    $associations = $this->modele->getAssociationsParGestionnaire($id_gestionnaire);
                    $this->vue->formulaireAjoutProduit($associations);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $produitId = $this->modele->ajouterProduit(
                        $_POST['nom'],
                        $_POST['type'],
                        $_POST['prix'],
                        $_POST['stock']
                    );

                    if ($produitId && isset($_POST['association_id'])) {
                        $this->modele->lierProduitAssociation($produitId, $_POST['association_id']);
                    }

                    header('Location: index.php?action=produits');
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
            case 'voirAssociation':
                $id_assos = $_GET['id'] ?? '';
                if ($id_assos) {
                    $assos = $this->modele->getDetailsAssos($id_assos);
                    if ($assos) {
                        $this->vue->afficherDetailsAssos($assos);
                    } else {
                        $_SESSION['error'] = "Association introuvable.";
                        header('Location: index.php?action=associations');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "ID d'association manquant.";
                    header('Location: index.php?action=associations');
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

            case 'gererAssociation':
                if (isset($_GET['id'])) {

                    $assoId = $_GET['id'];
                    $association = $this->modele->getAssociationParId($assoId);
                    if ($association) {
                        $barmans = $this->modele->getBarmansParAssociation($assoId);
                        $produits = $this->modele->getProduitsParAssociation($assoId);
                        $clients = $this->modele->getClientsParAssociation($assoId);
                        $this->vue->gererAssociation($association, $barmans, $produits, $clients);
                    } else {
                        $_SESSION['error'] = "Association non trouvée";
                        header('Location: index.php?action=associations');
                        exit();
                    }
                }
                break;

            case 'associations':
                $id_gestionnaire = $_SESSION['id'] ?? '';
                $associations = $this->modele->getAssociationsParGestionnaire($id_gestionnaire);
                $this->vue->afficherAssociationsValidees($associations);
                break;

            case 'barmans':
                $barmans = $this->modele->getBarmans();
                $this->vue->afficherBarmans($barmans);
                break;

            case 'ajouterBarman':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $barmans = $this->modele->getBarmans();
                    $this->vue->afficherBarmans($barmans);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                        $_SESSION['error'] = "Tous les champs sont obligatoires";
                        header('Location: index.php?action=ajouterBarman');
                        exit();
                    }

                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['error'] = "Format d'email invalide";
                        header('Location: index.php?action=ajouterBarman');
                        exit();
                    }

                    $barmanId = $this->modele->ajouterBarman(
                        $_POST['nom'],
                        $_POST['prenom'],
                        $_POST['email'],
                        $_POST['mot_de_passe']
                    );

                    if ($barmanId) {
                        $_SESSION['success'] = "Barman ajouté avec succès !";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout du barman (email peut-être déjà utilisé)";
                    }
                    header('Location: index.php?action=barmans');
                    exit();
                }
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

            case 'reinitialiserMdpBarman':
                if (isset($_GET['id'])) {
                    $nouveauMdp = $this->modele->reinitialiserMotDePasseBarman($_GET['id']);
                    if ($nouveauMdp) {
                        $_SESSION['success'] = "Mot de passe réinitialisé. Nouveau mot de passe temporaire : <strong>" . $nouveauMdp . "</strong>";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la réinitialisation du mot de passe";
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
                $utilisateurs = $this->modele->getClients();
                $this->vue->afficherUtilisateurs($utilisateurs);
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
                if (isset($_GET['id'])) {
                    $produit = $this->modele->getProduitParId($_GET['id']);
                    if ($produit) {
                        $this->vue->formulaireAjoutStock($produit);
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $this->modele->ajouterStock($_POST['id'], $_POST['quantite']);
                    if ($success) {
                        $_SESSION['success'] = "Stock mis à jour avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la mise à jour du stock";
                    }
                    header('Location: index.php?action=stock');
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