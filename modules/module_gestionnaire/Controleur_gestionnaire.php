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
                $this->vue->afficherAccueil();
                echo $this->vue->getVueGenerique();
                break;

            case 'produits':
                $produits = $this->modele->getProduits();
                $this->vue->afficherProduits($produits);
                break;

            case 'ajouterProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $associations = $this->modele->getAssociations();
                    $this->vue->formulaireAjoutProduit($associations);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $produitId = $this->modele->ajouterProduit(
                        $_POST['nom'],
                        $_POST['type'],
                        $_POST['prix'],
                        $_POST['stock']
                    );

                    if ($produitId && isset($_POST['association_id'])) {
                        $this->modele->lierProduitAssociation(
                            $produitId,
                            $_POST['association_id']
                        );
                    }

                    header('Location: index.php?action=produits');
                    exit();
                }
                break;

            case 'ajouterAssociation':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->vue->formulaireAjoutAssociation();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['nom']) || empty($_POST['adresse']) ||
                        empty($_POST['email']) || empty($_POST['telephone'])) {
                        $_SESSION['error'] = "Tous les champs sont obligatoires";
                        header('Location: index.php?action=ajouterAssociation');
                        exit();
                    }

                    $associationId = $this->modele->ajouterAssociation(
                        $_POST['nom'],
                        $_POST['adresse'],
                        $_POST['email'],
                        $_POST['telephone'],
                        $_POST['solde'] ?? 0.00
                    );

                    if ($associationId) {
                        $_SESSION['success'] = "Association ajoutée avec succès !";
                        header('Location: index.php?action=associations');
                        exit();
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout de l'association";
                        header('Location: index.php?action=ajouterAssociation');
                        exit();
                    }
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

            case 'utilisateurs':
                $utilisateurs = $this->modele->getUtilisateurs();
                $this->vue->afficherUtilisateurs($utilisateurs);
                break;

            case 'statistiques':
                $stats = [
                    'totalVentes' => $this->modele->getTotalVentes(),
                    'nbProduits' => $this->modele->getNbProduits(),
                    'nbUtilisateurs' => $this->modele->getNbUtilisateurs(),
                    'nbAssociations' => $this->modele->getNbAssociations()
                ];
                $this->vue->afficherStatistiques($stats);
                break;

            case 'associations':
                $associations = $this->modele->getAssociations();
                $this->vue->afficherAssociations($associations);
                break;

            case 'supprimerAssociation':
                if (isset($_GET['id'])) {
                    $success = $this->modele->supprimerAssociation($_GET['id']);
                    if ($success) {
                        $_SESSION['success'] = "Association supprimée avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la suppression";
                    }
                    header('Location: index.php?action=associations');
                    exit();
                }
                break;

            case 'modifierAssociation':
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                    $association = $this->modele->getAssociationParId($_GET['id']);
                    if ($association) {
                        $this->vue->formulaireModificationAssociation($association);
                    } else {
                        $_SESSION['error'] = "Association non trouvée";
                        header('Location: index.php?action=associations');
                        exit();
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $this->modele->modifierAssociation(
                        $_POST['id'],
                        $_POST['nom'],
                        $_POST['adresse'],
                        $_POST['email'],
                        $_POST['telephone'],
                        $_POST['solde']
                    );
                    if ($success) {
                        $_SESSION['success'] = "Association modifiée avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la modification";
                    }
                    header('Location: index.php?action=associations');
                    exit();
                }
                break;

            default:
                echo '<div class="bg-white rounded-xl p-6 shadow-medium border border-gray-100">';
                echo '<h2 class="text-2xl font-bold text-primary mb-4">Page introuvable</h2>';
                echo '<p class="text-gray-600 mb-4">L\'action demandée n\'existe pas.</p>';
                echo '<a href="index.php" class="text-link hover:text-secondary font-semibold">';
                echo '<i class="fas fa-arrow-left mr-2"></i>Retour à l\'accueil';
                echo '</a>';
                echo '</div>';
                break;
        }
    }
}