<?php

include_once 'connexion/Connexion.php';
include_once 'modules/module_commun/modele_commun.php';

class ModeleStaff extends ModeleCommun
{
    public function getTousLesClients()
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT id, nom, prenom, email, role, solde 
            FROM compte 
            WHERE role = 'client' 
            ORDER BY nom, prenom
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log("Erreur dans getTousLesClients : " . $e->getMessage());
            return [];
        }
    }
    public function getClientParId($id)
    {
        $sql = "SELECT c.*, a.association_id 
            FROM compte c 
            LEFT JOIN appartient a ON c.id = a.compte_id 
            WHERE c.id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAssociationIdParBarman($idBarman)
    {
        try {
            // On sélectionne l'id de l'association dans la table 'appartient'
            // où le compte correspond à l'ID du barman connecté
            $sql = "SELECT association_id 
                FROM appartient 
                WHERE compte_id = ? 
                LIMIT 1";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$idBarman]);

            // On récupère uniquement la valeur de la colonne 'association_id'
            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log('Erreur getAssociationIdParBarman : ' . $e->getMessage());
            return null;
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

    public function getProduitsFiltres($id_gest, $id_asso = null, $tri = 'nom') {
        $trisAutorises = [
            'nom'   => 'p.nom ASC',
            'prix'  => 'p.prix ASC',
            'stock' => 'g_table.stock_asso ASC',
        ];
        $orderBy = $trisAutorises[$tri] ?? 'p.nom ASC';

        // Correction : Utilisation de 'quantiteActuelle' (vu dans ton SQL)
        $sql = "SELECT p.id, p.nom, p.type, p.prix, p.quantiteActuelle as stock_global, 
                   a.nom as nom_association, g_table.stock_asso 
            FROM produit p
            INNER JOIN gere g_table ON p.id = g_table.produit_id 
            INNER JOIN association a ON g_table.association_id = a.id
            INNER JOIN gestionne g ON a.id = g.association_id
            WHERE g.compte_id = ?";

        $params = [$id_gest];

        if ($id_asso && $id_asso !== 'all') {
            $sql .= " AND a.id = ?";
            $params[] = $id_asso;
        }

        $sql .= " ORDER BY " . $orderBy;

        $req = self::getBdd()->prepare($sql);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
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

    public function updateStock($id, $quantite)
    {
        $sql = "UPDATE produit SET quantiteActuelle = quantiteActuelle + ? WHERE id = ?";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([$quantite, $id]);
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

    public function rechercherClients($q = '')
    {
        try {
            $sql = "SELECT id, nom, prenom, email FROM compte WHERE role = 'client'";
            if ($q) {
                $sql .= " AND (nom LIKE :q OR prenom LIKE :q OR email LIKE :q)";
                $sql .= " ORDER BY nom, prenom";
                $stmt = self::getBdd()->prepare($sql);
                $stmt->execute(['q' => "%$q%"]);
            } else {
                $sql .= " ORDER BY nom, prenom";
                $stmt = self::getBdd()->prepare($sql);
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rechercherClients : " . $e->getMessage());
            return [];
        }
    }
    public function rechercherClient($search) {
        try {
            $requete = self::getBdd()->prepare('
                SELECT id, nom, prenom, solde 
                FROM compte 
                WHERE nom LIKE ? OR prenom LIKE ? OR id = ?
            ');
            $searchTerm = "%$search%";
            $requete->execute([$searchTerm, $searchTerm, $search]);
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur recherche client: " . $e->getMessage());
            return [];
        }
    }
    public function validerInventaire($idAssociation, $donneesStocks)
    {
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


}