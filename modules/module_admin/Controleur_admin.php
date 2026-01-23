<?php

include_once "Vue_admin.php";
include_once "Modele_admin.php";
include_once "modules/module_staff/vue_staff.php";
include_once "modules/module_staff/modele_staff.php";
include_once "modules/module_commun/vue_commun.php";
include_once "modules/module_commun/modele_commun.php";

class Controleur_admin
{
    private $vue;
    private $modele;
    private $vueStaff;
    private $modeleStaff;
    private $vueCommun;
    private $modeleCommun;

    public function __construct()
    {
        $this->vue = new Vue_admin();
        $this->modele = new Modele_admin();
        $this->vueStaff = new VueStaff();
        $this->modeleStaff = new ModeleStaff();
        $this->vueCommun = new VueCommun();
        $this->modeleCommun = new ModeleCommun();
    }

    public function gererAction($action)
    {
        $redirectActions = ['accepterAsso', 'refuserAsso', 'toggleUserStatus',
            'ajouterProduit', 'modifierProduit', 'supprimerProduit',
            'ajouterFournisseur', 'modifierFournisseur', 'supprimerFournisseur',
            'deconnexion'];

        if (in_array($action, $redirectActions)) {
            $this->traiterActionSansAffichage($action);
            return;
        }

        $nb = $this->modele->compterDemandesEnAttente();
        $this->vue->setNbDemandes($nb);
        $this->vue->afficherNav();

        if (isset($_GET['msg'])) {
            $messages = [
                'success' => 'Action réalisée avec succès !',
                'error' => 'Une erreur est survenue.',
                'refused' => 'Demande refusée.',
                'user_toggled' => 'Statut utilisateur modifié.',
                'produit_ajoute' => 'Produit ajouté avec succès.',
                'produit_modifie' => 'Produit modifié avec succès.',
                'produit_supprime' => 'Produit supprimé avec succès.',
                'fournisseur_ajoute' => 'Fournisseur ajouté avec succès.',
                'fournisseur_modifie' => 'Fournisseur modifié avec succès.',
                'fournisseur_supprime' => 'Fournisseur supprimé avec succès.'
            ];

            if (isset($messages[$_GET['msg']])) {
                $type = ($_GET['msg'] == 'error' || $_GET['msg'] == 'refused') ? 'error' : 'success';
                $this->vue->afficherConfirmation($messages[$_GET['msg']], $type);
            }
        }

        switch ($action) {
            case 'accueil':
                $this->actionAccueil();
                break;
            case 'modifierAsso':
                $this->actionModifierAsso();
                break;
            case 'validerAssos':
                $this->actionValiderAssos();
                break;
            case 'getDetailsDemande':
                $this->actionGetDetailsDemande();
                break;
            case 'gestionAssos':
                $this->actionGestionAssos();
                break;

            case 'detailsAsso':
                $this->actionDetailsAsso();
                break;

            case 'gestionUtilisateurs':
                $this->actionGestionUtilisateurs();
                break;

            case 'catalogueProduits':
                $this->actionCatalogueProduits();
                break;

            case 'modifierProduitForm':
                $this->actionModifierProduitForm();
                break;

            case 'getFormulaireProduit':
                $this->actionGetFormulaireProduit();
                break;

            case 'getFormulaireFournisseur':
                $this->actionGetFormulaireFournisseur();
                break;

            case 'gestionFournisseurs':
                $this->actionGestionFournisseurs();
                break;

            case 'modifierFournisseurForm':
                $this->actionModifierFournisseurForm();
                break;

            case 'mesMessages':
                $this->actionMesMessages();
                break;

            case 'profil':
                $this->actionProfil();
                break;

            default:
                echo "<div class='p-10 text-white font-bold'>Action Admin non reconnue ou page en construction...</div>";
                break;
        }

    }

    private function actionGetFormulaireProduit() {
        echo $this->vue->afficherFormulaireProduit();
        exit();
    }

    private function actionGetFormulaireFournisseur() {
        echo $this->vue->afficherFormulaireFournisseur();
        exit();
    }
    private function actionModifierAsso() {
        $id = $_GET['id'] ?? 0;

        if (!$id || !is_numeric($id)) {
            $this->afficherErreurModification("ID d'association invalide");
            return;
        }

        $association = $this->modele->getAssociationById($id);

        if (!$association) {
            $this->afficherErreurModification("Association #$id non trouvée");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->traiterModificationAsso($id, $association);
            return;
        }
        $this->vue->afficherFormulaireModification($id, $association);
    }

