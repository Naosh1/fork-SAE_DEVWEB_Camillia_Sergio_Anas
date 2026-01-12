<?php
include_once '../vue_generique.php';

class VueGestionnaire extends VueGenerique
{

    public function gererAssociation($association, $barmans = [], $produits = [], $clients = [])
    {

        $soldeColor = $association['solde'] >= 0 ? 'text-green-600' : 'text-red-600';

        echo '<div class="max-w-6xl mx-auto mt-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800">' . $association['nom'] . ' - Gestion</h2>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p><strong>Adresse:</strong> ' . $association['adresse'] . '</p>
            <p><strong>Email:</strong> ' . $association['email'] . '</p>
            <p><strong>Téléphone:</strong> ' . $association['telephone'] . '</p>
            <p><strong>Solde:</strong> <span class="' . $soldeColor . '">' . $association['solde'] . ' €</span></p>
            <div class="mt-4 flex space-x-4">
                <a href="index.php?action=ajouterStock&id=' . $association['id'] . '" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Ajouter du stock</a>
                <a href="index.php?action=ajouterBarman&id=' . $association['id'] . '" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Ajouter un barman</a>
                <a href="index.php?action=voirClients&id=' . $association['id'] . '" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">Voir les clients</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Barmans</h3>';

        foreach ($barmans as $barman) {
            $roleClass = ($barman['role'] === 'barman') ? 'text-green-600' : 'text-gray-600';
            echo '<div class="flex justify-between items-center border-b border-gray-200 py-2">
                <span class="' . $roleClass . '">' . $barman['nom'] . ' ' . $barman['prenom'] . ' (' . $barman['email'] . ')</span>
                <a href="index.php?action=modifierBarman&id=' . $barman['id'] . '" class="text-blue-600 hover:text-blue-800 text-sm">Modifier</a>
            </div>';
        }

        echo '<h3 class="text-xl font-bold mb-4 mt-6">Produits</h3>';

        foreach ($produits as $produit) {
            echo '<div class="flex justify-between items-center border-b border-gray-200 py-2">
                <span>' . $produit['nom'] . ' (' . $produit['type'] . ') - Stock: ' . $produit['quantiteActuelle'] . '</span>
                <a href="index.php?action=modifierProduit&id=' . $produit['id'] . '" class="text-blue-600 hover:text-blue-800 text-sm">Modifier</a>
            </div>';
        }

        echo '</div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Clients</h3>';

        foreach ($clients as $client) {
            echo '<div class="border-b border-gray-200 py-2">
                ' . $client['nom'] . ' ' . $client['prenom'] . ' (' . $client['email'] . ')
            </div>';
        }

        echo '</div></div>';
    }

