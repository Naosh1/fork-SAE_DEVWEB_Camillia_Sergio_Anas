<?php
class Vue_barman {
    public function menu() {
        echo '<div class="menu" style="background-color: #f0f0f0; padding: 10px; margin-bottom: 20px;">';
        echo '<a href="index.php?action=accueil">Accueil</a> | ';
        echo '<a href="index.php?action=afficherProduits">Produits</a> | ';
        echo '<a href="index.php?action=rechercherClient">Rechercher Client</a> | ';
        echo '<a href="index.php?action=commandesEnCours">Commandes</a>';
        echo '</div>';
    }

    public function afficherAccueil() {
        $this->menu();
        echo '<h1>Bienvenue dans le gestionnaire de buvette</h1>';
        echo '<p>Veuillez choisir une section dans le menu ci-dessus.</p>';
    }

    public function afficherProduits($produits) {
        $this->menu();
        echo "<h2>Produits en vente</h2>";
        if (empty($produits)) {
            echo "<p>Aucun produit disponible.</p>";
        } else {
            foreach ($produits as $p) {
                echo "<p>";
                echo "{$p['nom']} - {$p['prix']} € - {$p['disponibilite']}";
                echo "</p>";
            }
        }
    }

    public function afficherClients($clients) {
        $this->menu();
        echo "<h2>Recherche client</h2>";
        echo '<form method="get">';
        echo '<input type="hidden" name="action" value="rechercherClient">';
        echo '<input type="text" name="search" placeholder="Nom ou ID">';
        echo '<button type="submit">Rechercher</button>';
        echo '</form>';
        if (empty($clients)) {
            echo "<p>Aucun client trouvé.</p>";
        } else {
            foreach ($clients as $c) {
                echo "<p>";
                echo "ID: {$c['id']} | ";
                echo "Nom: {$c['nom']} | ";
                echo "Prénom: {$c['prenom']} | ";
                echo "Solde: {$c['solde']} €";
                echo "</p>";
            }
        }
    }

    public function afficherCommandes($commandes) {
        $this->menu();
        echo "<h2>Commandes en cours</h2>";
        if (empty($commandes)) {
            echo "<p>Aucune commande en cours.</p>";
        } else {
            foreach ($commandes as $c) {
                echo "<p>";
                echo "Commande n°{$c['commande_id']} | ";
                echo "{$c['prenom']} | ";
                echo "{$c['montant_total']} € | ";
                echo "<a href='index.php?action=detailCommande&id={$c['commande_id']}'>Voir</a>";
                echo "</p>";
            }
        }
    }

    public function afficherDetailCommande($commande, $produits) {
        $this->menu();
        echo "<h2>Détail commande</h2>";
        if (!$commande) {
            echo "<p>Commande introuvable.</p>";
            return;
        }
        echo "<p>";
        echo "Commande n°{$commande['commande_id']}<br>";
        echo "Client : {$commande['prenom']} {$commande['nom']}<br>";
        echo "Date : {$commande['date_vente']}<br>";
        echo "Montant : {$commande['montant_total']} €";
        echo "</p>";
        echo "<h3>Produits</h3>";
        if (empty($produits)) {
            echo "<p>Aucun produit dans cette commande.</p>";
        } else {
            foreach ($produits as $p) {
                echo "<p>";
                echo "{$p['nom']} x {$p['quantite']} ";
                echo "({$p['prix_unitaire']} €)";
                echo "</p>";
            }
        }
    }

    public function afficherErreur($message) {
        $this->menu();
        echo '<div style="color: red; padding: 10px; border: 1px solid red;">';
        echo '<strong>Erreur:</strong> ' . htmlspecialchars($message);
        echo '</div>';
    }
}