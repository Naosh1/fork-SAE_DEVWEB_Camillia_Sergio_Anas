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
                        $this->modele->lierProduitAssociation($produitId, $_POST['association_id']);
                    }

                    header('Location: index.php?action=produits');
                    exit();
                }
                break;

            case 'modifierProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                    $produit = $this->modele->getProduit($_GET['id']);
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

            // GESTION DES ASSOCIATIONS
            case 'associations':
                $associations = $this->modele->getAssociations();
                $this->vue->afficherAssociations($associations);
                break;

            case 'ajouterAssociation':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->vue->formulaireAjoutAssociation();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['nom']) || empty($_POST['adresse']) || empty($_POST['email']) || empty($_POST['telephone'])) {
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
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout de l'association";
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

            case 'barmans':
                $barmans = $this->modele->getBarmans();
                $this->vue->afficherBarmans($barmans);
                break;

            case 'ajouterBarman':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $this->vue->formulaireAjoutBarman();
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

            case 'modifierBarman':
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                    $barman = $this->modele->getBarmanParId($_GET['id']);
                    if ($barman) {
                        $this->vue->formulaireModificationBarman($barman);
                    } else {
                        $_SESSION['error'] = "Barman non trouvé";
                        header('Location: index.php?action=barmans');
                        exit();
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email'])) {
                        $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis";
                        header('Location: index.php?action=modifierBarman&id=' . $_POST['id']);
                        exit();
                    }

                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['error'] = "Format d'email invalide";
                        header('Location: index.php?action=modifierBarman&id=' . $_POST['id']);
                        exit();
                    }

                    $changerMdp = isset($_POST['changer_mdp']) && $_POST['changer_mdp'] == 'on';

                    if ($changerMdp) {
                        if (empty($_POST['nouveau_mot_de_passe']) || empty($_POST['confirmer_mot_de_passe'])) {
                            $_SESSION['error'] = "Les champs de mot de passe doivent être remplis";
                            header('Location: index.php?action=modifierBarman&id=' . $_POST['id']);
                            exit();
                        }

                        if ($_POST['nouveau_mot_de_passe'] !== $_POST['confirmer_mot_de_passe']) {
                            $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                            header('Location: index.php?action=modifierBarman&id=' . $_POST['id']);
                            exit();
                        }
                    }

                    $success = $this->modele->modifierBarman(
                        $_POST['id'],
                        $_POST['nom'],
                        $_POST['prenom'],
                        $_POST['email'],
                        $changerMdp,
                        $changerMdp ? $_POST['nouveau_mot_de_passe'] : null
                    );

                    if ($success) {
                        $_SESSION['success'] = "Barman modifié avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la modification";
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
                    $produit = $this->modele->getProduit($_GET['id']);
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
                echo '<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; margin: 20px;">';
                echo '<h2 style="color: #dc3545;">Page introuvable</h2>';
                echo '<p>L\'action demandée n\'existe pas.</p>';
                echo '<a href="index.php" style="color: #007bff; text-decoration: none;">← Retour à l\'accueil</a>';
                echo '</div>';
                break;
        }
    }
}