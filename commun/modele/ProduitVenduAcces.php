<?php

class ProduitVenduAcces {
    private $bdd;

    public function __construct() {
        $this->bdd = Connexion::getBdd();
    }

    public function valider_commande()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id']) || empty($_SESSION['panier'])) {
            return;
        }

        $montantTotal = 0;

        foreach ($_SESSION['panier'] as $idProduit => $quantite) {
            $stmt = $this->bdd->prepare(
                "SELECT prix FROM produit WHERE id = :id"
            );
            $stmt->execute([':id' => $idProduit]);
            $produit = $stmt->fetch();

            if ($produit) {
                $montantTotal += $produit['prix'] * $quantite;
            }
        }

        $stmt = $this->bdd->prepare(
            "INSERT INTO vente (compte_id, date_vente, montant_total, statut)
             VALUES (:compte_id, NOW(), :montant_total, 'en_attente')"
        );

        $stmt->execute([
            ':compte_id'     => $_SESSION['id'],
            ':montant_total' => $montantTotal
        ]);

        $venteId = $this->bdd->lastInsertId();

        foreach ($_SESSION['panier'] as $idProduit => $quantite) {

            $stmt = $this->bdd->prepare(
                "SELECT prix FROM produit WHERE id = :id"
            );
            $stmt->execute([':id' => $idProduit]);
            $produit = $stmt->fetch();

            if ($produit) {
                $stmt = $this->bdd->prepare(
                    "INSERT INTO ligne_vente
                     (produit_id, vente_id, quantite, prix_unitaire, statut)
                     VALUES (:produit_id, :vente_id, :quantite, :prix, 'en_attente')"
                );

                $stmt->execute([
                    ':produit_id' => $idProduit,
                    ':vente_id'   => $venteId,
                    ':quantite'   => $quantite,
                    ':prix'       => $produit['prix']
                ]);
            }
        }

        unset($_SESSION['panier']);
    }

    public function enlever_commande($venteId)
    {
        if (!$venteId) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Début transaction
        $this->bdd->beginTransaction();

        $stmt = $this->bdd->prepare(
            "SELECT produit_id, quantite 
             FROM ligne_vente 
             WHERE vente_id = :vente_id 
             AND statut = 'en_attente'"
        );
        $stmt->execute([':vente_id' => $venteId]);
        $lignes = $stmt->fetchAll();

        if (empty($lignes)) {
            $this->bdd->rollBack();
            return;
        }

        foreach ($lignes as $ligne) {
            $stmt = $this->bdd->prepare(
                "UPDATE produit 
                 SET quantiteActuelle = quantiteActuelle + :quantite
                 WHERE id = :id"
            );
            $stmt->execute([
                ':quantite' => $ligne['quantite'],
                ':id'       => $ligne['produit_id']
            ]);
        }

        $stmt = $this->bdd->prepare(
            "DELETE FROM ligne_vente WHERE vente_id = :vente_id"
        );
        $stmt->execute([':vente_id' => $venteId]);

        $stmt = $this->bdd->prepare(
            "DELETE FROM vente WHERE id = :vente_id"
        );
        $stmt->execute([':vente_id' => $venteId]);

        $this->bdd->commit();
    }

    public function getStatutCommandesClient($idCompte)
    {
        $stmt = $this->bdd->prepare(
            "SELECT 
                    v.id AS vente_id,
                    v.date_vente AS dateVente,
                    v.montant_total AS montant,
                    v.statut AS statut
             FROM vente v
             WHERE v.compte_id = :id
             AND v.statut NOT IN ('payee', 'annulee')
             ORDER BY v.date_vente DESC"
        );

        $stmt->execute([':id' => $idCompte]);
        $ventes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($ventes as $vente) {
            $stmt2 = $this->bdd->prepare(
                "SELECT p.nom, l.quantite, l.prix_unitaire
                 FROM ligne_vente l
                 JOIN produit p ON p.id = l.produit_id
                 WHERE l.vente_id = :vente_id"
            );
            $stmt2->execute([':vente_id' => $vente['vente_id']]);
            $produits = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $result[] = [
                'vente_id' => $vente['vente_id'],
                'dateVente' => $vente['dateVente'],
                'date' => $vente['dateVente'],
                'montant' => $vente['montant'],
                'statut' => $vente['statut'],
                'produits' => array_map(function($p) {
                    return [
                        'nom' => $p['nom'],
                        'quantite' => $p['quantite'],
                        'prix' => $p['prix_unitaire']
                    ];
                }, $produits)
            ];
        }

        return $result;
    }

    public function getCommandesClient($idCompte)
    {
        $stmt = $this->bdd->prepare(
            "SELECT 
                v.id AS vente_id,
                v.date_vente AS dateVente,
                v.montant_total AS montant,
                v.statut AS statut,
                l.produit_id,
                l.prix_unitaire,
                p.nom AS nom_produit,
                l.quantite AS quantite,
                l.statut AS statut_ligne
             FROM vente v
             JOIN ligne_vente l ON l.vente_id = v.id
             JOIN produit p ON p.id = l.produit_id
             WHERE v.compte_id = :idCompte
             AND v.statut = 'payee'
             ORDER BY v.date_vente DESC"
        );

        $stmt->execute([
            ':idCompte' => $idCompte
        ]);

        return $stmt->fetchAll();
    }
}