<?php
include_once '../vue_generique.php';

class VueGestionnaire extends VueGenerique
{

    private function afficherAlerte($type, $message, $returnHTML = false)
    {
        $icons = [
                'success' => 'check-circle',
                'error' => 'exclamation-circle',
                'info' => 'info-circle'
        ];

        $classes = [
                'success' => 'bg-green-100 border-green-500 text-green-700',
                'error' => 'bg-red-100 border-red-500 text-red-700',
                'info' => 'bg-blue-100 border-blue-500 text-blue-700'
        ];

        $icon = $icons[$type] ?? 'info-circle';
        $class = $classes[$type] ?? 'bg-blue-100 border-blue-500 text-blue-700';

        $html = '<div class="border-l-4 p-4 mb-4 rounded ' . $class . '">
        <i class="fas fa-' . $icon . ' mr-2"></i>
        ' . htmlspecialchars($message) . '
    </div>';

        if ($returnHTML) {
            return $html;
        } else {
            echo $html;
        }
    }

    public function afficherNav()
    {
        $prenom = $_SESSION['prenom'];
        echo '
    <div class="flex min-h-screen bg-gray-100">

        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">

            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <span class="text-xl font-bold text-blue-600">AssoManager</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

                <p class="text-xs font-semibold text-gray-400 uppercase px-2 mb-2">Menu</p>

                <a href="index.php?action=accueil"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    📊 Dashboard
                </a>

                <a href="index.php?action=associations"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    🏢 Associations
                </a>

                <a href="index.php?action=messagerie"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    💬 Messagerie
                </a>

                <a href="index.php?action=notifications"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    🔔 Notifications
                </a>

                <p class="text-xs font-semibold text-gray-400 uppercase px-2 mt-6 mb-2">Gestion</p>

                <a href="index.php?action=barmans"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    🍺 Barmans
                </a>

                <a href="index.php?action=produits"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    📦 Produits & Stock
                </a>

                <a href="index.php?action=commandes"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition">
                    🧾 Commandes
                </a>

            </nav>

            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        ' . strtoupper(substr($prenom, 0, 1)) . '
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">' . htmlspecialchars($prenom) . '</p>
                        <p class="text-xs text-gray-500">Gestionnaire</p>
                    </div>
                </div>

                <a href="index.php?action=deconnexion"
                   class="flex items-center justify-center gap-2 w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition text-sm">
                    🚪 Déconnexion
                </a>
            </div>

        </aside>

        <main class="flex-1">
    ';
    }