    public function afficherBarmans($barmans)
    {

        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<div class="flex justify-between items-center mb-6">';
        echo '<h2 class="text-2xl font-bold text-gray-800">Gestion du personnel</h2>';

        echo '<button onclick="window.location.href=\'index.php?action=ajouterBarman\'"';
        echo ' class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">';
        echo '<i class="fas fa-plus mr-2"></i> Ajouter un barman';
        echo '</button>';

        echo '</div>';

        if (isset($_SESSION['success'])) {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-check-circle mr-2"></i> ' . htmlspecialchars($_SESSION['success']);
            echo '</div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-exclamation-circle mr-2"></i> ' . htmlspecialchars($_SESSION['error']);
            echo '</div>';
            unset($_SESSION['error']);
        }

        if (empty($barmans)) {
            echo '<div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded">';
            echo '<i class="fas fa-info-circle mr-2"></i> Aucun barman enregistré.';
            echo '</div>';
        } else {
            echo '<div class="overflow-x-auto">';
            echo '<table class="min-w-full divide-y divide-gray-200">';
            echo '<thead class="bg-gray-50">';
            echo '<tr>';
            echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>';
            echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>';
            echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde</th>';
            echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>';
            echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody class="bg-white divide-y divide-gray-200">';

            foreach ($barmans as $barman) {
                $estBarman = ($barman['role'] === 'barman' || $barman['est_barman'] ?? false);
                $statutClass = $estBarman ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                $statutTexte = $estBarman ? 'Barman' : 'Autre';

                echo '<tr>';
                echo '<td class="px-6 py-4 whitespace-nowrap">';
                echo '<div class="font-medium text-gray-900">' . htmlspecialchars($barman['nom'] . ' ' . $barman['prenom']) . '</div>';
                echo '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-gray-600">' . htmlspecialchars($barman['email']) . '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-gray-600">' . htmlspecialchars($barman['solde']) . ' €</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap">';
                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $statutClass . '">';
                echo $statutTexte;
                echo '</span>';
                echo '</td>';
                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">';

                echo '<a href="index.php?action=modifierBarman&id=' . $barman['id'] . '" class="text-blue-600 hover:text-blue-900 mr-4">';
                echo '<i class="fas fa-edit mr-1"></i> Modifier';
                echo '</a>';

                if ($estBarman) {
                    echo '<a href="index.php?action=toggleBarman&id=' . $barman['id'] . '" class="text-yellow-600 hover:text-yellow-900 mr-4">';
                    echo '<i class="fas fa-power-off mr-1"></i> Désactiver';
                    echo '</a>';
                } else {
                    echo '<a href="index.php?action=toggleBarman&id=' . $barman['id'] . '" class="text-green-600 hover:text-green-900 mr-4">';
                    echo '<i class="fas fa-power-off mr-1"></i> Activer';
                    echo '</a>';
                }

                echo '<a href="index.php?action=reinitialiserMdpBarman&id=' . $barman['id'] . '" class="text-orange-600 hover:text-orange-900 mr-4" onclick="return confirm(\'Générer un nouveau mot de passe temporaire ?\')">';
                echo '<i class="fas fa-key mr-1"></i> Réinit. MDP';
                echo '</a>';

                echo '<a href="index.php?action=supprimerBarman&id=' . $barman['id'] . '" class="text-red-600 hover:text-red-900" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce barman ?\')">';
                echo '<i class="fas fa-trash mr-1"></i> Supprimer';
                echo '</a>';

                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }

        echo '</div>';
    }


    public function formulaireAjoutBarman()
    {
        echo '<div class="max-w-2xl mx-auto">';
        echo '<div class="bg-white rounded-lg shadow-md p-6">';
        echo '<h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un nouveau barman</h2>';

        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-exclamation-circle mr-2"></i> ' . htmlspecialchars($_SESSION['error']);
            echo '</div>';
            unset($_SESSION['error']);
        }

        echo '<form action="index.php?action=ajouterBarman" method="post" class="space-y-4">';

        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>';
        echo '<input type="text" name="nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>';
        echo '<input type="text" name="prenom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>';
        echo '<input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe temporaire *</label>';
        echo '<input type="password" name="mot_de_passe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '<p class="text-sm text-gray-500 mt-1">Le barman devra changer ce mot de passe à sa première connexion.</p>';
        echo '</div>';

        echo '<div class="flex justify-end space-x-4 pt-6">';
        echo '<a href="index.php?action=barmans" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">';
        echo 'Annuler';
        echo '</a>';
        echo '<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">';
        echo '<i class="fas fa-check mr-2"></i> Ajouter le barman';
        echo '</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    public function formulaireModificationBarman($barman)
    {
        echo '<div class="max-w-2xl mx-auto">';
        echo '<div class="bg-white rounded-lg shadow-md p-6">';
        echo '<h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le barman</h2>';

        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-exclamation-circle mr-2"></i> ' . htmlspecialchars($_SESSION['error']);
            echo '</div>';
            unset($_SESSION['error']);
        }

        echo '<form action="index.php?action=modifierBarman" method="post" class="space-y-4">';
        echo '<input type="hidden" name="id" value="' . $barman['id'] . '">';

        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>';
        echo '<input type="text" name="nom" value="' . htmlspecialchars($barman['nom']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>';
        echo '<input type="text" name="prenom" value="' . htmlspecialchars($barman['prenom']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>';
        echo '<input type="email" name="email" value="' . htmlspecialchars($barman['email']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div class="border-t pt-4">';
        echo '<h3 class="text-lg font-medium text-gray-800 mb-3">Changer le mot de passe</h3>';
        echo '<div class="flex items-center mb-4">';
        echo '<input type="checkbox" id="changer_mdp" name="changer_mdp" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">';
        echo '<label for="changer_mdp" class="ml-2 text-sm text-gray-700">Changer le mot de passe</label>';
        echo '</div>';

        echo '<div id="nouveau_mdp_fields" class="hidden space-y-4">';
        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe *</label>';
        echo '<input type="password" name="nouveau_mot_de_passe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>';
        echo '<input type="password" name="confirmer_mot_de_passe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="flex justify-end space-x-4 pt-6">';
        echo '<a href="index.php?action=barmans" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">';
        echo 'Annuler';
        echo '</a>';
        echo '<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">';
        echo '<i class="fas fa-save mr-2"></i> Enregistrer les modifications';
        echo '</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';
        echo '</div>';

        echo '<script>
        document.getElementById("changer_mdp").addEventListener("change", function() {
            const fields = document.getElementById("nouveau_mdp_fields");
            if (this.checked) {
                fields.classList.remove("hidden");
                fields.querySelectorAll("input").forEach(input => input.required = true);
            } else {
                fields.classList.add("hidden");
                fields.querySelectorAll("input").forEach(input => input.required = false);
            }
        });
    </script>';
    }

    public function afficherProduits($produits)
    {
        echo '<h2>Liste des produits</h2>';

        if (empty($produits)) {
            echo '<p>Aucun produit trouvé.</p>';
        } else {
            foreach ($produits as $produit) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo '<strong>' . htmlspecialchars($produit['nom']) . '</strong> - ' . htmlspecialchars($produit['prix']) . '€<br>';
                echo 'Type : ' . htmlspecialchars($produit['type']) . '<br>';
                echo 'Stock : ' . htmlspecialchars($produit['quantiteActuelle']) . '<br>';
                echo '<a href="index.php?action=modifierProduit&id=' . $produit['id'] . '">Modifier</a> | ';
                echo '<a href="index.php?action=supprimerProduit&id=' . $produit['id'] . '" onclick="return confirm(\'Êtes-vous sûr ?\')">Supprimer</a>';
                echo '</div>';
            }
        }
        echo '<br><a href="index.php?action=ajouterProduit"><button>Ajouter un produit</button></a>';
    }

    public function formulaireModificationProduit($produit)
    {
        echo '<h2>Modifier le produit</h2>';
        echo '<form action="index.php?action=modifierProduit" method="post">';
        echo '<input type="hidden" name="id" value="' . $produit['id'] . '">';
        echo '<label>Nom :</label>';
        echo '<input type="text" name="nom" value="' . htmlspecialchars($produit['nom']) . '" required><br><br>';
        echo '<label>Type :</label>';
        echo '<select name="type" required>';
        echo '<option value="boisson"' . ($produit['type'] == 'boisson' ? ' selected' : '') . '>Boisson</option>';
        echo '<option value="nourriture"' . ($produit['type'] == 'nourriture' ? ' selected' : '') . '>Nourriture</option>';
        echo '<option value="autre"' . ($produit['type'] == 'autre' ? ' selected' : '') . '>Autre</option>';
        echo '</select><br><br>';
        echo '<label>Prix :</label>';
        echo '<input type="number" name="prix" step="0.01" min="0" value="' . $produit['prix'] . '" required><br><br>';
        echo '<label>Stock :</label>';
        echo '<input type="number" name="stock" min="0" value="' . $produit['quantiteActuelle'] . '" required><br><br>';
        echo '<input type="submit" value="Modifier">';
        echo '</form>';
    }

    public function formulaireAjoutStock($produit)
    {
        echo '<h2>Réapprovisionner le stock</h2>';
        echo '<p>Produit : <strong>' . htmlspecialchars($produit['nom']) . '</strong></p>';
        echo '<p>Stock actuel : ' . $produit['quantiteActuelle'] . '</p>';
        echo '<form action="index.php?action=ajouterStock" method="post">';
        echo '<input type="hidden" name="id" value="' . $produit['id'] . '">';
        echo '<label>Quantité à ajouter :</label>';
        echo '<input type="number" name="quantite" min="1" required><br><br>';
        echo '<input type="submit" value="Ajouter au stock">';
        echo '</form>';
    }

    public function formulaireAjoutProduit($associations)
    {
        echo '<h2>Ajouter un produit</h2>';
        echo '<form action="index.php?action=ajouterProduit" method="post">
            <label>Nom :</label>
            <input type="text" name="nom" required><br><br>
            
            <label>Type :</label>
            <select name="type" required>
                <option value="">Choisir un type</option>
                <option value="boisson">Boisson</option>
                <option value="nourriture">Nourriture</option>
                <option value="autre">Autre</option>
            </select><br><br>
            
            <label>Prix :</label>
            <input type="number" name="prix" step="0.01" min="0" required><br><br>
            
            <label>Stock initial :</label>
            <input type="number" name="stock" min="0" required><br><br>
            
            <label>Association :</label>
            <select name="association_id" required>
                <option value="">Choisir une association</option>';

        foreach ($associations as $asso) {
            echo '<option value="' . $asso['id'] . '">' . htmlspecialchars($asso['nom']) . '</option>';
        }

        echo '</select><br><br>
            <input type="submit" value="Ajouter">
        </form>';
    }

    public function afficherStock($stocks)
    {
        echo '<h2>État du stock</h2>';

        if (empty($stocks)) {
            echo '<p>Aucun stock disponible.</p>';
        } else {
            foreach ($stocks as $stock) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo '<strong>' . htmlspecialchars($stock['produit']) . '</strong><br>';
                echo 'Quantité : ' . htmlspecialchars($stock['quantite']) . '<br>';
                echo '<a href="index.php?action=ajouterStock&id=' . $stock['id'] . '">Réapprovisionner</a>';
                echo '</div>';
            }
        }
    }

    public function afficherVentes($ventes)
    {
        echo '<h2>Historique des ventes</h2>';

        if (empty($ventes)) {
            echo '<p>Aucune vente enregistrée.</p>';
        } else {
            foreach ($ventes as $vente) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo 'ID Vente : ' . $vente['id'] . '<br>';
                echo 'Date : ' . $vente['date_vente'] . '<br>';
                echo 'Montant total : ' . $vente['montant_total'] . '€<br>';
                echo 'Client ID : ' . $vente['compte_id'] . '<br>';
                echo '</div>';
            }
        }
    }

