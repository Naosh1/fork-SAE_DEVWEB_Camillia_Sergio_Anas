<?php
include_once 'connexion/Connexion.php';
include_once 'modules/module_staff/modele_staff.php';


class ModeleGestionnaire extends ModeleStaff
{
    public function getTousLesFournisseurs()
    {
        $req =self::getBdd()->prepare("SELECT id, nom, telephone, email FROM fournisseur ORDER BY nom ASC");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sauvegarderDemande($idGest, $nomAsso, $pdfIdentite, $pdfPv, $pdfAgo) {
        try {
            $sql = "INSERT INTO demandes_association 
            (id_gestionnaire, nom_association, pdf_identite, pdf_pv_creation, pdf_ago, statut, date_soumission) 
            VALUES (?, ?, ?, ?, ?, 'en_attente', NOW())";
            $stmt = self::getBdd()->prepare($sql);
            return $stmt->execute([$idGest, $nomAsso, $pdfIdentite, $pdfPv, $pdfAgo]);
        } catch (PDOException $e) {
            error_log("Erreur sauvegarderDemande: " . $e->getMessage());
            return false;
        }
    }

    public function demandeExisteDeja($idGest, $nomAsso) {
        $sql = "SELECT COUNT(*) FROM demandes_association 
            WHERE id_gestionnaire = ? AND nom_association = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idGest, $nomAsso]);
        return $stmt->fetchColumn() > 0;
    }
    public function getBenefices($associationId)
    {
        try {
            $sqlRecettes = "SELECT SUM(v.montant_total) 
                            FROM vente v
                            JOIN appartient a ON v.compte_id = a.compte_id
                            WHERE a.association_id = ? AND a.role = 'barman'";
            $stmtR = self::getBdd()->prepare($sqlRecettes);
            $stmtR->execute([$associationId]);
            $recettes = $stmtR->fetchColumn() ?: 0;

            $sqlCout = "SELECT SUM(c.quantite * fp.prix_achat) 
                        FROM contient c
                        JOIN vente v ON c.vente_id = v.id
                        JOIN appartient a ON v.compte_id = a.compte_id
                        JOIN fournisseur_produit fp ON c.produit_id = fp.id_produit
                        WHERE a.association_id = ? AND a.role = 'barman'";
            $stmtC = self::getBdd()->prepare($sqlCout);
            $stmtC->execute([$associationId]);
            $coutAchat = $stmtC->fetchColumn() ?: 0;

            $sqlPertes = "SELECT SUM(co.perte * p.prix) 
                          FROM concerne co
                          JOIN produit p ON co.produit_id = p.id
                          JOIN inventaire i ON co.inventaire_id = i.id
                          WHERE i.association_id = ?";
            $stmtP = self::getBdd()->prepare($sqlPertes);
            $stmtP->execute([$associationId]);
            $pertes = $stmtP->fetchColumn() ?: 0;

            return [
                'recettes' => (float)$recettes,
                'pertes' => (float)$pertes,
                'benefice_net' => (float)($recettes - $coutAchat - $pertes)
            ];
        } catch (PDOException $e) {
            error_log("Erreur getBenefices : " . $e->getMessage());
            return ['recettes' => 0, 'pertes' => 0, 'benefice_net' => 0];
        }
    }

    public function getDemandeEnCours($idGest) {
        $sql = "SELECT * FROM demandes_association WHERE id_gestionnaire = ? AND statut = 'en_attente' LIMIT 1";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idGest]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getDemandesGestionnaire($idGest) {
        $sql = "SELECT * FROM demandes_association WHERE id_gestionnaire = ? ORDER BY date_soumission DESC";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$idGest]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function rechercherProduitGlobal($nomProduit)
    {
        $sql = "SELECT p.nom as produit_nom, f.nom as fournisseur_nom, f.id as id_f,
                   fp.prix_achat, fp.delai_livraison, p.type
            FROM produit p
            JOIN fournisseur_produit fp ON p.id = fp.id_produit
            JOIN fournisseur f ON f.id = fp.id_fournisseur
            WHERE p.nom LIKE :nom
            ORDER BY fp.prix_achat ASC";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([':nom' => '%' . $nomProduit . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsTousLesFournisseurs()
    {
        $sql = "SELECT f.nom as fournisseur_nom, p.nom as produit_nom, p.type, 
                   fp.prix_achat, fp.delai_livraison
            FROM fournisseur_produit fp
            JOIN fournisseur f ON fp.id_fournisseur = f.id
            JOIN produit p ON fp.id_produit = p.id
            ORDER BY f.nom ASC, p.nom ASC";

        return self::getBdd()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommandesFournisseursRecentes($idGest)
    {
        $sql = "SELECT cf.id, f.nom as nom_fournisseur, cf.montant_total, cf.date_commande, cf.statut,
            (SELECT COUNT(*) FROM detail_commande_fournisseur WHERE id_commande = cf.id) as nb_articles
            FROM commande_fournisseur cf
            JOIN fournisseur f ON cf.id_fournisseur = f.id
            ORDER BY cf.date_commande DESC LIMIT 10";

        return $this->getBdd()->query($sql)->fetchAll();
    }

    public function getHistoriqueAchatsComplet($idGest)
    {
        $sql = "SELECT p.nom as produit, dcf.quantite, f.nom as fournisseur, cf.date_commande as date
            FROM detail_commande_fournisseur dcf
            JOIN commande_fournisseur cf ON dcf.id_commande = cf.id
            JOIN produit p ON dcf.id_produit = p.id
            JOIN fournisseur f ON cf.id_fournisseur = f.id
            ORDER BY cf.date_commande DESC LIMIT 15";

        return $this->getBdd()->query($sql)->fetchAll();
    }

    public function getProduitsFournisseur($idFournisseur)
    {
        $sql = "SELECT p.id, p.nom, fp.prix_achat 
            FROM produit p 
            INNER JOIN fournisseur_produit fp ON p.id = fp.id_produit 
            WHERE fp.id_fournisseur = ?";

        $req = $this->getBdd()->prepare($sql);
        $req->execute([$idFournisseur]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterLienFournisseur($id_f, $id_p, $prix, $delai)
    {
        try {
            $sql = "INSERT INTO fournisseur_produit (id_fournisseur, id_produit, prix_achat, delai_livraison) 
                VALUES (:id_f, :id_p, :prix, :delai)
                ON DUPLICATE KEY UPDATE prix_achat = :prix, delai_livraison = :delai";

            $stmt = self::getBdd()->prepare($sql);
            return $stmt->execute([
                ':id_f' => $id_f,
                ':id_p' => $id_p,
                ':prix' => $prix,
                ':delai' => $delai
            ]);
        } catch (PDOException $e) {
            error_log("Erreur SQL ajouterLienFournisseur: " . $e->getMessage());
            return false;
        }
    }

    public function getFournisseurParId($id)
    {
        try {
            $sql = "SELECT * FROM fournisseur WHERE id = :id";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getFournisseurParId: " . $e->getMessage());
            return false;
        }
    }

    public function creerEnteteCommande($idFournisseur, $idAsso)
    {
        $sql = "INSERT INTO commande_fournisseur (id_fournisseur, id_association, date_commande, montant_total, statut) 
            VALUES (:id_f, :id_a, NOW(), 0, 'payee')";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([
            'id_f' => $idFournisseur,
            'id_a' => $idAsso
        ]);
        return self::getBdd()->lastInsertId();
    }

    public function majMontantTotalCommande($idCommande, $total)
    {
        $sql = "UPDATE commande_fournisseur SET montant_total = :total WHERE id = :id";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([
            'total' => $total,
            'id' => $idCommande
        ]);
    }

    public function ajouterLigneCommande($idCommande, $idProduit, $quantite, $prixUnitaire)
    {
        $sql = "INSERT INTO detail_commande_fournisseur (id_commande, id_produit, quantite, prix_unitaire) 
            VALUES (:id_c, :id_p, :qte, :prix)";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([
            'id_c' => $idCommande,
            'id_p' => $idProduit,
            'qte' => $quantite,
            'prix' => $prixUnitaire
        ]);
    }
    public function validerInventaire($idAssociation, $donneesStocks)
    {
        try {
            self::getBdd()->beginTransaction();
            $stmtInv = self::getBdd()->prepare("INSERT INTO inventaire (date_inventaire, association_id) VALUES (NOW(), ?)");
            $stmtInv->execute([$idAssociation]);
            $idInventaire = $this->getBdd()->lastInsertId();

            $stmtDetail = self::getBdd()->prepare("INSERT INTO concerne (produit_id, inventaire_id, stock_theorique, stock_reel, perte) VALUES (?, ?, ?, ?, ?)");
            $stmtUpdateGere = self::getBdd()->prepare("UPDATE gere SET stock_asso = ? WHERE association_id = ? AND produit_id = ?");
            $stmtUpdateGlobal = self::getBdd()->prepare("UPDATE produit SET quantiteActuelle = ? WHERE id = ?");

            foreach ($donneesStocks as $idProduit => $stockReel) {
                $sqlT = "SELECT stock_asso FROM gere WHERE association_id = ? AND produit_id = ?";
                $st = self::getBdd()->prepare($sqlT);
                $st->execute([$idAssociation, $idProduit]);
                $stockTheorique = $st->fetchColumn() ?: 0;
                $perte = $stockTheorique - $stockReel;
                $stmtDetail->execute([$idProduit, $idInventaire, $stockTheorique, $stockReel, $perte]);
                $stmtUpdateGere->execute([$stockReel, $idAssociation, $idProduit]);
                $stmtUpdateGlobal->execute([$stockReel, $idProduit]);
            }

            $this->getBdd()->commit();
            return true;
        } catch (Exception $e) {
            if (self::getBdd()->inTransaction()) $this->getBdd()->rollBack();
            error_log("Erreur Inventaire : " . $e->getMessage());
            return false;
        }
    }
    public function getAssociationsGerees($idGest)
    {
        $sql = "SELECT a.* FROM association a 
            JOIN gestionne g ON a.id = g.association_id 
            WHERE g.compte_id = :id_gest";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute(['id_gest' => $idGest]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function debiterSoldeAssociation($idAsso, $montant)
    {
        try {
            $idAsso = (int)$idAsso;
            $montant = (float)$montant;
            $sql = "UPDATE association SET solde = solde - :montant WHERE id = :id";
            $stmt = self::getBdd()->prepare($sql);

            $success = $stmt->execute([
                ':montant' => $montant,
                ':id' => $idAsso
            ]);

            return $success;
        } catch (PDOException $e) {
            error_log("Erreur débit solde : " . $e->getMessage());
            return false;
        }
    }

    public function getStockReserveGlobal()
    {
        $sql = "SELECT 
                p.id as id_produit, 
                p.nom, 
                p.type,
                COALESCE((
                    SELECT SUM(dc.quantite) 
                    FROM detail_commande_fournisseur dc 
                    JOIN commande_fournisseur cf ON dc.id_commande = cf.id
                    WHERE dc.id_produit = p.id 
                    AND cf.statut = 'livré'
                ), 0) - 
                COALESCE((
                    SELECT SUM(stock_asso) 
                    FROM gere 
                    WHERE produit_id = p.id
                ), 0) as quantite_achetee
            FROM produit p
            HAVING quantite_achetee > 0
            ORDER BY p.nom ASC";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTousLesProduits()
    {
        $sql = "SELECT * FROM produit";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueInventairesComplet($idGest)
    {
        try {
            $sql = "SELECT c.stock_theorique as quantite_theorique, 
                       c.stock_reel as quantite_trouvee, 
                       i.date_inventaire as date, 
                       p.nom as produit 
                FROM concerne c
                JOIN inventaire i ON c.inventaire_id = i.id 
                JOIN produit p ON c.produit_id = p.id 
                JOIN association a ON i.association_id = a.id
                WHERE a.id IN (SELECT association_id FROM gestionne WHERE compte_id = ?)
                ORDER BY i.date_inventaire DESC 
                LIMIT 30";
            $req = self::getBdd()->prepare($sql);
            $req->execute([$idGest]);
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function distribuerStock($idAsso, $idProduit, $qte)
    {
        try {
            $idAsso = (int)$idAsso;
            $idProduit = (int)$idProduit;
            $qte = (int)$qte;

            if ($qte <= 0) return false;

            $sql = "INSERT INTO gere (association_id, produit_id, stock_asso) 
                VALUES (:id_a, :id_p, :qte) 
                ON DUPLICATE KEY UPDATE stock_asso = stock_asso + :qte_update";

            $stmt = self::getBdd()->prepare($sql);
            return $stmt->execute([
                ':id_a' => $idAsso,
                ':id_p' => $idProduit,
                ':qte' => $qte,
                ':qte_update' => $qte
            ]);
        } catch (PDOException $e) {
            error_log("Erreur distribuerStock : " . $e->getMessage());
            return false;
        }
    }

    public function getCommandesParGestionnaire($idGest)
    {
        $sql = "SELECT c.*, f.nom as nom_fournisseur 
            FROM commande_fournisseur c
            JOIN fournisseur f ON c.id_fournisseur = f.id
            JOIN association a ON c.id_association = a.id
            JOIN gestionne g ON a.id = g.association_id
            WHERE g.compte_id = :id_gest
            ORDER BY c.date_commande DESC";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute(['id_gest' => $idGest]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailsCommande($idCommande)
    {
        try {
            $sql = "SELECT c.*, f.nom AS nom_fournisseur, a.nom AS nom_association 
                    FROM commande_fournisseur c
                    JOIN fournisseur f ON c.id_fournisseur = f.id
                    JOIN association a ON c.id_association = a.id
                    WHERE c.id = ?";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$idCommande]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getDetailsCommande: " . $e->getMessage());
            return false;
        }
    }
    public function toggleStatutCompte($id_compte) {
        try {
            $stmt = self::getBdd()->prepare("SELECT actif FROM compte WHERE id = ?");
            $stmt->execute([$id_compte]);
            $etatActuel = $stmt->fetchColumn();

            $nouvelEtat = ($etatActuel == 1) ? 0 : 1;

            $update = self::getBdd()->prepare("UPDATE compte SET actif = ? WHERE id = ?");
            return $update->execute([$nouvelEtat, $id_compte]);
        } catch (PDOException $e) {
            error_log("Erreur toggleStatutCompte: " . $e->getMessage());
            return false;
        }
    }
    public function retrograderBarmanEnClient($id_compte) {
        try {
            $bdd = self::getBdd();
            $bdd->beginTransaction();

            $req1 = $bdd->prepare("UPDATE compte SET role = 'client' WHERE id = ?");
            $req1->execute([$id_compte]);

            $req2 = $bdd->prepare("DELETE FROM appartient WHERE compte_id = ? AND role = 'barman'");
            $req2->execute([$id_compte]);

            $bdd->commit();
            return true;
        } catch (PDOException $e) {
            if ($bdd->inTransaction()) $bdd->rollBack();
            error_log("Erreur retrograderBarmanEnClient: " . $e->getMessage());
            return false;
        }
    }
    public function getPrixAchatFournisseur($idFournisseur, $idProduit)
    {
        $sql = "SELECT prix_achat FROM fournisseur_produit 
            WHERE id_fournisseur = ? AND id_produit = ?";
        $stmt = $this->getBdd()->prepare($sql);
        $stmt->execute([$idFournisseur, $idProduit]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProduitsCommande($idCommande)
    {
        try {
            $sql = "SELECT cp.*, p.nom as nom_produit, p.type as type_produit
                FROM commande_produits cp
                JOIN produits p ON cp.id_produit = p.id
                WHERE cp.id_commande = :id_commande
                ORDER BY p.nom";
            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([':id_commande' => $idCommande]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getProduitsCommande: " . $e->getMessage());
            return [];
        }
    }

    public function annulerCommande($idCommande)
    {
        try {
            $sql = "UPDATE commande_fournisseur SET statut = 'annulee' WHERE id = :id";
            $stmt = self::getBdd()->prepare($sql);
            return $stmt->execute([':id' => $idCommande]);
        } catch (PDOException $e) {
            error_log("Erreur annulerCommande: " . $e->getMessage());
            return false;
        }
    }
    public function rembourserAssociation($idAsso, $montant)
    {
        try {
            $sql = "UPDATE association SET solde = solde + :montant WHERE id = :id";
            $stmt = self::getBdd()->prepare($sql);
            return $stmt->execute([
                ':montant' => $montant,
                ':id' => $idAsso
            ]);
        } catch (PDOException $e) {
            error_log("Erreur remboursement: " . $e->getMessage());
            return false;
        }
    }

    public function getClientSansAssos()
    {
        try {
            $stmt = self::getBdd()->prepare("
            SELECT c.id, c.nom, c.prenom, c.email, c.solde, c.role
            FROM compte c
            LEFT JOIN appartient a ON c.id = a.compte_id
            WHERE a.compte_id IS NULL 
            AND c.role = 'client'
            ORDER BY c.nom, c.prenom
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getClientSansAssos: " . $e->getMessage());
            return [];
        }
    }
    public function ajouterBarmanALAssociation($id_compte, $id_asso) {
        try {
            $sql = "INSERT IGNORE INTO appartient (compte_id, association_id, role) 
                VALUES (?, ?, 'barman')";

            $stmt = self::getBdd()->prepare($sql);
            $res = $stmt->execute([$id_compte, $id_asso]);

            if ($res) {
                $update = self::getBdd()->prepare("UPDATE compte SET actif = 1 WHERE id = ?");
                $update->execute([$id_compte]);
            }

            return $res;
        } catch (PDOException $e) {
            error_log("Erreur promotion barman: " . $e->getMessage());
            return false;
        }
    }
    public function ajouterClient($id_client, $id_assos)
    {
        try {
            $stmt = self::getBdd()->prepare("INSERT INTO appartient (compte_id, association_id) VALUES (:id_c, :id_a)");
            return $stmt->execute(['id_c' => $id_client, 'id_a' => $id_assos]);
        } catch (PDOException $e) {
            error_log("Doublon ou erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function getBarmanParId($id) {
        $sql = "SELECT compte.*, association.nom AS nom_association 
            FROM compte 
            LEFT JOIN appartient ON compte.id = appartient.compte_id 
            LEFT JOIN association ON appartient.association_id = association.id 
            WHERE compte.id = ?";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterClientCommeBarman($clientId, $assoId) {
        try {
            $sql = "INSERT INTO appartient (compte_id, association_id, role) 
                VALUES (?, ?, 'barman') 
                ON DUPLICATE KEY UPDATE role = 'barman'";

            $stmt = self::getBdd()->prepare($sql);
            $res = $stmt->execute([$clientId, $assoId]);

            if ($res) {
                $update = self::getBdd()->prepare("UPDATE compte SET actif = 1 WHERE id = ?");
                $update->execute([$clientId]);
            }

            return $res;
        } catch (PDOException $e) {
            error_log("Erreur promotion barman : " . $e->getMessage());
            return false;
        }
    }

    public function retirerClientDeLasso($idClient, $idAsso) {
        try {
            $sql = "DELETE FROM appartient WHERE compte_id = ? AND association_id = ?";
            $stmt = $this->getBdd()->prepare($sql);
            return $stmt->execute([$idClient, $idAsso]);
        } catch (Exception $e) {
            error_log("Erreur retirerClientDeLasso: " . $e->getMessage());
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
            $stmt = self::getBdd()->prepare("SELECT COUNT(*) as nb FROM compte WHERE role = 'barman'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['nb'];
        } catch (PDOException $e) {
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


    public function accepterAssociation($assoId, $idGestionnaire)
    {
        $sql = "INSERT INTO gestionne (compte_id, association_id) VALUES (:compte_id, :association_id)";
        $stmt = self::getBdd()->prepare($sql);
        return $stmt->execute([
            ':compte_id' => $idGestionnaire,
            ':association_id' => $assoId
        ]);
    }


    public function getAssociationsParGestionnaire($id_gestionnaire)
    {
        try {
            $sql = "
            SELECT a.id, a.nom, a.adresse, a.email, a.telephone, a.solde
            FROM association a
            JOIN gestionne g ON g.association_id = a.id
            WHERE g.compte_id = ?
            LIMIT 1";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([$id_gestionnaire]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur getAssociationsParGestionnaire : ' . $e->getMessage());
            return false;
        }
    }

    public function getBarmansParAssociation($id_association) {
        $sql = "SELECT c.id, c.nom, c.prenom, c.email, c.actif, a.role, asso.nom AS nom_association
            FROM compte c
            JOIN appartient a ON c.id = a.compte_id
            JOIN association asso ON a.association_id = asso.id
            WHERE a.association_id = ? 
            AND a.role = 'barman'";

        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id_association]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                'seuil' => $seuil
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
    public function changerSoldeClient($id, $solde)
    {
        $stmt = self::getBdd()->prepare("UPDATE compte SET solde = ? WHERE id = ?");
        return $stmt->execute([$solde, $id]);
    }

    public function getListeBarmans($id_gestionnaire)
    {
        $sql = "SELECT c.id, c.nom, c.prenom, c.email, c.actif, a.nom as nom_association 
            FROM compte c 
            JOIN appartient ap ON c.id = ap.compte_id
            JOIN association a ON ap.association_id = a.id
            WHERE ap.role = 'barman' 
            ORDER BY c.nom ASC";

        $req = self::getBdd()->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}