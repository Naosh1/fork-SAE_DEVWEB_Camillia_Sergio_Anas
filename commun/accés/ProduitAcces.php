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

        public function miseAJourDuStock($idProduit, $nouveauStock) {
            $stmt = $this->bdd->prepare(
                "UPDATE produit SET quantiteActuelle = ? WHERE id = ?"
            );
            $stmt->execute([$nouveauStock, $idProduit]);
        }

    }