    public function gererAssociation($association, $barmans, $produits, $clients)
    {
        $this->afficherNav();
        echo '<div class="p-6 bg-gray-50 min-h-screen">';

        echo '<h2 class="text-3xl font-bold mb-6 text-blue-700">Gestion de l\'association : ' . htmlspecialchars($association['nom']) . '</h2>';

        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8 space-y-2">';
        echo '<p><strong>Adresse :</strong> ' . htmlspecialchars($association['adresse']) . '</p>';
        echo '<p><strong>Email :</strong> ' . htmlspecialchars($association['email']) . '</p>';
        echo '<p><strong>Téléphone :</strong> ' . htmlspecialchars($association['telephone']) . '</p>';
        echo '<p><strong>Solde :</strong> ' . htmlspecialchars($association['solde']) . '€</p>';
        echo '</div>';

        echo '<h3 class="text-2xl font-semibold mb-4 text-blue-600">Barmans</h3>';
        if (!empty($barmans)) {
            echo '<div class="grid gap-3 mb-4">';
            foreach ($barmans as $barman) {
                echo '<div class="bg-white p-3 rounded-lg shadow flex justify-between items-center hover:bg-blue-50 transition">';
                echo '<span>' . htmlspecialchars($barman['prenom']) . ' ' . htmlspecialchars($barman['nom']) . ' (' . htmlspecialchars($barman['email']) . ')</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="mb-4 text-gray-500">Aucun barman pour cette association.</p>';
        }
        echo '<button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition mb-8">Ajouter un barman</button>';

        echo '<h3 class="text-2xl font-semibold mb-4 text-blue-600">Produits</h3>';
        if (!empty($produits)) {
            echo '<div class="grid gap-3 mb-4">';
            foreach ($produits as $produit) {
                echo '<div class="bg-white p-3 rounded-lg shadow flex justify-between items-center hover:bg-blue-50 transition">';
                echo '<span>' . htmlspecialchars($produit['nom']) . ' - ' . htmlspecialchars($produit['type']) . ' - ' . htmlspecialchars($produit['prix']) . '€ - Stock: ' . htmlspecialchars($produit['quantiteActuelle']) . '</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="mb-4 text-gray-500">Aucun produit lié à cette association.</p>';
        }
        echo '<button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition mb-8">Ajouter du stock</button>';


        echo '<h3 class="text-2xl font-semibold mb-4 text-blue-600">Clients</h3>';
        if (!empty($clients)) {
            echo '<div class="grid gap-3 mb-4">';
            foreach ($clients as $client) {
                echo '<div class="bg-white p-3 rounded-lg shadow flex justify-between items-center hover:bg-blue-50 transition">';
                echo '<span>' . htmlspecialchars($client['prenom']) . ' ' . htmlspecialchars($client['nom']) . ' (' . htmlspecialchars($client['email']) . ') - Solde : ' . htmlspecialchars($client['solde']) . '€</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-gray-500">Aucun client pour cette association.</p>';
        }

        echo '</div>';
    }


    public function afficherTableauDeBordAccueil($prenom, $associations)
    {
        $this->afficherNav();

        $soldeTotal = array_sum(array_column($associations, 'solde'));
        $totalClients = array_sum(array_map(fn($a)=>count($a['clients'] ?? []), $associations));
        $totalBarmans = array_sum(array_map(fn($a)=>count($a['barmans'] ?? []), $associations));

        $allProduits = [];
        $allClients = [];
        $allVentes = [];
        $allBarmans  = [];

        foreach ($associations as $asso) {
            if(!empty($asso['barman'])) $allBarmans = array_merge($allBarmans, $asso['barman']);
            if(!empty($asso['produits'])) $allProduits = array_merge($allProduits, $asso['produits']);
            if(!empty($asso['clients'])) $allClients = array_merge($allClients, $asso['clients']);
            if(!empty($asso['ventes'])) $allVentes = array_merge($allVentes, $asso['ventes']);
        }

        usort($allProduits, fn($a,$b)=>($b['ventes_totales'] ?? 0) <=> ($a['ventes_totales'] ?? 0));
        $topProduits = array_slice($allProduits,0,3);

        usort($allClients, fn($a,$b)=>($b['total_depense'] ?? 0) <=> ($a['total_depense'] ?? 0));
        $topClients = array_slice($allClients,0,5);

        usort($allVentes, fn($a,$b)=>strtotime($b['date']) - strtotime($a['date']));
        $recentVentes = array_slice($allVentes,0,5);
        ?>

        <div class="min-h-screen bg-gray-100 p-8">

            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-500 mt-1">
                    Bonjour <?= htmlspecialchars($prenom) ?> 👋 Voici un aperçu global.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg text-blue-600">🏢</div>
                    <div>
                        <p class="text-sm text-gray-500">Associations</p>
                        <p class="text-2xl font-bold"><?= count($associations) ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-lg text-green-600">💰</div>
                    <div>
                        <p class="text-sm text-gray-500">Solde total</p>
                        <p class="text-2xl font-bold <?= $soldeTotal >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format($soldeTotal,2,',',' ') ?> €
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="p-3 bg-purple-100 rounded-lg text-purple-600">🍺</div>
                    <div>
                        <p class="text-sm text-gray-500">Produits</p>
                        <p class="text-2xl font-bold"><?= count($allProduits) ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-lg text-orange-600">👤</div>
                    <div>
                        <p class="text-sm text-gray-500">Clients</p>
                        <p class="text-2xl font-bold"><?= $totalClients ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Top 3 produits vendus</h3>
                    <?php if(!empty($topProduits)): ?>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <?php foreach($topProduits as $prod): ?>
                                <li>
                                    <?= htmlspecialchars($prod['nom']) ?> - Vendu : <?= $prod['ventes_totales'] ?? 0 ?> fois - <?= number_format($prod['prix'],2,',',' ') ?> €
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Aucun produit vendu pour le moment.</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Clients les plus actifs</h3>
                    <?php if(!empty($topClients)): ?>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <?php foreach($topClients as $client): ?>
                                <li>
                                    <?= htmlspecialchars($client['prenom'].' '.$client['nom']) ?> - Total dépensé : <?= number_format($client['total_depense'] ?? 0,2,',',' ') ?> € - Dernier achat : <?= $client['dernier_achat'] ?? 'Aucun' ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Aucun client actif pour le moment.</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Dernières ventes</h3>
                    <?php if(!empty($recentVentes)): ?>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <?php foreach($recentVentes as $vente): ?>
                                <li>
                                    <?= htmlspecialchars($vente['produit_nom'] ?? 'Produit inconnu') ?> - <?= htmlspecialchars($vente['client_nom'] ?? 'Client inconnu') ?> - <?= $vente['date'] ?? '' ?> - <?= number_format($vente['montant'] ?? 0,2,',',' ') ?> €
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Aucune vente récente.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <?php
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


    public function afficherAssociationsValidees($associations)
    {
        $this->afficherNav();

        echo '<div class="p-8 w-full">';
        echo '
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mes Associations</h1>
            <p class="text-gray-500 mt-1">Liste des associations que vous gérez et qui sont validées</p>
        </div>
    </div>';

        foreach (['success' => 'green', 'error' => 'red'] as $type => $color) {
            if (!empty($_SESSION[$type])) {
                echo '
            <div class="mb-6 px-6 py-4 rounded-lg border border-' . $color . '-200 bg-' . $color . '-50 text-' . $color . '-700 shadow-sm">
                ' . htmlspecialchars($_SESSION[$type]) . '
            </div>';
                unset($_SESSION[$type]);
            }
        }

        if (empty($associations)) {
            echo '
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">
            Vous n\'avez validé aucune association pour le moment.
        </div>';
        } else {
            echo '<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">';

            foreach ($associations as $asso) {
                $solde = $asso['solde'] ?? 0;
                $soldeColor = $solde >= 0 ? 'text-green-600' : 'text-red-600';
                $soldeBg = $solde >= 0 ? 'bg-green-50' : 'bg-red-50';

                echo '
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition border border-gray-100">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">' . htmlspecialchars($asso['nom'] ?? '') . '</h3>
                            <div class="mt-1"><span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Validée</span></div>
                        </div>
                        <div class="px-3 py-1 text-sm font-medium rounded ' . $soldeBg . ' ' . $soldeColor . '">
                            ' . number_format($solde, 2, ',', ' ') . ' €
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 flex-1">
                        <div class="flex items-center gap-2"><span class="text-yellow-500">📍</span> ' . htmlspecialchars($asso['adresse'] ?? '') . '</div>
                        <div class="flex items-center gap-2"><span class="text-blue-500">✉️</span> ' . htmlspecialchars($asso['email'] ?? '') . '</div>
                        <div class="flex items-center gap-2"><span class="text-green-500">📞</span> ' . htmlspecialchars($asso['telephone'] ?? '') . '</div>
                    </div>

                    <div class="flex justify-end mt-5 pt-4 border-t border-gray-100">
                        <a href="index.php?action=voirAssociation&id=' . ($asso['id'] ?? 0) . '" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg font-medium transition">
                           Détails
                        </a>
                    </div>
                </div>
            </div>';
            }

            echo '</div>';
        }

        echo '</div>';
    }

    public function afficherDetailsAssos($assos)
    {
        $this->afficherNav();
        ?>

        <div class="p-6 bg-gray-50 min-h-screen">

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h1 class="text-3xl font-bold text-blue-700 mb-4">
                    <?= htmlspecialchars($assos['nom']) ?>
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <p><strong>Adresse :</strong> <?= htmlspecialchars($assos['adresse']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($assos['email']) ?></p>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($assos['telephone']) ?></p>
                    <p><strong>Solde :</strong>
                        <span class="<?= $assos['solde'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                        <?= number_format($assos['solde'], 2, ',', ' ') ?> €
                    </span>
                    </p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-blue-600">
                        Produits (<?= count($assos['produits']) ?>)
                    </h2>
                    <a href="index.php?action=voirProduits&id=<?= $assos['id'] ?>"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition">
                        Voir la liste complète
                    </a>
                </div>
                <?php if (!empty($assos['produits'])): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Prix</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Stock</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($assos['produits'] as $produit): ?>
                            <tr>
                                <td class="px-4 py-2"><?= htmlspecialchars($produit['nom']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($produit['type']) ?></td>
                                <td class="px-4 py-2"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                                <td class="px-4 py-2"><?= (int)$produit['quantiteActuelle'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">Aucun produit disponible.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-blue-600">
                        Barmans (<?= count($assos['barmans']) ?>)
                    </h2>
                    <a href="index.php?action=voirBarmans&id=<?= $assos['id'] ?>"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition">
                        Voir la liste complète
                    </a>
                </div>
                <?php if (!empty($assos['barmans'])): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($assos['barmans'] as $barman):
                            $actif = isset($barman['est_barman']) && $barman['est_barman']; ?>
                            <tr>
                                <td class="px-4 py-2"><?= htmlspecialchars($barman['prenom'] . ' ' . $barman['nom']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($barman['email']) ?></td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-white text-xs <?= $actif ? 'bg-green-600' : 'bg-gray-400' ?>">
                                        <?= $actif ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">Aucun barman associé.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-blue-600">
                        Clients (<?= count($assos['clients']) ?>)
                    </h2>
                    <a href="index.php?action=voirClients&id=<?= $assos['id'] ?>"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition">
                        Voir la liste complète
                    </a>
                </div>
                <?php if (!empty($assos['clients'])): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Solde</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($assos['clients'] as $client): ?>
                            <tr>
                                <td class="px-4 py-2"><?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($client['email']) ?></td>
                                <td class="px-4 py-2 <?= $client['solde'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= number_format($client['solde'], 2, ',', ' ') ?> €
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">Aucun client enregistré.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-blue-600 mb-4">
                    Dernières ventes
                </h2>
                <?php if (!empty($assos['ventes'])): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">ID Vente</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Client</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($assos['ventes'] as $vente): ?>
                            <tr>
                                <td class="px-4 py-2"><?= $vente['id'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($vente['date_vente']) ?></td>
                                <td class="px-4 py-2"><?= number_format($vente['montant_total'], 2, ',', ' ') ?> €</td>
                                <td class="px-4 py-2"><?= htmlspecialchars($vente['client_prenom'] . ' ' . $vente['client_nom']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">Aucune vente enregistrée.</p>
                <?php endif; ?>
            </div>

        </div>
        <?php
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


}