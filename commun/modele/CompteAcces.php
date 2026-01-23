<?php

class CompteAcces {
    private $bdd;

    public function __construct() {
        $this->bdd = Connexion::getBdd();
    }

    public function modification() {
        if (!isset($_SESSION['id'])) {
            header("Location: index.php?module=client&action=erreur&erreur=personneEstConnectee_utilisateur");
            exit;
        }

        $id = $_SESSION['id'];
        $nvEmail = $_POST["nvEmailUtilisateur"];
        $nvMdp = $_POST["nvMdpUtilisateur"];

        $sql = "SELECT COUNT(*) FROM compte WHERE email = :nvEmail AND id != :id";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([':nvEmail' => $nvEmail, ":id" => $id]);

        if ($stmt->fetchColumn() > 0) {
            header("Location: index.php?module=client&action=erreur&erreur=emailDejaUtilise_utilisateur");
            exit;
        }

        if ($nvMdp !== $_POST["nvMdpUtilisateurConfirmation"]) {
            header("Location: index.php?module=client&action=erreur&erreur=mdpPasBon_utilisateur");
            exit;
        }

        $mdpHasher = password_hash($nvMdp, PASSWORD_DEFAULT);
        $sql = "UPDATE compte SET email = :nvEmail, mdp = :nvMdp WHERE id = :id";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([
            ":nvEmail" => $nvEmail,
            ":nvMdp" => $mdpHasher,
            ":id" => $id
        ]);

        header("Location: index.php?module=client&action=form_modificationReussie_utilisateur");
        exit();
    }

    public function recharger_solde($id, $montant) {
        $stmt = $this->bdd->prepare("SELECT solde FROM compte WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $soldeActuel = $stmt->fetchColumn();

        $nvSolde = $soldeActuel + $montant;

        $this->mise_a_jour_du_solde($id, $nvSolde);
        $this->ajouter_rechargement($id, $montant);
    }

    public function deconnexion() {
        session_unset();
        session_destroy();
        header("Location: index.php?module=client&action=form_deconnexionReussie_utilisateur");
        exit();
    }

    public function get_solde($id) {
        $stmt = $this->bdd->prepare("SELECT solde FROM compte WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public function mise_a_jour_du_solde($idCompte, $nouveauSolde) {
        $stmt = $this->bdd->prepare("UPDATE compte SET solde = ? WHERE id = ?");
        $stmt->execute([$nouveauSolde, $idCompte]);
    }

    public function ajouter_rechargement($idCompte, $montant) {
        $stmt = $this->bdd->prepare("INSERT INTO rechargement (valeur, date_rechargement, compte_id) VALUES (:montant, NOW(), :idCompte)");
        $stmt->execute([':montant' => $montant, ':idCompte' => $idCompte]);
    }

    public function get_historique_rechargements($idCompte) {
        $stmt = $this->bdd->prepare(
            "SELECT valeur, date_rechargement
             FROM rechargement
             WHERE compte_id = :id
             ORDER BY date_rechargement DESC"
        );
        $stmt->execute([':id' => $idCompte]);
        return $stmt->fetchAll();
    }
}