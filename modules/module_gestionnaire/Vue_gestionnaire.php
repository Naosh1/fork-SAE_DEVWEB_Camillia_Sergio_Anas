<?php

class VueGestionnaire
{


    public function menu()
    {
        echo '<a href="index.php?action=produits">Produits</a> | ';
        echo '<a href="index.php?action=stock">Stock</a> | ';
        echo '<a href="index.php?action=ventes">Ventes</a> | ';
        echo '<a href="index.php?action=utilisateurs">Utilisateurs</a> | ';
        echo '<a href="index.php?action=statistiques">Statistiques</a>';
        echo '<a href=index.php?action=associations">Associations</a>';
    }

    public function afficherProduits($produits)
    {

        foreach ($produits as $produit) {
            echo '<div style = "margin-bottom: 10px;">';
            echo '<strong>' . $produit['nom'] . '</strong>' . $produit['prix'] . '€<br>';
            echo 'Stock : ' . $produit['stock'] . '<br>';
            echo '<a href="index.php?action=modifierProduit&id=' . $produit['id'] . '">Modifier</a>';
            echo '<a href="index.php?action=supprimerProduit&id=' . $produit['id'] . '">Supprimer</a>';
            echo '</div>';
        }
        echo '<a href="index.php?action=ajouterProduit"><button>Ajouter un produit</button></a>';
    }

    public function formulaireAjoutProduit($associations)
    {

        echo '<form action="index.php?action=ajouterProduit" method="post">
              <label for="nom">Nom du produit : </label>
              <input type="text" name="nom" required><br>
              
              <label for="prix">Prix : </label>
              <input type="number" name="prix" step="0.01" required><br> 
              
              <label for="stock">Stock : </label>
              <input type="number" name="stock" required><br>
              
              <label for="association">Association : </label>
              <select name="association" id="assos-select">
              <option value="">Choisir une association</option>';

        foreach ($associations as $association) {
            echo '<option value=""' . $association['id'] . '">' . htmlspecialchars($association['nom']) . '</option>';
        }

        echo '</select><br>
              <input type="submit" value="Ajouter">
            </form>';

        echo '<a href="index.php?action=produits"><button>Retour à la liste des produits</button></a>';
    }

    public function afficherStock($stocks)
    {
        foreach ($stocks as $stock) {
            echo $stock['produit'] . ' : ' . $stock['quantite'] . ' en stock<br>';
            echo '<a href="index.php?action=ajouterStock&id=' . $stock['id'] . '">Réapprovisionner</a>';
        }
    }

    public function afficherVentes($ventes)
    {
        foreach ($ventes as $vente) {
            echo 'Produit : ' . $vente['produit'] . '<br>' . 'Quantite : ' . $vente['quantite'] . '<br>' . 'Date : ' . $vente['date'] . '<br>';
        }
    }

    public function afficherUtilisateurs($utilisateurs)
    {
        foreach ($utilisateurs as $utilisateur) {
            echo $utilisateur['nom'] . ' ' . $utilisateur['prenom'] . ' (' . $utilisateur['role'] . ')<br>';
        }
    }

    public function afficherStatistiques($stats)
    {
        echo 'Total ventes : ' . $stats['totalVentes'] . '<br>';
    }
}