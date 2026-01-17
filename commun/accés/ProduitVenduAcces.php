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

            // Sécurité : utilisateur connecté
            if (!isset($_SESSION['id']) || empty($_SESSION['panier'])) {
                return;
            }

            $stmt = $this->bdd->prepare(
                "INSERT INTO vente (compte_id, date_vente)
                 VALUES (:compte_id, NOW())"
            );
            $stmt->execute([
                ':compte_id' => $_SESSION['id']
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

        public function getStatutCommandesClient($idCompte)
        {
            $stmt = $this->bdd->prepare(
                "SELECT vente_id,
                CASE 
                    WHEN SUM(statut = 'en attente') > 0 THEN 'en attente'
                    ELSE 'validée'
                END AS statut
         FROM ligne_vente
         JOIN vente ON vente.id = ligne_vente.vente_id
         WHERE vente.compte_id = :id
         GROUP BY vente_id"
            );

            $stmt->execute([':id' => $idCompte]);
            return $stmt->fetchAll();
        }
    }