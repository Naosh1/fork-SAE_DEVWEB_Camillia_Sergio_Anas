<?php
include_once '../connexion/Connexion.php';

class ModeleGestionnaire extends Connexion
{
    public function getTousLesClients()
    {
        $stmt = self::getBdd()->prepare("
        SELECT id, nom, prenom, email
        FROM compte
        WHERE role = 'client'
        ORDER BY nom, prenom
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUtilisateur($id) {
        $sql = "SELECT *, mdp FROM compte WHERE id = ?";
        $query = self::getBdd()->prepare($sql);
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function updateUserPhoto($id, $nom_image) {
        $sql = "UPDATE compte SET photo = ? WHERE id = ?";
        $query = self::getBdd()->prepare($sql);
        return $query->execute([$nom_image, $id]);
    }
    public function updateUserInfos($id, $nom, $prenom, $email, $tel, $password = null) {
        if ($password) {
            $sql = "UPDATE compte SET nom = ?, prenom = ?, email = ?, tel = ?, mdp = ? WHERE id = ?";
            $query = self::getBdd()->prepare($sql);
            return $query->execute([$nom, $prenom, $email, $tel, $password, $id]);
        } else {
            $sql = "UPDATE compte SET nom = ?, prenom = ?, email = ?, tel = ? WHERE id = ?";
            $query = self::getBdd()->prepare($sql);
            return $query->execute([$nom, $prenom, $email, $tel, $id]);
        }
    }

    public function rechercherClients($q = '')
    {
        try {
            if ($q) {
                $stmt = self::getBdd()->prepare("
                SELECT id, nom, prenom, email
                FROM compte
                WHERE role = 'client'
                AND (
                    nom LIKE :q
                    OR prenom LIKE :q
                    OR email LIKE :q
                )
                ORDER BY nom, prenom
            ");
                $stmt->execute(['q' => "%$q%"]);
            } else {
                $stmt = self::getBdd()->query("
                SELECT id, nom, prenom, email
                FROM compte
                WHERE role = 'client'
                ORDER BY nom, prenom
            ");
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur rechercherClients : " . $e->getMessage());
            return [];
        }
    }
    public function getTousLesFournisseurs() {
        $req = $this->getBdd()->prepare("SELECT id, nom, telephone, email FROM fournisseur ORDER BY nom ASC");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerFournisseur($id) {
        $req = $this->getBdd()->prepare("DELETE FROM fournisseurs WHERE id = ?");
        return $req->execute([$id]);
    }
    public function getTotalPertes($idGestionnaire) {
        $sql = "SELECT SUM(perte * prix) as valeur_perte 
            FROM concerne 
            JOIN produit ON concerne.produit_id = produit.id
            JOIN gere ON produit.id = gere.produit_id
            JOIN gestionne ON gere.association_id = gestionne.association_id
            WHERE gestionne.compte_id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idGestionnaire]);
        return $stmt->fetchColumn() ?: 0;
    }


    public function getTopProduits($idGest) {
        $sql = "SELECT p.nom, SUM(c.quantite) as total_vendu, p.prix
            FROM contient c
            JOIN produit p ON c.produit_id = p.id
            JOIN gere g ON p.id = g.produit_id
            JOIN gestionne gn ON g.association_id = gn.association_id
            WHERE gn.compte_id = :idGest
            GROUP BY p.id
            ORDER BY total_vendu DESC
            LIMIT 5";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute(['idGest' => $idGest]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getBarmansParAssociation($associationId)
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role
            FROM compte c
            JOIN appartient a ON c.id = a.compte_id
            WHERE a.association_id = :id
            AND c.role = 'barman'
            ORDER BY c.nom, c.prenom
        ");
            $stmt->execute(['id' => $associationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getBarmansParAssociation: " . $e->getMessage());
            return [];
        }
    }


    public function getProduitsParAssociation($associationId)
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT p.*
            FROM produit p
            JOIN gere g ON p.id = g.produit_id
            WHERE g.association_id = :id
        ");
            $stmt->execute(['id' => $associationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getProduitsParAssociation: " . $e->getMessage());
            return [];
        }
    }


    public function getClientsParAssociation($associationId)
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role
            FROM compte c
            JOIN appartient a ON c.id = a.compte_id
            WHERE a.association_id = :id
            AND c.role = 'client'
        ");
            $stmt->execute(['id' => $associationId]);
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clients as &$client) {
                if (!isset($client['solde'])) $client['solde'] = 0;
            }

            return $clients;
        } catch (PDOException $e) {
            error_log("Erreur getClientsParAssociation: " . $e->getMessage());
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

    public function ajouterClientCommeBarman($clientId, $assoId) {
        try {
            $bdd = self::getBdd();

            $sql1 = "UPDATE compte SET role = 'barman' WHERE id = :cid";
            $stmt1 = $bdd->prepare($sql1);
            $stmt1->execute(['cid' => $clientId]);
            $sqlDelete = "DELETE FROM appartient WHERE compte_id = :cid AND association_id = :aid";
            $stmtDel = $bdd->prepare($sqlDelete);
            $stmtDel->execute(['cid' => $clientId, 'aid' => $assoId]);

            $sqlInsert = "INSERT INTO appartient (compte_id, association_id, role) 
                      VALUES (:cid, :aid, 'barman')";
            $stmtIns = $bdd->prepare($sqlInsert);

            $result = $stmtIns->execute([
                'cid' => $clientId,
                'aid' => $assoId
            ]);

            return $result;

        } catch (PDOException $e) {
            echo "Détails de l'erreur SQL : " . $e->getMessage();
            die();
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


    public function accepterAssociation($assoId, $idGestionnaire)
    {
        $sql = "INSERT INTO gestionne (compte_id, association_id) VALUES (:compte_id, :association_id)";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([
            ':compte_id' => $idGestionnaire,
            ':association_id' => $assoId
        ]);
    }public function updateStock($id, $quantite) {
    $sql = "UPDATE produit SET quantiteActuelle = quantiteActuelle + ? WHERE id = ?";
    $stmt = self::getBdd()->prepare($sql);
    return $stmt->execute([$quantite, $id]);
}
    public function getProduitsFiltres($id_compte = null, $id_asso = null, $tri = 'stock') {
        $sql = "SELECT p.*, a.nom as nom_association 
        FROM produit p 
        JOIN gere g_link ON p.id = g_link.produit_id
        JOIN association a ON a.id = g_link.association_id ";
        $params = [];
        $where = [];
        if ($id_asso) { $where[] = "a.id = ?"; $params[] = $id_asso; }
        elseif ($id_compte) {
            $sql .= " JOIN gestionne g ON a.id = g.association_id ";
            $where[] = "g.compte_id = ?"; $params[] = $id_compte;
        }

        if ($tri === 'urgences_seules') {
            $where[] = "p.quantiteActuelle < 10";

        } elseif ($tri === 'type_alim') {
            $where[] = "p.type = 'nourriture'";
        } elseif ($tri === 'type_boisson') {
            $where[] = "p.type = 'boisson'";
        }

        if (!empty($where)) { $sql .= " WHERE " . implode(" AND ", $where); }

        switch ($tri) {
            case 'nom': $sql .= " ORDER BY p.nom ASC"; break;
            case 'prix': $sql .= " ORDER BY p.prix ASC"; break;
            default: $sql .= " ORDER BY p.quantiteActuelle ASC"; break;
        }

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAssociationsParGestionnaire($id_gestionnaire)
    {
        try {
            $sql = "
            SELECT a.id, a.nom, a.adresse, a.email, a.telephone, a.solde
            FROM association a
            JOIN gestionne g ON g.association_id = a.id
            WHERE g.compte_id = ?
        ";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$id_gestionnaire]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur getAssociationsParGestionnaire : ' . $e->getMessage());
            return [];
        }
    }


    public function getDetailsAssos($idAssociation)
    {
        $assos = $this->getAssociationParId($idAssociation);
        if (!$assos) return null;

        $assos['produits'] = $this->getProduitsParAssociation($idAssociation);
        $assos['clients'] = $this->getClientsParAssociation($idAssociation);
        $assos['barmans'] = $this->getBarmansParAssociation($idAssociation);

        $assos['nb_clients'] = count($assos['clients']);
        $assos['nb_produits'] = count($assos['produits']);
        $assos['nb_barmans'] = count($assos['barmans']);

        return $assos;
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
    public function getStockCritique($idGestionnaire, $seuil = 15)
    {
        try {
            $sql = "SELECT p.id, p.nom, p.quantiteActuelle, a.nom as nom_association
                FROM produit p
                JOIN gere g ON p.id = g.produit_id
                JOIN association a ON g.association_id = a.id
                JOIN gestionne gest ON a.id = gest.association_id
                WHERE gest.compte_id = :id_gest 
                AND p.quantiteActuelle <= :seuil
                ORDER BY p.quantiteActuelle ASC";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([
                'id_gest' => $idGestionnaire,
                'seuil'   => $seuil
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getStockCritique : " . $e->getMessage());
            return [];
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

    public function modifierProduit($id, $nom, $type, $prix, $stock)
    {
        try {
            $requete = self::getBdd()->prepare(
                "UPDATE produit SET nom = ?, type = ?, prix = ?, quantiteActuelle = ? WHERE id = ?"
            );
            return $requete->execute([$nom, $type, $prix, $stock, $id]);
        } catch (PDOException $e) {
            error_log("Erreur modifierProduit: " . $e->getMessage());
            return false;
        }
    }

    public function getProduitParId($id)
    {
        try {
            $requete = self::getBdd()->prepare(
                "SELECT id, nom, type, prix, quantiteActuelle 
             FROM produit 
             WHERE id = ?"
            );
            $requete->execute([$id]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getProduitParId: " . $e->getMessage());
            return false;
        }
    }



    public function getBarmans()
    {
        try {
            $requete = self::getBdd()->query(
                "SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role, 
                        assos.nom as nom_association
                 FROM compte c
                 LEFT JOIN appartient a ON c.id = a.compte_id
                 LEFT JOIN association assos ON a.association_id = assos.id
                 WHERE c.role = 'barman' 
                 OR c.id IN (
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

    public function getAssociations() {
        try {
            $req = self::getBdd()->prepare("SELECT id, nom FROM association ORDER BY nom");
            $req->execute();
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAssociations : " . $e->getMessage());
            return [];
        }
    }
    public function validerInventaire($idAssociation, $donneesStocks) {
        try {
            $this->getBdd()->beginTransaction();

            $stmtInv = $this->getBdd()->prepare("INSERT INTO inventaire (date_inventaire, association_id) VALUES (NOW(), ?)");
            $stmtInv->execute([$idAssociation]);
            $idInventaire = $this->getBdd()->lastInsertId();

            $stmtDetail = $this->getBdd()->prepare("
            INSERT INTO concerne (produit_id, inventaire_id, stock_theorique, stock_reel, perte) 
            VALUES (?, ?, ?, ?, ?)
        ");
            $stmtUpdateStock = $this->getBdd()->prepare("UPDATE produit SET quantiteActuelle = ? WHERE id = ?");

            foreach ($donneesStocks as $idProduit => $stockReel) {
                $produit = $this->getProduitParId($idProduit);
                $stockTheorique = $produit['quantiteActuelle'];
                $perte = $stockTheorique - $stockReel;
                $stmtDetail->execute([$idProduit, $idInventaire, $stockTheorique, $stockReel, $perte]);
                $stmtUpdateStock->execute([$stockReel, $idProduit]);
            }

            $this->getBdd()->commit();
            return true;
        } catch (Exception $e) {
            $this->getBdd()->rollBack();
            error_log("Erreur Inventaire : " . $e->getMessage());
            return false;
        }
    }

}