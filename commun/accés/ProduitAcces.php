<?php
    class ProduitAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrerProduit() {
            $nom = $_POST['nom'];
            $type = $_POST['type'];
            $prix = $_POST['prix'];
            $quantiteActuelle = $_POST['quantiteActuelle'];

            $stmt = $this->bdd->prepare(
                "INSERT INTO produit (nom, type, prix, quantiteActuelle)
             VALUES (:nom, :type, :prix, :quantiteActuelle)"
            );

            $stmt->execute([
                ":nom"   => $nom,
                ":type" => $type,
                ":prix"  => $prix,
                ":quantiteActuelle" => $quantiteActuelle
            ]);
        }

        public function supprimerProduitParID($id) {
            $stmt = $this->bdd->prepare(
                "DELETE FROM produit WHERE id = ?"
            );
            $stmt->execute([$id]);
        }

        public function rechercheProduitParID($id) {
            $stmt = $this->bdd->prepare("SELECT * FROM produit WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            return $row;
        }

        public function miseAJourDuStock($idProduit, $nouveauStock) {
            $stmt = $this->bdd->prepare(
                "UPDATE produit SET quantiteActuelle = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauStock, $idProduit]);
        }

        public function tousLesProduits() {
           $stmt = $this->bdd->prepare(
            "SELECT id, nom, type, prix, quantiteActuelle
             FROM produit
             WHERE quantiteActuelle > 0
             ORDER BY nom"
           );

           $stmt->execute();
           return $stmt->fetchAll();
        }

        public function ajouter_panier($idProduit)
        {
            if (!$idProduit) {
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Début transaction
            $this->bdd->beginTransaction();

            $stmt = $this->bdd->prepare(
                "SELECT quantiteActuelle 
                 FROM produit 
                 WHERE id = :id 
                 FOR UPDATE"
            );
            $stmt->execute([':id' => $idProduit]);
            $produit = $stmt->fetch();

            if (!$produit || $produit['quantiteActuelle'] <= 0) {
                $this->bdd->rollBack();
                return;
            }

            $stmt = $this->bdd->prepare(
                "UPDATE produit 
                 SET quantiteActuelle = quantiteActuelle - 1 
                 WHERE id = :id"
            );
            $stmt->execute([':id' => $idProduit]);

            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            if (isset($_SESSION['panier'][$idProduit])) {
                $_SESSION['panier'][$idProduit]++;
            } else {
                $_SESSION['panier'][$idProduit] = 1;
            }

            $this->bdd->commit();
        }

        public function enlever_panier($idProduit)
        {
            if (!$idProduit) {
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Si le panier est vide ou le produit absent
            if (empty($_SESSION['panier']) || !isset($_SESSION['panier'][$idProduit])) {
                return;
            }

            // Début transaction
            $this->bdd->beginTransaction();

            $stmt = $this->bdd->prepare(
                "UPDATE produit 
                 SET quantiteActuelle = quantiteActuelle + 1 
                 WHERE id = :id"
            );
            $stmt->execute([':id' => $idProduit]);

            $_SESSION['panier'][$idProduit]--;

            if ($_SESSION['panier'][$idProduit] <= 0) {
                unset($_SESSION['panier'][$idProduit]);
            }

            $this->bdd->commit();
        }


        public function panier()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $panier_details = [];
            $total_general = 0;

            if (!empty($_SESSION['panier'])) {

                foreach ($_SESSION['panier'] as $idProduit => $quantite) {

                    $stmt = $this->bdd->prepare(
                        "SELECT id, nom, prix FROM produit WHERE id = :id"
                    );
                    $stmt->execute([':id' => $idProduit]);
                    $produit = $stmt->fetch();

                    if ($produit) {
                        $sous_total = $produit['prix'] * $quantite;
                        $total_general += $sous_total;

                        $panier_details[] = [
                            'id' => $produit['id'],
                            'nom' => $produit['nom'],
                            'prix' => $produit['prix'],
                            'qte' => $quantite,
                            'sous_total' => $sous_total
                        ];
                    }
                }
            }

            return [
                'details' => $panier_details,
                'total'   => $total_general
            ];
        }

    }