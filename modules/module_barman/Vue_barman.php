<?php
class Vue_barman {
    public function menu() {
        echo '<div class="menu" style="background-color: #f0f0f0; padding: 10px; margin-bottom: 20px;">';
        echo '<a href="index.php?action=accueil">Accueil</a> | ';
        echo '<a href="index.php?action=afficherProduits">Produits</a> | ';
        echo '<a href="index.php?action=rechercherClient">Rechercher Client</a> | ';
        echo '<a href="index.php?action=commandesEnCours">Commandes</a> | ';
        echo '<a href="index.php?action=creerTransaction">Nouvelle Transaction</a>';
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
                echo "{$p['nom']} - {$p['prix']} € - Stock: {$p['disponibilite']}";
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

    public function afficherFormTransaction($produits = [], $erreur = null, $donneesSaisies = null) {
        $this->menu();
        echo '<h2>Créer une transaction</h2>';

        if ($erreur) {
            echo '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">';
            echo '<strong>Erreur:</strong> ' . htmlspecialchars($erreur);
            echo '</div>';
        }

        echo '<form method="post" action="index.php">';
        echo '<input type="hidden" name="action" value="traiterTransaction">';

        echo '<div style="margin-bottom: 15px;">';
        echo '<label for="client_id">ID Client:</label><br>';
        $client_id = $donneesSaisies['client_id'] ?? '';
        echo '<input type="number" name="client_id" id="client_id" value="' . htmlspecialchars($client_id) . '" min="1" required>';
        echo '</div>';

        echo '<div style="margin-bottom: 15px;">';
        echo '<h3>Produits</h3>';
        echo '<div id="produits-container">';

        $produits_saisis = $donneesSaisies['produits'] ?? [['id' => '', 'quantite' => '1']];
        foreach ($produits_saisis as $index => $produit_saisi) {
            echo '<div class="produit-row" style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">';
            echo '<label>Produit:</label><br>';
            echo '<select name="produits[' . $index . '][id]" required>';
            echo '<option value="">-- Sélectionner un produit --</option>';
            foreach ($produits as $produit) {
                $selected = ($produit_saisi['id'] ?? '') == $produit['id'] ? 'selected' : '';
                echo '<option value="' . $produit['id'] . '" ' . $selected . '>';
                echo htmlspecialchars($produit['nom']) . ' - ' . $produit['prix'] . ' € (Stock: ' . $produit['disponibilite'] . ')';
                echo '</option>';
            }
            echo '</select><br>';

            echo '<label>Quantité:</label><br>';
            $quantite = $produit_saisi['quantite'] ?? '1';
            echo '<input type="number" name="produits[' . $index . '][quantite]" value="' . htmlspecialchars($quantite) . '" min="1" required>';

            if ($index > 0) {
                echo ' <button type="button" onclick="supprimerProduit(this)">Supprimer ce produit</button>';
            }
            echo '</div>';
        }

        echo '</div>';
        echo '<button type="button" onclick="ajouterProduit()">Ajouter un autre produit</button>';
        echo '</div>';

        echo '<button type="submit" onclick="return validerFormulaire()">Créer la transaction</button>';
        echo '</form>';

        echo '<script>
        let produitsDisponibles = ' . json_encode($produits) . ';
        let compteurProduit = ' . count($produits_saisis) . ';
        
        function ajouterProduit() {
            const container = document.getElementById("produits-container");
            const div = document.createElement("div");
            div.className = "produit-row";
            div.style.marginBottom = "10px";
            div.style.padding = "10px";
            div.style.border = "1px solid #ddd";
            
            let html = \'<label>Produit:</label><br>\';
            html += \'<select name="produits[\' + compteurProduit + \'][id]" required>\';
            html += \'<option value="">-- Sélectionner un produit --</option>\';
            
            produitsDisponibles.forEach(function(produit) {
                html += \'<option value="\' + produit.id + \'">\' + 
                        produit.nom + \' - \' + produit.prix + \' € (Stock: \' + produit.disponibilite + \')</option>\';
            });
            
            html += \'</select><br>\';
            html += \'<label>Quantité:</label><br>\';
            html += \'<input type="number" name="produits[\' + compteurProduit + \'][quantite]" value="1" min="1" required>\';
            html += \' <button type="button" onclick="supprimerProduit(this)">Supprimer ce produit</button>\';
            
            div.innerHTML = html;
            container.appendChild(div);
            compteurProduit++;
        }
        
        function supprimerProduit(bouton) {
            if (document.querySelectorAll(".produit-row").length > 1) {
                bouton.parentElement.remove();
            } else {
                alert("Vous devez avoir au moins un produit dans la transaction.");
            }
        }
        
        function validerFormulaire() {
            const clientId = document.getElementById("client_id").value;
            if (clientId <= 0) {
                alert("L\'ID client doit être un nombre positif.");
                return false;
            }
            
            const selectsProduits = document.querySelectorAll("select[name^=\'produits[\']");
            let produitIds = [];
            
            for (let select of selectsProduits) {
                if (!select.value) {
                    alert("Tous les produits doivent être sélectionnés.");
                    return false;
                }
                
                if (produitIds.includes(select.value)) {
                    alert("Un produit ne peut pas être ajouté deux fois. Veuillez augmenter la quantité.");
                    return false;
                }
                produitIds.push(select.value);
                
                const quantiteInput = select.parentElement.querySelector("input[type=\'number\']");
                if (quantiteInput.value <= 0) {
                    alert("La quantité doit être au moins de 1.");
                    return false;
                }
                
                const produitInfo = produitsDisponibles.find(p => p.id == select.value);
                if (produitInfo && quantiteInput.value > produitInfo.disponibilite) {
                    alert("Stock insuffisant pour \\"" + produitInfo.nom + "\\". Stock disponible: " + produitInfo.disponibilite);
                    return false;
                }
            }
            
            return confirm("Êtes-vous sûr de vouloir créer cette transaction ?");
        }
        </script>';
    }

    public function afficherResultatTransaction($vente_id) {
        $this->menu();

        echo '<h2>Transaction créée avec succès !</h2>';
        echo '<div style="border: 2px solid green; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h3>Transaction #' . htmlspecialchars($vente_id) . '</h3>';
        echo '<p><strong>Numéro de transaction:</strong> ' . htmlspecialchars($vente_id) . '</p>';
        echo '<p><strong>Statut:</strong> Transaction créée avec succès</p>';
        echo '</div>';

        echo '<p>La transaction a été enregistrée sous le numéro: <strong>' . htmlspecialchars($vente_id) . '</strong></p>';
        echo '<p>Le solde du client a été débité et les stocks ont été mis à jour.</p>';

        echo '<div style="margin-top: 20px;">';
        echo '<a href="index.php?action=creerTransaction" style="margin-right: 10px; padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Nouvelle transaction</a>';
        echo '<a href="index.php?action=detailCommande&id=' . $vente_id . '" style="padding: 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px;">Voir les détails</a>';
        echo '</div>';
    }
}