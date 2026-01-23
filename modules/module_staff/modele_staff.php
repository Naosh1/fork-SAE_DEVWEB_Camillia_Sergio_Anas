<?php

include_once 'connexion/Connexion.php';
include_once 'modules/module_commun/modele_commun.php';

class ModeleStaff extends ModeleCommun
{
    public function getTousLesClients()
    {
        try {
            $stmt = self::getBdd()->prepare("
                SELECT DISTINCT c.id, c.nom, c.prenom, c.email, c.solde 
                FROM compte c
                JOIN appartient ap ON c.id = ap.compte_id
                WHERE ap.role = 'client'
                ORDER BY c.nom, c.prenom
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
        $sql = "SELECT c.*, ap.association_id, ap.role
            FROM compte c 
            LEFT JOIN appartient ap ON c.id = ap.compte_id 
            WHERE c.id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAssociationIdParBarman($idBarman)
    {
        try {
            $sql = "SELECT association_id 
                FROM appartient 
                WHERE compte_id = ? 
                AND role IN ('barman', 'gestionnaire')
                LIMIT 1";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$idBarman]);
            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log('Erreur getAssociationIdParBarman : ' . $e->getMessage());
            return null;
        }
    }

    public function getProduitsParAssociation($id_assos)
    {
        $sql = "SELECT p.*, g.stock_asso 
            FROM produit p
            INNER JOIN gere g ON p.id = g.produit_id
            WHERE g.association_id = ?";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id_assos]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNbProduits($associationId = null)
    {
        try {
            if ($associationId) {
                $req = self::getBdd()->prepare("SELECT COUNT(*) FROM gere WHERE association_id = ?");
                $req->execute([$associationId]);
            } else {
                $req = self::getBdd()->prepare("SELECT COUNT(*) FROM produit");
                $req->execute();
            }
            return $req->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur getNbProduits: " . $e->getMessage());
            return 0;
        }
    }

    public function getStockCritiqueParAsso($idAsso)
    {
        $sql = "SELECT p.nom, g.stock_asso as quantiteActuelle 
            FROM produit p 
            INNER JOIN gere g ON p.id = g.produit_id 
            WHERE g.association_id = ? AND g.stock_asso < 10 
            ORDER BY g.stock_asso ASC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idAsso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopProduitsParAsso($idAsso)
    {
        try {
            $sql = "SELECT 
                    p.nom, 
                    IFNULL(p.type, 'Autre') as type, 
                    SUM(c.quantite) as total 
                FROM produit p
                JOIN ligne_vente c ON p.id = c.produit_id
                JOIN vente v ON c.vente_id = v.id
                JOIN appartient a ON v.compte_id = a.compte_id
                WHERE a.association_id = ?
                GROUP BY p.id, p.nom, p.type 
                ORDER BY total DESC";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$idAsso]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getTopProduitsParAsso : " . $e->getMessage());
            return [];
        }
    }

    public function getVentesParHeure($idAsso)
    {
        $sql = "SELECT HOUR(date_vente) as heure, SUM(montant_total) as total 
            FROM vente 
            WHERE association_id = ? 
            GROUP BY HOUR(date_vente) 
            ORDER BY heure ASC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idAsso]);
        $ventes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $complet = array_fill(0, 24, 0);
        foreach ($ventes as $heure => $total) {
            $complet[(int)$heure] = (float)$total;
        }
        return $complet;
    }

    public function getTopClients($idAsso)
    {
        $sql = "SELECT c.nom, c.prenom, 
            SUM(v.montant_total) as depense_totale, 
            COUNT(v.id) as nb_commandes
            FROM compte c
            JOIN vente v ON c.id = v.compte_id
            WHERE v.association_id = ?
            GROUP BY c.id
            ORDER BY depense_totale DESC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idAsso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRentabiliteProduits($idAsso)
    {
        $sql = "SELECT p.nom, 
            SUM(c.quantite) as total_vendu,
            SUM(c.quantite * (p.prix - fp.prix_achat)) as benefice_reel
            FROM ligne_vente c
            JOIN produit p ON c.produit_id = p.id
            JOIN vente v ON c.vente_id = v.id
            JOIN fournisseur_produit fp ON p.id = fp.id_produit
            WHERE v.association_id = ?
            GROUP BY p.id
            ORDER BY benefice_reel DESC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idAsso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsEvolutionSeptJoursParAsso($idAsso)
    {
        $dates = [];
        $recettes = [];
        $pertes = [];
        $benefices = [];

        for ($i = 6; $i >= 0; $i--) {
            $dateSQL = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('d/m', strtotime($dateSQL));

            $sqlR = "SELECT SUM(montant_total) FROM vente WHERE association_id = ? AND DATE(date_vente) = ?";
            $stmtR = self::getBdd()->prepare($sqlR);
            $stmtR->execute([$idAsso, $dateSQL]);
            $r = (float)($stmtR->fetchColumn() ?: 0);
            $recettes[] = $r;

            $sqlP = "SELECT SUM(c.perte * p.prix) 
         FROM ligne_inventaire c 
         JOIN inventaire i ON c.inventaire_id = i.id 
         JOIN produit p ON c.produit_id = p.id
         WHERE i.association_id = ? AND DATE(i.date_inventaire) = ?";
            $stmtP = self::getBdd()->prepare($sqlP);
            $stmtP->execute([$idAsso, $dateSQL]);
            $p = (float)($stmtP->fetchColumn() ?: 0);
            $pertes[] = $p;

            $benefices[] = $r - $p;
        }

        return [
            'dates' => $dates,
            'recettes' => $recettes,
            'pertes' => $pertes,
            'benefices' => $benefices
        ];
    }

    public function getTotalPertesParAsso($idAsso)
    {
        try {
            $sql = "SELECT SUM(c.perte * p.prix) 
        FROM ligne_inventaire c
        JOIN inventaire i ON c.inventaire_id = i.id 
        JOIN produit p ON c.produit_id = p.id
        WHERE i.association_id = ?";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$idAsso]);
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            error_log("Erreur getTotalPertesParAsso: " . $e->getMessage());
            return 0;
        }
    }

    public function rechercherProduitsParNom($recherche, $idAsso)
    {
        $sql = "SELECT p.id, p.nom, p.prix 
            FROM produit p
            INNER JOIN gere g ON p.id = g.produit_id
            WHERE g.association_id = ? 
            AND p.nom LIKE ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idAsso, "%$recherche%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsFiltres($id_gest, $id_asso = null, $tri = 'nom')
    {
        $trisAutorises = [
            'nom' => 'p.nom ASC',
            'prix' => 'p.prix ASC',
            'stock' => 'g_table.stock_asso ASC',
        ];
        $orderBy = $trisAutorises[$tri] ?? 'p.nom ASC';

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

    public function enregistrerMessage($id_expediteur, $id_destinataire, $objet, $contenu)
    {
        try {
            $sql = "INSERT INTO messages (id_expediteur, id_destinataire, objet, contenu, date_envoi) 
                VALUES (?, ?, ?, ?, NOW())";

            $req = self::getBdd()->prepare($sql);

            return $req->execute([
                $id_expediteur,
                $id_destinataire,
                $objet,
                $contenu
            ]);
        } catch (PDOException $e) {
            error_log("Erreur enregistrerMessage : " . $e->getMessage());
            return false;
        }
    }

    public function getNbMessagesNonLus($id_user)
    {
        $sql = "SELECT COUNT(*) as total FROM messages WHERE id_destinataire = ? AND lu = 0";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetch()['total'];
    }

    public function getMesMessages($id_user)
    {
        $sql = "SELECT m.*, 
        exp.nom as nom, exp.prenom as prenom,
        dest.nom as dest_nom, dest.prenom as dest_prenom
        FROM messages m
        JOIN compte exp ON m.id_expediteur = exp.id
        JOIN compte dest ON m.id_destinataire = dest.id
        WHERE m.id_destinataire = :id_dest OR m.id_expediteur = :id_exp
        ORDER BY m.date_envoi DESC";

        $stmt = self::getBdd()->prepare($sql);

        $stmt->execute([
            'id_dest' => $id_user,
            'id_exp' => $id_user
        ]);

        return $stmt->fetchAll();
    }

    public function getClientsParAssociation($associationId)
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT c.id, c.nom, c.prenom, c.email, c.solde
            FROM compte c
            JOIN appartient a ON c.id = a.compte_id
            WHERE a.association_id = :id
            AND a.role = 'client'
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
            $sql = "SELECT c.id, c.nom, c.prenom, c.email 
                    FROM compte c 
                    INNER JOIN appartient ap ON c.id = ap.compte_id 
                    WHERE ap.role = 'client'";
            if ($q) {
                $sql .= " AND (c.nom LIKE :q OR c.prenom LIKE :q OR c.email LIKE :q)";
                $sql .= " ORDER BY c.nom, c.prenom";
                $stmt = self::getBdd()->prepare($sql);
                $stmt->execute(['q' => "%$q%"]);
            } else {
                $sql .= " ORDER BY c.nom, c.prenom";
                $stmt = self::getBdd()->prepare($sql);
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur rechercherClients : " . $e->getMessage());
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
            INSERT INTO ligne_inventaire (produit_id, inventaire_id, stock_theorique, stock_reel, perte)
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

    public function getToutStaff()
    {
        $sql = "SELECT c.id, c.nom, c.prenom, c.email, ap.role
            FROM compte c 
            INNER JOIN appartient ap ON c.id = ap.compte_id
            WHERE ap.role IN ('barman', 'gestionnaire')
            ORDER BY ap.role DESC, c.nom ASC";

        $req = self::getBdd()->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}