    private function afficherErreurModification($message) {
        echo '
    <div class="p-8">
        <div class="bg-white/5 border border-white/10 p-12 rounded-3xl text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-rose-600/20 flex items-center justify-center text-rose-500">
                <i class="fa-solid fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-white mb-3">Erreur</h3>
            <p class="text-slate-400 max-w-md mx-auto mb-6">' . htmlspecialchars($message) . '</p>
            <a href="index.php?module=admin&action=gestionAssos" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                <i class="fa-solid fa-arrow-left"></i> Retour aux associations
            </a>
        </div>
    </div>';
    }

    private function traiterModificationAsso($id, $anciennesDonnees) {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $solde = (float) ($_POST['solde'] ?? 0);

        $erreurs = [];

        if (empty($nom)) {
            $erreurs[] = "Le nom de l'association est obligatoire";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'email n'est pas valide";
        }

        if (!empty($solde) && !is_numeric($solde)) {
            $erreurs[] = "Le solde doit être un nombre";
        }

        if (!empty($erreurs)) {
            $_SESSION['erreurs_modification'] = $erreurs;
            echo '<script>window.location.href = "index.php?module=admin&action=modifierAsso&id=' . $id . '";</script>';
            exit();
        }

        $donnees = [
            'nom' => $nom,
            'email' => $email,
            'telephone' => $telephone,
            'adresse' => $adresse,
            'solde' => $solde
        ];

        $success = $this->modele->updateAssociation($id, $donnees);

        if ($success) {
            $_SESSION['message_success'] = 'Association modifiée avec succès !';
            echo '<script>window.location.href = "index.php?module=admin&action=detailsAsso&id=' . $id . '";</script>';
            exit();
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors de la modification de l\'association';
            echo '<script>window.location.href = "index.php?module=admin&action=modifierAsso&id=' . $id . '";</script>';
            exit();
        }}
    private function traiterActionSansAffichage($action)
    {
        switch ($action) {
            case 'refuserAsso':
                $id = $_POST['id'] ?? null;
                $raison = $_POST['raison'] ?? '';

                if ($id && $this->modele->refuserDemande($id, $raison)) {
                    header("Location: index.php?module=admin&action=validerAssos&msg=refused");
                } else {
                    header("Location: index.php?module=admin&action=validerAssos&msg=error");
                }
                exit();

            case 'accepterAsso':
                $id = $_POST['id'] ?? null;
                if ($id && $this->modele->validerCreationAsso($id)) {
                    header("Location: index.php?module=admin&action=validerAssos&msg=success");
                } else {
                    header("Location: index.php?module=admin&action=validerAssos&msg=error");
                }
                exit();
            case 'toggleUserStatus':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $this->modele->activerDesactiverUtilisateur($id);
                    header("Location: index.php?module=admin&action=gestionUtilisateurs&msg=user_toggled");
                    exit();
                }
                header("Location: index.php?module=admin&action=gestionUtilisateurs&msg=error");
                exit();

            case 'ajouterProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nom = $_POST['nom'] ?? '';
                    $type = $_POST['type'] ?? '';
                    $prix = $_POST['prix'] ?? 0;
                    $description = $_POST['description'] ?? '';

                    if (!empty($nom) && !empty($type) && $prix > 0) {
                        if ($this->modele->ajouterProduitReferent($nom, $type, $prix, $description)) {
                            header("Location: index.php?module=admin&action=catalogueProduits&msg=produit_ajoute");
                            exit();
                        }
                    }
                }
                header("Location: index.php?module=admin&action=catalogueProduits&msg=error");
                exit();

            case 'modifierProduit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $nom = $_POST['nom'] ?? '';
                    $type = $_POST['type'] ?? '';
                    $prix = $_POST['prix'] ?? 0;
                    $description = $_POST['description'] ?? '';

