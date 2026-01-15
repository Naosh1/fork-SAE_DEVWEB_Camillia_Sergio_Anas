<?php

include_once 'connexion/Connexion.php';

class ModeleBarman extends Connexion {

    /* =========================
       PRODUITS
       ========================= */

    public function listerProduits()
    {
        $stmt = self::getBdd()->prepare(
            "SELECT id, nom, prix, quantiteActuelle AS disponibilite
             FROM produit
             ORDER BY nom"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInfoProduit($produit_id)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT id, nom, prix, quantiteActuelle AS disponibilite
             FROM produit
             WHERE id = ?"
        );
        $stmt->execute([$produit_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       CLIENTS
       ========================= */

    public function rechercherClient($search)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT id, nom, prenom, solde
             FROM compte
             WHERE nom LIKE ?
                OR prenom LIKE ?
                OR id = ?"
        );
        $like = "%$search%";
        $stmt->execute([$like, $like, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoldeClient($client_id)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT solde FROM compte WHERE id = ?"
        );
        $stmt->execute([$client_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (float)$res['solde'] : false;
    }

    private function verifierCompteExiste($compte_id)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT id FROM compte WHERE id = ?"
        );
        $stmt->execute([$compte_id]);
        return $stmt->fetch() !== false;
    }

    /* =========================
       COMMANDES / VENTES
       ========================= */

    public function listerCommandesEnCours()
    {
        $stmt = self::getBdd()->prepare(
            "SELECT v.id AS commande_id,
                    c.prenom, c.nom,
                    v.date_vente, v.montant_total, v.statut
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             WHERE v.statut = 'payee'
               AND DATE(v.date_vente) = CURDATE()
             ORDER BY v.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommande($id)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT v.id AS commande_id,
                    c.prenom, c.nom,
                    v.date_vente, v.montant_total
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             WHERE v.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueCommandes()
    {
        $stmt = self::getBdd()->prepare(
            "SELECT v.id AS commande_id,
                    v.date_vente,
                    v.montant_total,
                    COALESCE(v.statut, 'payee') AS statut,
                    c.id AS client_id,
                    c.prenom, c.nom
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             ORDER BY v.id DESC"
        );
        $stmt->execute();
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($commandes as &$commande) {
            $dt = new DateTime($commande['date_vente']);
            $commande['date_heure_affichage'] = $dt->format('d/m/Y à H:i');
        }

        return $commandes;
    }

    public function getProduitsCommande($vente_id)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT p.nom, c.quantite, c.prix_unitaire
             FROM contient c
             JOIN produit p ON c.produit_id = p.id
             WHERE c.vente_id = ?"
        );
        $stmt->execute([$vente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       TRANSACTION
       ========================= */

    public function creerTransaction($produits, $compte_id, $montant_total)
    {
        self::getBdd()->beginTransaction();

        if (!$this->verifierCompteExiste($compte_id)) {
            self::getBdd()->rollBack();
            return false;
        }

        if (!$this->debiterCompte($compte_id, $montant_total)) {
            self::getBdd()->rollBack();
            return false;
        }

        $stmt = self::getBdd()->prepare(
            "INSERT INTO vente (date_vente, montant_total, compte_id, statut)
             VALUES (NOW(), ?, ?, 'payee')"
        );
        $stmt->execute([$montant_total, $compte_id]);
        $vente_id = self::getBdd()->lastInsertId();

        $this->insererProduitsVente($vente_id, $produits);
        $this->mettreAJourStocks($produits);

        self::getBdd()->commit();
        return $vente_id;
    }

    private function debiterCompte($compte_id, $montant)
    {
        $stmt = self::getBdd()->prepare(
            "SELECT solde FROM compte WHERE id = ?"
        );
        $stmt->execute([$compte_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res || $res['solde'] < $montant) {
            return false;
        }

        $stmt = self::getBdd()->prepare(
            "UPDATE compte SET solde = solde - ? WHERE id = ?"
        );
        return $stmt->execute([$montant, $compte_id]);
    }

    private function insererProduitsVente($vente_id, $produits)
    {
        $stmt = self::getBdd()->prepare(
            "INSERT INTO contient (produit_id, vente_id, quantite, prix_unitaire)
             VALUES (?, ?, ?, ?)"
        );

        foreach ($produits as $produit) {
            $stmt->execute([
                $produit['id'],
                $vente_id,
                $produit['quantite'],
                $produit['prix']
            ]);
        }
    }

    private function mettreAJourStocks($produits)
    {
        $stmt = self::getBdd()->prepare(
            "UPDATE produit
             SET quantiteActuelle = quantiteActuelle - ?
             WHERE id = ?"
        );

        foreach ($produits as $produit) {
            $stmt->execute([
                $produit['quantite'],
                $produit['id']
            ]);
        }
    }

    /* =========================
       ANNULATION
       ========================= */

    public function getDerniereTransaction()
    {
        $stmt = self::getBdd()->prepare(
            "SELECT v.id AS transaction_id,
                    v.date_vente,
                    v.montant_total,
                    v.statut,
                    c.id AS client_id,
                    c.nom, c.prenom, c.solde
             FROM vente v
             JOIN compte c ON v.compte_id = c.id
             WHERE v.statut != 'annulee' OR v.statut IS NULL
             ORDER BY v.id DESC
             LIMIT 1"
        );
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            $stmt = self::getBdd()->prepare(
                "SELECT p.nom, c.quantite, c.prix_unitaire
                 FROM contient c
                 JOIN produit p ON c.produit_id = p.id
                 WHERE c.vente_id = ?"
            );
            $stmt->execute([$transaction['transaction_id']]);
            $transaction['produits'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $transaction;
    }

    public function annulerTransaction($transaction_id)
    {
        self::getBdd()->beginTransaction();

        $stmt = self::getBdd()->prepare(
            "SELECT compte_id, montant_total, date_vente, statut
             FROM vente
             WHERE id = ?"
        );
        $stmt->execute([$transaction_id]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction || $transaction['statut'] === 'annulee') {
            self::getBdd()->rollBack();
            return false;
        }

        $dateVente = date('Y-m-d', strtotime($transaction['date_vente']));
        if ($dateVente !== date('Y-m-d')) {
            self::getBdd()->rollBack();
            return false;
        }

        self::getBdd()->prepare(
            "UPDATE compte SET solde = solde + ? WHERE id = ?"
        )->execute([$transaction['montant_total'], $transaction['compte_id']]);

        $stmt = self::getBdd()->prepare(
            "SELECT produit_id, quantite FROM contient WHERE vente_id = ?"
        );
        $stmt->execute([$transaction_id]);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtStock = self::getBdd()->prepare(
            "UPDATE produit SET quantiteActuelle = quantiteActuelle + ? WHERE id = ?"
        );

        foreach ($produits as $p) {
            $stmtStock->execute([$p['quantite'], $p['produit_id']]);
        }

        self::getBdd()->prepare(
            "UPDATE vente SET statut = 'annulee' WHERE id = ?"
        )->execute([$transaction_id]);

        self::getBdd()->commit();
        return true;
    }
}
