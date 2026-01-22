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

            // Sécurité : utilisateur connecté + panier non vide
            if (!isset($_SESSION['id']) || empty($_SESSION['panier']) || !isset($_SESSION['asso_choisi'])) {
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
                "INSERT INTO vente (compte_id, date_vente, montant_total, association_id)
                 VALUES (:compte_id, NOW(), :montant_total, :association_id)"
            );

            $stmt->execute([
                ':compte_id'     => $_SESSION['id'],
                ':montant_total' => $montantTotal,
                ':association_id' => $_SESSION['asso_choisi']
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
                         VALUES (:produit_id, :vente_id, :quantite, :prix, 'en attente')"
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

            // Récupérer les lignes de la commande (uniquement en attente)
            $stmt = $this->bdd->prepare(
                "SELECT produit_id, quantite 
                 FROM ligne_vente 
                 WHERE vente_id = :vente_id 
                 AND statut = 'en attente'"
            );
            $stmt->execute([':vente_id' => $venteId]);
            $lignes = $stmt->fetchAll();

            if (empty($lignes)) {
                $this->bdd->rollBack();
                return;
            }

            // Ré-incrémenter le stock
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

            // Supprimer les lignes de vente
            $stmt = $this->bdd->prepare(
                "DELETE FROM ligne_vente WHERE vente_id = :vente_id"
            );
            $stmt->execute([':vente_id' => $venteId]);

            // Supprimer la vente
            $stmt = $this->bdd->prepare(
                "DELETE FROM vente WHERE id = :vente_id"
            );
            $stmt->execute([':vente_id' => $venteId]);

            $this->bdd->commit();
        }


        public function getStatutCommandesClient($idCompte)
        {
            $stmt = $this->bdd->prepare(
                "SELECT vente_id, a.nom AS nom_association,
                 CASE 
                    WHEN SUM(statut = 'en attente') > 0 THEN 'en attente'
                    ELSE 'validée'
                 END AS statut
                 FROM ligne_vente
                 JOIN vente ON vente.id = ligne_vente.vente_id
                 JOIN association a ON vente.association_id = a.id
                 WHERE vente.compte_id = :id
                 GROUP BY vente_id"
            );

            $stmt->execute([':id' => $idCompte]);
            return $stmt->fetchAll();
        }

        public function getCommandesClient($idCompte)
        {
            $stmt = $this->bdd->prepare(
                "SELECT 
                     v.id AS vente_id,
                     v.date_vente AS dateVente,
                     v.montant_total AS montant,
                     a.nom AS nomAssociation,
                     l.produit_id,
                     p.nom AS nom_produit,
                     l.quantite AS quantite
                 FROM vente v
                 JOIN association a ON a.id = v.association_id
                 JOIN ligne_vente l ON l.vente_id = v.id
                 JOIN produit p ON p.id = l.produit_id
                 WHERE v.compte_id = :idCompte
                 ORDER BY v.date_vente DESC"
            );

            $stmt->execute([
                ':idCompte' => $idCompte
            ]);

            return $stmt->fetchAll();
        }

    }