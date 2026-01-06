<?php
    class ProduitAcces {
        private $bdd;

        public function __construct() {
            $this->bdd = Connexion::getBdd();
        }

        public function enregistrerProduit(Produit $produit) {
            $stmt = $this->bdd->prepare(
                "INSERT INTO produit (nom, type, prix, quantiteActuelle)
             VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $produit->getNom(),
                $produit->getType(),
                $produit->getPrix(),
                $produit->getQuantiteActuelle()
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

            return new Produit(
                $row["id"],
                $row["nom"],
                $row["type"],
                $row["prix"],
                $row["quantiteActuelle"]
            );
        }

        public function tousLesProduits() {
            $stmt = $this->bdd->query("SELECT * FROM produit");
            $rows = $stmt->fetchAll();

            $produits = [];

            foreach ($rows as $row) {
                $produits[] = new Produit(
                    $row["id"],
                    $row["nom"],
                    $row["type"],
                    $row["prix"],
                    $row["quantiteActuelle"]
                );
            }

            return $produits;
        }

        public function miseAJourDuStock($idProduit, $nouveauStock) {
            $stmt = $this->bdd->prepare(
                "UPDATE produit SET quantiteActuelle = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauStock, $idProduit]);
        }
    }