                    if (!empty($nom) && !empty($type) && $prix > 0) {
                        if ($this->modele->modifierProduitReferent($id, $nom, $type, $prix, $description)) {
                            header("Location: index.php?module=admin&action=catalogueProduits&msg=produit_modifie");
                            exit();
                        }
                    }
                }
                header("Location: index.php?module=admin&action=catalogueProduits&msg=error");
                exit();

            case 'supprimerProduit':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    if ($this->modele->supprimerProduitReferent($id)) {
                        header("Location: index.php?module=admin&action=catalogueProduits&msg=produit_supprime");
                    } else {
                        header("Location: index.php?module=admin&action=catalogueProduits&msg=error");
                    }
                    exit();
                }
                header("Location: index.php?module=admin&action=catalogueProduits&msg=error");
                exit();

            case 'ajouterFournisseur':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nom = $_POST['nom'] ?? '';
                    $telephone = $_POST['telephone'] ?? '';
                    $email = $_POST['email'] ?? '';

                    if (!empty($nom) && !empty($telephone) && !empty($email)) {
                        if ($this->modele->ajouterFournisseur($nom, $telephone, $email)) {
                            header("Location: index.php?module=admin&action=gestionFournisseurs&msg=fournisseur_ajoute");
                            exit();
                        }
                    }
                }
                header("Location: index.php?module=admin&action=gestionFournisseurs&msg=error");
                exit();

            case 'modifierFournisseur':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $nom = $_POST['nom'] ?? '';
                    $telephone = $_POST['telephone'] ?? '';
                    $email = $_POST['email'] ?? '';

                    if (!empty($nom) && !empty($telephone) && !empty($email)) {
                        if ($this->modele->modifierFournisseur($id, $nom, $telephone, $email)) {
                            header("Location: index.php?module=admin&action=gestionFournisseurs&msg=fournisseur_modifie");
                            exit();
                        }
                    }
                }
                header("Location: index.php?module=admin&action=gestionFournisseurs&msg=error");
                exit();

            case 'supprimerFournisseur':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    if ($this->modele->deleteFournisseur($id)) {
                        header("Location: index.php?module=admin&action=gestionFournisseurs&msg=fournisseur_supprime");
                    } else {
                        header("Location: index.php?module=admin&action=gestionFournisseurs&msg=error");
                    }
                    exit();
                }
                header("Location: index.php?module=admin&action=gestionFournisseurs&msg=error");
                exit();

            case 'deconnexion':
                session_destroy();
                header("Location: index.php");
                exit();

            default:
                header("Location: index.php?module=admin&action=accueil");
                exit();
        }
    }

    private function actionAccueil() {
        $stats = $this->modele->getStatsAdminGlobal();
        $topAssos = $this->modele->getTopAssociations(5);
        $activite = $this->modele->getActiviteRecent(7);
        $this->vue->afficherDashboard($stats, $topAssos, $activite);
    }

    private function actionValiderAssos() {
        $demandes = $this->modele->getToutesLesDemandes();
        $this->vue->afficherDemandesAssociation($demandes);
    }

    private function actionGestionAssos() {
        $assos = $this->modele->getToutesLesAssos();
        $this->vue->afficherGestionAssos($assos);
    }

    private function actionGestionUtilisateurs() {
        $users = $this->modele->getTousLesUtilisateurs();
        $this->vue->afficherGestionUtilisateurs($users);
    }

    private function actionCatalogueProduits() {
        $produits = $this->modele->getCatalogueGlobal();
        $this->vue->afficherCatalogue($produits);
    }

    private function actionGetDetailsDemande() {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $demande = $this->modele->getDemandeDetails($id);
            $this->vue->afficherDetailsDemande($demande);
        }
    }

    private function actionModifierProduitForm() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $produit = $this->modele->getProduitParId($id);
            if ($produit) {
                $produits = $this->modele->getCatalogueGlobal();
                $this->vue->afficherCatalogue($produits, true, $produit);
                return;
            }
        }
        header("Location: index.php?module=admin&action=catalogueProduits");
        exit();
    }

    private function actionGestionFournisseurs() {
        $fournisseurs = $this->modele->getTousLesFournisseurs();
        $this->vue->afficherFournisseurs($fournisseurs);
    }

    private function actionModifierFournisseurForm() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $fournisseur = $this->modele->getFournisseurParId($id);
            if ($fournisseur) {
                $fournisseurs = $this->modele->getTousLesFournisseurs();
                $this->vue->afficherFournisseurs($fournisseurs, true, $fournisseur);
                return;
            }
        }
        header("Location: index.php?module=admin&action=gestionFournisseurs");
        exit();
    }

    private function actionMesMessages() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?module=admin&action=accueil");
            exit();
        }
        $messages = $this->modeleStaff->getMesMessages($_SESSION['id']);
        $this->vueStaff->afficherMesMessages($messages, $_SESSION['id']);
    }

    private function actionProfil() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?module=admin&action=accueil");
            exit();
        }
        $infos = $this->modeleStaff->getClientParId($_SESSION['id']);
        $this->vueCommun->afficherProfil($infos);
    }

    private function actionDetailsAsso() {
        $id = $_GET['id'] ?? 0;

        if (!$id || !is_numeric($id)) {
            $this->afficherErreurDetails("ID d'association invalide");
            return;
        }

        $association = $this->modele->getAssociationById($id);

        if (!$association) {
            $this->afficherErreurDetails("Association #$id non trouvée");
            return;
        }

        $status = $association['status'];

        $donneesSupplementaires = [];

        switch($status) {
            case 'validee':
                $donneesSupplementaires = [
                    'titre_page' => 'Association Validée',
                    'couleur_theme' => 'emerald',
                    'actions_disponibles' => ['modifier', 'gerer_membres', 'voir_evenements', 'gerer_finances'],
                    'icone' => 'fa-check-circle',
                    'message_statut' => 'Cette association est active et validée.'
                ];
                break;

            case 'en_attente':
                $donneesSupplementaires = [
                    'titre_page' => 'Demande en Attente',
                    'couleur_theme' => 'amber',
                    'actions_disponibles' => ['valider', 'refuser', 'modifier'],
                    'icone' => 'fa-clock',
                    'message_statut' => 'Cette demande est en attente de validation.'
                ];
                break;

            case 'refusee':
                $donneesSupplementaires = [
                    'titre_page' => 'Association Refusée',
                    'couleur_theme' => 'rose',
                    'actions_disponibles' => ['reconsiderer', 'supprimer'],
                    'icone' => 'fa-times-circle',
                    'message_statut' => 'Cette association a été refusée.'
                ];

                $donneesSupplementaires['raison_refus'] = $association['raison_refus'] ?? 'Aucune raison spécifiée';
                $donneesSupplementaires['date_refus'] = $association['date_refus'] ?? date('Y-m-d H:i:s');
                break;

            default:
                $donneesSupplementaires = [
                    'titre_page' => 'Détails Association',
                    'couleur_theme' => 'blue',
                    'actions_disponibles' => ['modifier'],
                    'icone' => 'fa-question-circle',
                    'message_statut' => 'Statut inconnu'
                ];
                break;
        }

        $donnees = array_merge($association, $donneesSupplementaires);
        $this->vue->afficherDetailsAsso($id, $donnees);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->traiterActionDetailsAsso($id, $status);
        }
    }

    private function afficherErreurDetails($message) {
        echo '
        <div class="p-8">
            <div class="bg-white/5 border border-white/10 p-12 rounded-3xl text-center">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-rose-600/20 flex items-center justify-center text-rose-500">
                    <i class="fa-solid fa-exclamation-triangle text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3">Erreur</h3>
                <p class="text-slate-400 max-w-md mx-auto mb-6">' . htmlspecialchars($message) . '</p>
                <a href="index.php?module=admin&action=gestionAssos" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                    <i class="fa-solid fa-arrow-left"></i> Retour aux associations
                </a>
            </div>
        </div>';
    }

    private function traiterActionDetailsAsso($id, $status) {
        $action = $_POST['action'] ?? '';

        switch($action) {
            case 'valider':
                $this->validerAssociation($id);
                break;

            case 'refuser':
                $raison = $_POST['raison_refus'] ?? '';
                $this->refuserAssociation($id, $raison);
                break;

            case 'reconsiderer':
                $this->reconsidererAssociation($id);
                break;

            case 'modifier':
                header("Location: index.php?module=admin&action=modifierAsso&id=" . $id);
                exit();
                break;

            case 'supprimer':
                $this->supprimerAssociation($id);
                break;

            default:
                break;
        }
    }

    private function validerAssociation($id) {
        $success = $this->modele->validerCreationAsso($id);

        if ($success) {
            $_SESSION['message_success'] = 'Association validée avec succès !';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors de la validation de l\'association';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        }
    }

    private function refuserAssociation($id, $raison) {
        $success = $this->modele->refuserDemande($id, $raison);

        if ($success) {
            $_SESSION['message_success'] = 'Association refusée avec succès !';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors du refus de l\'association';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        }
    }

    private function reconsidererAssociation($id) {
        $success = $this->modele->mettreEnAttenteAsso($id);

        if ($success) {
            $_SESSION['message_success'] = 'Association remise en attente !';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors de la remise en attente';
            header('Location: index.php?module=admin&action=detailsAsso&id=' . $id);
            exit();
        }
    }

    private function supprimerAssociation($id) {
        $success = $this->modele->supprimerAssociation($id);

        if ($success) {
            $_SESSION['message_success'] = 'Association supprimée avec succès !';
            echo '<script>window.location.href = "index.php?module=admin&action=gestionAssos";</script>';
            exit();
        } else {
            $_SESSION['message_erreur'] = 'Erreur lors de la suppression de l\'association';
            echo '<script>window.location.href = "index.php?module=admin&action=detailsAsso&id=' . $id . '";</script>';
            exit();
        }
    }
}