<?php

class Vue_barman
{
    public function afficherProduits($produits)
    {
        echo "<h2>Produits en vente</h2>";

        foreach ($produits as $p) {
            echo "<p>
                {$p['nom']} - {$p['prix']} € - {$p['disponibilite']}
            </p>";
        }
    }

    public function afficherClients($clients)
    {
        echo "<h2>Recherche client</h2>";

        echo '
        <form method="get">
            <input type="hidden" name="action" value="clients">
            <input type="text" name="search" placeholder="Nom ou ID">
            <button type="submit">Rechercher</button>
        </form>
        ';

        foreach ($clients as $c) {
            echo "<p>
                ID: {$c['id']} |
                Prénom: {$c['prenom']} |
                Solde: {$c['solde']} €
            </p>";
        }
    }

    public function afficherCommandes($commandes)
    {
        echo "<h2>Commandes en cours</h2>";

        foreach ($commandes as $c) {
            echo "<p>
                Commande n°{$c['commande_id']} |
                {$c['prenom']} |
                {$c['montant_total']} €
                <a href='index.php?action=commande&id={$c['commande_id']}'>Voir</a>
            </p>";
        }
    }

    public function afficherDetailCommande($commande, $produits)
    {
        echo "<h2>Détail commande</h2>";

        echo "<p>
            Commande n°{$commande['commande_id']}<br>
            Client : {$commande['prenom']}<br>
            Montant : {$commande['montant_total']} €
        </p>";

        echo "<h3>Produits</h3>";

        foreach ($produits as $p) {
            echo "<p>
                {$p['nom']} x {$p['quantite']}
                ({$p['prix_unitaire']} €)
            </p>";
        }
    }
}
