<?php
include_once 'connexion/Connexion.php';

class ModeleGestionnaire extends Connexion
{

    public function getClients(){
        try {
            $requete = self::getBdd()->query(
                "SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role,
                    (SELECT COUNT(*) FROM dispose d WHERE d.compte_id = c.id AND d.role_id = 
                        (SELECT id FROM role WHERE nom = 'client')) as est_barman
             FROM compte c
             WHERE c.role = 'client' OR c.id IN (
                 SELECT compte_id FROM dispose WHERE role_id = 
                    (SELECT id FROM role WHERE nom = 'client')
             )
             ORDER BY c.nom, c.prenom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getBarmans: " . $e->getMessage());
            return [];
        }
    }
    public function getBarmans()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role,
                    (SELECT COUNT(*) FROM dispose d WHERE d.compte_id = c.id AND d.role_id = 
                        (SELECT id FROM role WHERE nom = 'barman')) as est_barman
             FROM compte c
             WHERE c.role = 'barman' OR c.id IN (
                 SELECT compte_id FROM dispose WHERE role_id = 
                    (SELECT id FROM role WHERE nom = 'barman')
             )
             ORDER BY c.nom, c.prenom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getBarmans: " . $e->getMessage());
            return [];
        }
    }

    public function getBarmanParId($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT c.*, 
                    (SELECT COUNT(*) FROM dispose d WHERE d.compte_id = c.id AND d.role_id = 
                        (SELECT id FROM role WHERE nom = 'barman')) as est_barman
             FROM compte c 
             WHERE c.id = ?"
            );
            $requete->execute([$id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getBarmanParId: " . $e->getMessage());
            return false;
        }
    }

    public function ajouterBarman($nom, $prenom, $email, $motDePasse)
    {
        try {
            $requeteVerif = self::getBdd()->prepare("SELECT id FROM compte WHERE email = ?");
            $requeteVerif->execute([$email]);
            if ($requeteVerif->fetch()) {
                return false;
            }
            $hash = password_hash($motDePasse, PASSWORD_DEFAULT);

            $requete = self::getBdd()->prepare(
                "INSERT INTO compte (nom, prenom, email, mdp, solde, role) 
             VALUES (?, ?, ?, ?, 0, 'barman')"
            );
            $requete->execute([$nom, $prenom, $email, $hash]);

            $compteId = self::getBdd()->lastInsertId();

            $requeteRole = self::getBdd()->prepare("SELECT id FROM role WHERE nom = 'barman'");
            $requeteRole->execute();
            $role = $requeteRole->fetch(PDO::FETCH_ASSOC);

            if ($role) {
                $requeteLien = self::getBdd()->prepare(
                    "INSERT INTO dispose (role_id, compte_id) VALUES (?, ?)"
                );
                $requeteLien->execute([$role['id'], $compteId]);
            }

            return $compteId;
        } catch (PDOException $e) {
            error_log("Erreur ajouterBarman: " . $e->getMessage());
            return false;
        }
    }

    public function modifierBarman($id, $nom, $prenom, $email, $changerMotDePasse = false, $nouveauMotDePasse = null)
    {
        try {
            $requeteVerif = self::getBdd()->prepare("SELECT id FROM compte WHERE email = ? AND id != ?");
            $requeteVerif->execute([$email, $id]);
            if ($requeteVerif->fetch()) {
                return false;
            }

            if ($changerMotDePasse && $nouveauMotDePasse) {
                $hash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                $requete = self::getBdd()->prepare(
                    "UPDATE compte SET 
                 nom = ?, 
                 prenom = ?, 
                 email = ?, 
                 mdp = ?,
                 role = 'barman'
                 WHERE id = ?"
                );
                return $requete->execute([$nom, $prenom, $email, $hash, $id]);
            } else {
                $requete = self::getBdd()->prepare(
                    "UPDATE compte SET 
                 nom = ?, 
                 prenom = ?, 
                 email = ?,
                 role = 'barman'
                 WHERE id = ?"
                );
                return $requete->execute([$nom, $prenom, $email, $id]);
            }
        } catch (PDOException $e) {
            error_log("Erreur modifierBarman: " . $e->getMessage());
            return false;
        }
    }

    public function activerDesactiverBarman($id, $actif)
    {
        try {
            if (!$actif) {
                $requete = self::getBdd()->prepare(
                    "DELETE FROM dispose 
                 WHERE compte_id = ? 
                 AND role_id = (SELECT id FROM role WHERE nom = 'barman')"
                );
                return $requete->execute([$id]);
            } else {
                $requeteRole = self::getBdd()->prepare("SELECT id FROM role WHERE nom = 'barman'");
                $requeteRole->execute();
                $role = $requeteRole->fetch(PDO::FETCH_ASSOC);

                if ($role) {
                    $requeteVerif = self::getBdd()->prepare(
                        "SELECT COUNT(*) FROM dispose 
                     WHERE compte_id = ? AND role_id = ?"
                    );
                    $requeteVerif->execute([$id, $role['id']]);

                    if (!$requeteVerif->fetchColumn()) {
                        $requeteLien = self::getBdd()->prepare(
                            "INSERT INTO dispose (role_id, compte_id) VALUES (?, ?)"
                        );
                        return $requeteLien->execute([$role['id'], $id]);
                    }
                    return true;
                }
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur activerDesactiverBarman: " . $e->getMessage());
            return false;
        }
    }

    public function reinitialiserMotDePasseBarman($id)
    {
        try {
            $motDePasseTemporaire = bin2hex(random_bytes(4));
            $hash = password_hash($motDePasseTemporaire, PASSWORD_DEFAULT);

            $requete = self::getBdd()->prepare(
                "UPDATE compte SET mdp = ? WHERE id = ?"
            );

            if ($requete->execute([$hash, $id])) {
                return $motDePasseTemporaire;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur reinitialiserMotDePasseBarman: " . $e->getMessage());
            return false;
        }
    }

    public function supprimerBarman($id)
    {
        try {
            $requeteLien = self::getBdd()->prepare("DELETE FROM dispose WHERE compte_id = ?");
            $requeteLien->execute([$id]);

            $requete = self::getBdd()->prepare("DELETE FROM compte WHERE id = ?");
            return $requete->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur supprimerBarman: " . $e->getMessage());
            return false;
        }
    }
    public function getNbBarmans()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT COUNT(*) as nb 
             FROM compte 
             WHERE role = 'barman'"
            );
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbBarmans: " . $e->getMessage());
            return 0;
        }
    }
    public function estBarmanActif($id): bool
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT COUNT(*) 
             FROM dispose d 
             JOIN role r ON d.role_id = r.id 
             WHERE d.compte_id = ? AND r.nom = 'barman'"
            );
            $requete->execute([$id]);
            return $requete->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur estBarmanActif: " . $e->getMessage());
            return false;
        }
    }


    public function ajouterStock($idProduit, $quantite)
    {
        try {
            $requete = self::getBdd()->prepare(
                "UPDATE produit SET quantiteActuelle = quantiteActuelle + ? WHERE id = ?"
            );
            return $requete->execute([$quantite, $idProduit]);
        } catch (PDOException $e) {
            error_log("Erreur ajouterStock: " . $e->getMessage());
            return false;
        }
    }
    public function getAssociations()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom, adresse, email, telephone, solde FROM association ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAssociations: " . $e->getMessage());
            return [];
        }
    }
    public function getProduits()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom, type, prix, quantiteActuelle FROM produit ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getProduits: " . $e->getMessage());
            return [];
        }
    }
    public function supprimerProduit($id)
    {
        try {
            $requete = self::getBdd()->prepare("DELETE FROM produit WHERE id = ?");
            return $requete->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur supprimerProduit: " . $e->getMessage());
            return false;
        }
    }
    public function ajouterProduit($nom, $type, $prix, $stock)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO produit (nom, type, prix, quantiteActuelle)
                 VALUES (?, ?, ?, ?)"
            );
            $requete->execute([$nom, $type, $prix, $stock]);
            return self::getBdd()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur ajouterProduit: " . $e->getMessage());
            return false;
        }
    }

    public function lierProduitAssociation($idProduit, $idAssociation)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO gere (association_id, produit_id)
                 VALUES (?, ?)"
            );
            return $requete->execute([$idAssociation, $idProduit]);
        } catch (PDOException $e) {
            error_log("Erreur lierProduitAssociation: " . $e->getMessage());
            return false;
        }
    }

    public function getStock()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT id, nom as produit, quantiteActuelle as quantite 
                 FROM produit 
                 WHERE quantiteActuelle > 0 
                 ORDER BY nom"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getStock: " . $e->getMessage());
            return [];
        }
    }

    public function getVentes()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT v.id, v.date_vente, v.montant_total, v.compte_id,
                        c.nom as client_nom, c.prenom as client_prenom
                 FROM vente v
                 LEFT JOIN compte c ON v.compte_id = c.id
                 ORDER BY v.date_vente DESC, v.id DESC"
            );
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getVentes: " . $e->getMessage());
            return [];
        }
    }
    public function getTotalVentes()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT COALESCE(SUM(montant_total), 0) as total 
                 FROM vente"
            );
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Erreur getTotalVentes: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbProduits()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM produit");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbProduits: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbUtilisateurs()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM compte");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbUtilisateurs: " . $e->getMessage());
            return 0;
        }
    }

    public function getNbAssociations()
    {
        try {
            $requete = self::getBdd()->query("SELECT COUNT(*) as nb FROM association");
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
            error_log("Erreur getNbAssociations: " . $e->getMessage());
            return 0;
        }
    }

    public function ajouterAssociation($nom, $adresse, $email, $telephone, $solde = 0)
    {
        try {
            $requete = self::getBdd()->prepare(
                "INSERT INTO association (nom, adresse, email, telephone, solde)
             VALUES (?, ?, ?, ?, ?)"
            );
            $requete->execute([$nom, $adresse, $email, $telephone, $solde]);
            return self::getBdd()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur ajouterAssociation: " . $e->getMessage());
            return false;
        }
    }

    public function supprimerAssociation($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "DELETE FROM association WHERE id = ?"
            );
            return $requete->execute([$id]);
        } catch (PDOException $e) {
            error_log("L'association n'a pas pu être supprimé : " . $e->getMessage());
            return false;
        }
    }

    public function getAssociationParId($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT * FROM association WHERE id = ?"
            );
            $requete->execute([$id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAssociationParId: " . $e->getMessage());
            return false;
        }
    }

    public function modifierAssociation($id, $nom, $adresse, $email, $telephone, $solde)
    {
        try {
            $requete = self::getBdd()->prepare(
                "UPDATE association SET 
                nom = ?,
                adresse = ?,
                email = ?,
                telephone = ?,
                solde = ?
             WHERE id = ?"
            );
            return $requete->execute([$nom, $adresse, $email, $telephone, $solde, $id]);
        } catch (PDOException $e) {
            error_log("Erreur modifierAssociation: " . $e->getMessage());
            return false;
        }
    }
}