    public function afficherUtilisateurs($utilisateurs)
    {
        echo '<h2>Liste des utilisateurs</h2>';

        if (empty($utilisateurs)) {
            echo '<p>Aucun utilisateur trouvé.</p>';
        } else {
            foreach ($utilisateurs as $utilisateur) {
                echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                echo 'Nom : ' . htmlspecialchars($utilisateur['nom']) . ' ' . htmlspecialchars($utilisateur['prenom']) . '<br>';
                echo 'Email : ' . htmlspecialchars($utilisateur['email']) . '<br>';
                echo 'Rôle : ' . htmlspecialchars($utilisateur['role']) . '<br>';
                echo 'Solde : ' . htmlspecialchars($utilisateur['solde']) . '€<br>';
                echo '</div>';
            }
        }
    }

    public function afficherStatistiques($stats)
    {
        echo '<h2>Statistiques</h2>';
        echo '<div style="border: 1px solid #ccc; padding: 10px;">';
        echo 'Total ventes : ' . $stats['totalVentes'] . '€<br>';
        echo 'Nombre de produits : ' . $stats['nbProduits'] . '<br>';
        echo 'Nombre d\'utilisateurs : ' . $stats['nbUtilisateurs'] . '<br>';
        echo 'Nombre d\'associations : ' . $stats['nbAssociations'] . '<br>';
        echo '</div>';
    }

