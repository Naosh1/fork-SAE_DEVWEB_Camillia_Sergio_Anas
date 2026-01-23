<?php
class ProduitAcces {
    private $bdd;

    public function __construct() {
        $this->bdd = Connexion::getBdd();
    }

    public function liste_produits() {
        $stmt = $this->bdd->prepare(
            "SELECT id, nom, type, prix, quantiteActuelle
             FROM produit
             WHERE quantiteActuelle > 0
             ORDER BY nom"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function ajouter_au_panier($idProduit, $quantite = 1)
    {
        if (!$idProduit || $quantite <= 0) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->bdd->beginTransaction();

        $stmt = $this->bdd->prepare(
            "SELECT quantiteActuelle FROM produit WHERE id = :id FOR UPDATE"
        );
        $stmt->execute([':id' => $idProduit]);
        $produit = $stmt->fetch();

        if (!$produit || $produit['quantiteActuelle'] < $quantite) {
            $this->bdd->rollBack();
            return;
        }

        $stmt = $this->bdd->prepare(
            "UPDATE produit SET quantiteActuelle = quantiteActuelle - :quantite WHERE id = :id"
        );
        $stmt->execute([':id' => $idProduit, ':quantite' => $quantite]);

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        if (isset($_SESSION['panier'][$idProduit])) {
            $_SESSION['panier'][$idProduit] += $quantite;
        } else {
            $_SESSION['panier'][$idProduit] = $quantite;
        }

        $this->bdd->commit();
    }

    public function enlever_panier($idProduit)
    {
        if (!$idProduit) return;
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['panier']) || !isset($_SESSION['panier'][$idProduit])) return;

        $quantiteARetirer = $_SESSION['panier'][$idProduit];

        $this->bdd->beginTransaction();

        $stmt = $this->bdd->prepare("UPDATE produit SET quantiteActuelle = quantiteActuelle + :quantite WHERE id = :id");
        $stmt->execute([':id' => $idProduit, ':quantite' => $quantiteARetirer]);

        unset($_SESSION['panier'][$idProduit]);

        $this->bdd->commit();
    }

    public function modifier_quantite_panier($idProduit, $nouvelleQuantite)
    {
        if (!$idProduit || $nouvelleQuantite <= 0) return;
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['panier']) || !isset($_SESSION['panier'][$idProduit])) return;

        $ancienneQuantite = $_SESSION['panier'][$idProduit];
        $difference = $nouvelleQuantite - $ancienneQuantite;

        if ($difference == 0) return;

        $this->bdd->beginTransaction();

        if ($difference > 0) {
            $stmt = $this->bdd->prepare("SELECT quantiteActuelle FROM produit WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $idProduit]);
            $produit = $stmt->fetch();

            if (!$produit || $produit['quantiteActuelle'] < $difference) {
                $this->bdd->rollBack();
                return;
            }

            $stmt = $this->bdd->prepare("UPDATE produit SET quantiteActuelle = quantiteActuelle - :diff WHERE id = :id");
            $stmt->execute([':id' => $idProduit, ':diff' => $difference]);
        } else {
            $stmt = $this->bdd->prepare("UPDATE produit SET quantiteActuelle = quantiteActuelle + :diff WHERE id = :id");
            $stmt->execute([':id' => $idProduit, ':diff' => abs($difference)]);
        }

        $_SESSION['panier'][$idProduit] = $nouvelleQuantite;

        $this->bdd->commit();
    }

    public function panier()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $panier_details = [];
        if (!empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $idProduit => $quantite) {
                $stmt = $this->bdd->prepare("SELECT id, nom, prix, quantiteActuelle FROM produit WHERE id = :id");
                $stmt->execute([':id' => $idProduit]);
                $produit = $stmt->fetch();

                if ($produit) {
                    $panier_details[] = [
                        'id' => $produit['id'],
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'qte' => $quantite,
                        'quantite' => $quantite,
                        'stock' => $produit['quantiteActuelle'],
                        'sous_total' => $produit['prix'] * $quantite
                    ];
                }
            }
        }
        return $panier_details;
    }

    public function calculer_total_panier($panier_details) {
        $total = 0;
        foreach ($panier_details as $item) {
            $total += $item['sous_total'];
        }
        return $total;
    }
}