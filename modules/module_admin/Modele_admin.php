<?php
include_once 'connexion/Connexion.php';

class Modele_admin extends Connexion
{
    public function compterDemandesEnAttente()
    {
        $sql = "SELECT COUNT(*) FROM demandes_association WHERE statut = 'en_attente'";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getToutesLesDemandes() {
        $sql = "SELECT d.*, c.nom, c.prenom 
            FROM demandes_association d 
            JOIN compte c ON d.id_gestionnaire = c.id 
            WHERE d.statut = 'en_attente'";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function refuserDemande($id, $raison = '') {
        $sql = "UPDATE demandes_association SET statut = 'refusee', raison_refus = ? WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$raison, $id]);
    }

    public function getAssociationById($id) {
        $sql = "SELECT * FROM association WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function mettreEnAttenteAsso($id) {
        $sql = "UPDATE association SET status = 'en_attente' WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function supprimerAssociation($id) {
        $sql = "DELETE FROM association WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function validerCreationAsso($id_demande)
    {
        try {
            self::getBdd()->beginTransaction();

            $sql = "SELECT * FROM demandes_association WHERE id = ?";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$id_demande]);
            $demande = $stmt->fetch();

            if (!$demande) throw new Exception("Demande introuvable");

            $sqlAsso = "INSERT INTO association (nom, status, solde) VALUES (?, 'validee', 0)";
            $stmtAsso = self::getBdd()->prepare($sqlAsso);
            $stmtAsso->execute([$demande['nom_association']]);
            $idNouvelleAsso = self::getBdd()->lastInsertId();

            $sqlGest = "INSERT INTO gestionne (compte_id, association_id) VALUES (?, ?)";
            $stmtGest = self::getBdd()->prepare($sqlGest);
            $stmtGest->execute([$demande['id_gestionnaire'], $idNouvelleAsso]);

            $sqlRole = "INSERT INTO appartient (compte_id, association_id, role) VALUES (?, ?, 'gestionnaire')";
            $stmtRole = self::getBdd()->prepare($sqlRole);
            $stmtRole->execute([$demande['id_gestionnaire'], $idNouvelleAsso]);

            $sqlUpd = "UPDATE demandes_association SET statut = 'acceptee' WHERE id = ?";
            $stmtUpd = self::getBdd()->prepare($sqlUpd);
            $stmtUpd->execute([$id_demande]);

            self::getBdd()->commit();
            return true;
        } catch (Exception $e) {
            self::getBdd()->rollBack();
            error_log("Erreur Validation Asso: " . $e->getMessage());
            return false;
        }
    }

    public function getDemandeDetails($id) {
        $sql = "SELECT d.*, c.nom, c.prenom 
            FROM demandes_association d
            LEFT JOIN compte c ON d.id_gestionnaire = c.id
            WHERE d.id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUtilisateurParId($id)
    {
        $sql = "SELECT * FROM compte WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function activerDesactiverUtilisateur($id)
    {
        $sql = "UPDATE compte SET actif = NOT actif WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function ajouterProduitReferent($nom, $type, $prix, $description = '')
    {
        $sql = "INSERT INTO produit (nom, type, prix, description) VALUES (?, ?, ?, ?)";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$nom, $type, $prix, $description]);
    }

    public function modifierProduitReferent($id, $nom, $type, $prix, $description = '')
    {
        $sql = "UPDATE produit SET nom = ?, type = ?, prix = ?, description = ? WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$nom, $type, $prix, $description, $id]);
    }

    public function getProduitParId($id)
    {
        $sql = "SELECT * FROM produit WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterFournisseur($nom, $telephone, $email)
    {
        $sql = "INSERT INTO fournisseur (nom, telephone, email) VALUES (?, ?, ?)";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$nom, $telephone, $email]);
    }

    public function modifierFournisseur($id, $nom, $telephone, $email)
    {
        $sql = "UPDATE fournisseur SET nom = ?, telephone = ?, email = ? WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$nom, $telephone, $email, $id]);
    }

    public function getFournisseurParId($id)
    {
        $sql = "SELECT * FROM fournisseur WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopAssociations($limit = 5)
    {
        $sql = "SELECT a.nom, a.solde FROM association a WHERE a.status = 'validee' ORDER BY a.solde DESC LIMIT :limit";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiviteRecent($jours = 7)
    {
        $sql = "SELECT DATE(date_soumission) as date, COUNT(*) as nb_demandes 
            FROM demandes_association 
            WHERE date_soumission >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            GROUP BY DATE(date_soumission) 
            ORDER BY date DESC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$jours]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inverserStatutUtilisateur($id) {
        $sql = "UPDATE compte SET actif = NOT actif WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function supprimerProduitReferent($id) {
        try {
            $bdd = self::getBdd();
            $bdd->beginTransaction();

            $sqlConcerne = "DELETE FROM concerne WHERE produit_id = ?";
            $prepConcerne = $bdd->prepare($sqlConcerne);
            $prepConcerne->execute([$id]);

            $sqlProduit = "DELETE FROM produit WHERE id = ?";
            $prepProduit = $bdd->prepare($sqlProduit);
            $prepProduit->execute([$id]);

            $bdd->commit();
            return true;
        } catch (Exception $e) {
            $bdd->rollBack();
            error_log("Erreur suppression produit : " . $e->getMessage());
            return false;
        }
    }

    public function deleteFournisseur($id) {
        $sql = "DELETE FROM fournisseur WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getToutesLesAssos()
    {
        try {
            $sql = "SELECT * FROM association ORDER BY nom ASC";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getToutesLesAssos: " . $e->getMessage());
            return [];
        }
    }

    public function getTousLesUtilisateurs()
    {
        $sql = "SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.actif, 
                (SELECT GROUP_CONCAT(role) FROM appartient WHERE compte_id = c.id) as roles
                FROM compte c ORDER BY c.nom ASC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCatalogueGlobal()
    {
        try {
            $sql = "SELECT * FROM produit ORDER BY nom ASC";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getCatalogueGlobal: " . $e->getMessage());
            return [];
        }
    }
    public function updateAssociation($id, $donnees) {
        try {
            $sql = "UPDATE association SET 
                nom = ?, 
                solde = ? 
                WHERE id = ?";

            $stmt = self::getBdd()->prepare($sql);

            return $stmt->execute([
                $donnees['nom'],
                $donnees['solde'],
                $id
            ]);

        } catch (PDOException $e) {
            error_log("Erreur updateAssociation: " . $e->getMessage());
            return false;
        }
    }
    public function getTousLesFournisseurs()
    {
        try {
            $sql = "SELECT * FROM fournisseur ORDER BY nom ASC";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getTousLesFournisseurs: " . $e->getMessage());
            return [];
        }
    }

    public function getStatsAdminGlobal()
    {
        $stats = [];
        try {
            $stats['total_assos'] = self::getBdd()->query("SELECT COUNT(*) FROM association WHERE status = 'validee'")->fetchColumn();
            $stats['total_users'] = self::getBdd()->query("SELECT COUNT(*) FROM compte")->fetchColumn();
            $stats['ca_global'] = self::getBdd()->query("SELECT SUM(montant_total) FROM vente")->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            error_log("Erreur getStatsAdminGlobal: " . $e->getMessage());
            $stats['total_assos'] = 0;
            $stats['total_users'] = 0;
            $stats['ca_global'] = 0;
        }
        return $stats;
    }
}