    public function afficherAssociations($associations)
    {

        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Liste des associations</h2>
        </div>';

        if (isset($_SESSION['success'])) {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            <i class="fas fa-check-circle mr-2"></i>' . htmlspecialchars($_SESSION['success']) . '
        </div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i>' . htmlspecialchars($_SESSION['error']) . '
        </div>';
            unset($_SESSION['error']);
        }

        if (empty($associations)) {
            echo '<div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
            <i class="fas fa-info-circle mr-2"></i> Aucune association trouvée. Cliquez sur "Ajouter une association" pour commencer.
        </div>';
        } else {
            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
            foreach ($associations as $asso) {
                $soldeColor = $asso['solde'] >= 0 ? 'text-green-600' : 'text-red-600';
                $soldeBg = $asso['solde'] >= 0 ? 'bg-green-50' : 'bg-red-50';

                echo '<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-lg text-gray-800">' . htmlspecialchars($asso['nom']) . '</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded ' . $soldeBg . ' ' . $soldeColor . '">' . htmlspecialchars($asso['solde']) . ' €</span>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-start text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-gray-400"></i>
                        <span class="flex-1">' . htmlspecialchars($asso['adresse']) . '</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        <span>' . htmlspecialchars($asso['email']) . '</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>
                        <span>' . htmlspecialchars($asso['telephone']) . '</span>
                    </div>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-100">
                    <a href="index.php?action=modifierAssociation&id=' . $asso['id'] . '" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i> Modifier
                    </a>
                    <a href="index.php?action=gererAssociation&id=' . $asso['id'] . '" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium">
                        <i class="fas fa-cogs mr-1"></i> Gérer
                    </a>
                </div>
            </div>';
            }
            echo '</div>';
        }

        echo '</div>';
    }


