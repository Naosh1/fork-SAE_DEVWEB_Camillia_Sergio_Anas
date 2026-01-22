<?php
class ProduitAcces {
    private $bdd;

    public function __construct() {
        $this->bdd = Connexion::getBdd();
    }

    // Renommé pour correspondre au Controleur (ligne 46)
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

    // Renommé pour correspondre au Controleur (ligne 81)
    public function ajouter_au_panier($idProduit)
    {
        if (!$idProduit) {
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

        if (!$produit || $produit['quantiteActuelle'] <= 0) {
            $this->bdd->rollBack();
            return;
        }

        $stmt = $this->bdd->prepare(
            "UPDATE produit SET quantiteActuelle = quantiteActuelle - 1 WHERE id = :id"
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
        if (!$idProduit) return;
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['panier']) || !isset($_SESSION['panier'][$idProduit])) return;

        $this->bdd->beginTransaction();
        $stmt = $this->bdd->prepare("UPDATE produit SET quantiteActuelle = quantiteActuelle + 1 WHERE id = :id");
        $stmt->execute([':id' => $idProduit]);

        $_SESSION['panier'][$idProduit]--;
        if ($_SESSION['panier'][$idProduit] <= 0) {
            unset($_SESSION['panier'][$idProduit]);
        }
        $this->bdd->commit();
    }

    // Cette méthode retourne maintenant uniquement les détails (ce qu'attend le contrôleur)
    public function panier()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $panier_details = [];
        if (!empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $idProduit => $quantite) {
                $stmt = $this->bdd->prepare("SELECT id, nom, prix FROM produit WHERE id = :id");
                $stmt->execute([':id' => $idProduit]);
                $produit = $stmt->fetch();

                if ($produit) {
                    $panier_details[] = [
                        'id' => $produit['id'],
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'qte' => $quantite,
                        'sous_total' => $produit['prix'] * $quantite
                    ];
                }
            }
        }
        return $panier_details;
    }

    // Ajout de la méthode de calcul du total attendue par le contrôleur (ligne 51)
    public function calculer_total_panier($panier_details) {
        $total = 0;
        foreach ($panier_details as $item) {
            $total += $item['sous_total'];
        }
        return $total;
    }
}