    public function formulaireAjoutAssociation()
    {
        echo '<div class="max-w-2xl mx-auto">';
        echo '<div class="bg-white rounded-lg shadow-md p-6">';
        echo '<h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter une nouvelle association</h2>';

        if (isset($_SESSION['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-exclamation-circle mr-2"></i> ' . htmlspecialchars($_SESSION['error']);
            echo '</div>';
            unset($_SESSION['error']);
        }

        echo '<form action="index.php?action=ajouterAssociation" method="post" class="space-y-4">';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nom de l\'association *</label>';
        echo '<input type="text" name="nom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>';
        echo '<input type="text" name="adresse" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>';
        echo '<input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>';
        echo '<input type="tel" name="telephone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Solde initial (€)</label>';
        echo '<input type="number" name="solde" step="0.01" value="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
        echo '</div>';

        echo '<div class="flex justify-end space-x-4 pt-6">';
        echo '<a href="index.php?action=associations" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">';
        echo 'Annuler';
        echo '</a>';
        echo '<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">';
        echo '<i class="fas fa-check mr-2"></i> Ajouter l\'association';
        echo '</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    public function formulaireModificationAssociation($association)
    {
        echo '<div class="max-w-2xl mx-auto">';
        echo '<div class="bg-white rounded-lg shadow-md p-6">';
        echo '<h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier l\'association</h2>';

        echo '<form action="index.php?action=modifierAssociation" method="post" class="space-y-4">';
        echo '<input type="hidden" name="id" value="' . $association['id'] . '">';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Nom de l\'association</label>';
        echo '<input type="text" name="nom" value="' . htmlspecialchars($association['nom']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>';
        echo '<input type="text" name="adresse" value="' . htmlspecialchars($association['adresse']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Email</label>';
        echo '<input type="email" name="email" value="' . htmlspecialchars($association['email']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>';
        echo '<input type="tel" name="telephone" value="' . htmlspecialchars($association['telephone']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>';
        echo '</div>';
        echo '</div>';

        echo '<div>';
        echo '<label class="block text-sm font-medium text-gray-700 mb-2">Solde (€)</label>';
        echo '<input type="number" name="solde" step="0.01" value="' . htmlspecialchars($association['solde']) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
        echo '</div>';

        echo '<div class="flex justify-end space-x-4 pt-6">';
        echo '<a href="index.php?action=associations" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">';
        echo 'Annuler';
        echo '</a>';
        echo '<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">';
        echo '<i class="fas fa-save mr-2"></i> Enregistrer les modifications';
        echo '</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    public function afficherMessage($type, $message)
    {
        if ($type === 'success') {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-check-circle mr-2"></i> ' . htmlspecialchars($message);
            echo '</div>';
        } elseif ($type === 'error') {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-exclamation-circle mr-2"></i> ' . htmlspecialchars($message);
            echo '</div>';
        } elseif ($type === 'info') {
            echo '<div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded">';
            echo '<i class="fas fa-info-circle mr-2"></i> ' . htmlspecialchars($message);
            echo '</div>';
        }
    }

}