<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";
include "modules/module_staff/vue_staff.php";

class Vue_barman extends VueStaff
{
    public function afficherCommandes($commandes) {
        $this->afficherHeader("Commandes");
        $this->afficherMenu();
        echo "<h2>Commandes du jour</h2>";
        echo "<table border='1'><tr><th>ID</th><th>Client</th><th>Montant</th><th>Statut</th><th>Action</th></tr>";
        foreach ($commandes as $commande) {
            $color = ($commande['statut'] == 'payee') ? 'green' : 'red';
            echo "<tr>
                <td>{$commande['commande_id']}</td>
                <td>{$commande['prenom']} {$commande['nom']}</td>
                <td>{$commande['montant_total']} €</td>
                <td style='color:$color; font-weight:bold;'>".ucfirst($commande['statut'])."</td>
                <td><a href='index.php?action=detailCommande&id={$commande['commande_id']}'>Voir</a></td>
            </tr>";
        }
        echo "</table>";
        $this->afficherFooter();
    }


private function afficherHeader($titre = "Gestionnaire de buvette")
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($titre) ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }

            .menu {
                background-color: #f0f0f0;
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
            }

            .menu a {
                margin-right: 15px;
                text-decoration: none;
                color: #333;
                font-weight: bold;
            }

            .menu a:hover {
                color: #007bff;
            }

            .erreur {
                color: red;
                padding: 10px;
                border: 1px solid red;
                margin-bottom: 15px;
                border-radius: 5px;
            }

            .succes {
                border: 2px solid green;
                padding: 15px;
                margin: 15px 0;
                border-radius: 5px;
            }

            .produit-row {
                margin-bottom: 10px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .btn {
                padding: 10px 15px;
                border-radius: 4px;
                text-decoration: none;
                display: inline-block;
                margin-right: 10px;
            }

            .btn-primary {
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
            }

            .btn-secondary {
                background-color: #2196F3;
                color: white;
                border: none;
                cursor: pointer;
            }

            .btn-danger {
                background-color: #f44336;
                color: white;
                border: none;
                cursor: pointer;
            }

            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }

            table th, table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            table th {
                background-color: #4CAF50;
                color: white;
            }

            input, select {
                padding: 8px;
                margin: 5px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
    <?php
    }

    private function afficherMenu()
    {
        ?>
        <nav class="menu">
            <div class="nav-section">
                <strong>Boutique :</strong>
                <a href="index.php?module=barman&action=accueil">Accueil</a>
                <a href="index.php?module=barman&action=creerTransaction">Vendre</a>
                <a href="index.php?module=barman&action=commandesEnCours">Commandes du jour</a>
                <a href="index.php?module=barman&action=historiqueCommandes">Historique</a>
            </div>

            <div class="nav-section" style="margin-top: 10px; border-top: 1px solid #ccc; padding-top: 10px;">
                <strong>Compte :</strong>
                <a href="index.php?module=barman&action=messagerie">Messagerie</a>
                <a href="index.php?module=barman&action=monProfil">Mon Profil</a>
                <a href="index.php?action=deconnexion" style="color: red;">Déconnexion</a>
            </div>
        </nav>
        <?php
    }

    private function afficherFooter() {
    ?>
    </body>
    </html>
    <?php
}

    public function afficherAccueil()
    {
        $this->afficherHeader("Accueil");
        $this->afficherMenu();
        ?>
        <h1>Bienvenue dans le gestionnaire de buvette</h1>
        <p>Veuillez choisir une section dans le menu ci-dessus.</p>
        <?php
        $this->afficherFooter();
    }

    public function afficherDerniereTransaction($transaction)
    {
        $this->afficherHeader("Dernière transaction");
        $this->afficherMenu();
        ?>
        <h2>Dernière transaction effectuée</h2>

        <?php if (!$transaction): ?>
        <p>Aucune transaction trouvée.</p>
    <?php else: ?>
        <div style="background-color: <?= ($transaction['statut'] ?? 'payee') === 'annulee' ? '#ffebee' : '#f9f9f9' ?>; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>Transaction #<?= htmlspecialchars($transaction['transaction_id']) ?></h3>
            <p><strong>Date:</strong> <?= htmlspecialchars($transaction['date_vente']) ?></p>
            <p><strong>Client:</strong> <?= htmlspecialchars($transaction['prenom'] . ' ' . $transaction['nom']) ?>
                (ID: <?= htmlspecialchars($transaction['client_id']) ?>)</p>
            <p><strong>Montant total:</strong> <?= htmlspecialchars($transaction['montant_total']) ?> €</p>
            <p><strong>Statut:</strong>
                <span style="color: <?= ($transaction['statut'] ?? 'payee') === 'annulee' ? 'red' : 'green' ?>; font-weight: bold;">
                    <?= htmlspecialchars(ucfirst($transaction['statut'] ?? 'payee')) ?>
                </span>
            </p>
        </div>

        <?php if (!empty($transaction['produits'])): ?>
            <h3>Produits commandés</h3>
            <table>
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($transaction['produits'] as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= htmlspecialchars($produit['quantite']) ?></td>
                        <td><?= htmlspecialchars($produit['prix_unitaire']) ?> €</td>
                        <td><?= htmlspecialchars($produit['quantite'] * $produit['prix_unitaire']) ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (($transaction['statut'] ?? 'payee') !== 'annulee'): ?>
            <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px;">
                <h4>⚠️ Annuler cette transaction</h4>
                <p>L'annulation de cette transaction va:</p>
                <ul>
                    <li>Rembourser le client de <?= htmlspecialchars($transaction['montant_total']) ?> €</li>
                    <li>Remettre les produits en stock</li>
                    <li>Marquer la transaction comme annulée</li>
                </ul>
                <form method="post" action="index.php"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ? Cette action est irréversible.')">
                    <input type="hidden" name="action" value="annulerTransaction">
                    <input type="hidden" name="transaction_id"
                           value="<?= htmlspecialchars($transaction['transaction_id']) ?>">
                    <button type="submit" class="btn btn-danger">Annuler la transaction</button>
                </form>
            </div>
        <?php else: ?>
            <div style="margin-top: 20px; padding: 15px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 5px;">
                <p><strong>Cette transaction a déjà été annulée.</strong></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
    }

    public function afficherConfirmationAnnulation($transaction_id)
    {
        $this->afficherHeader("Transaction annulée");
        $this->afficherMenu();
        ?>
        <h2>Transaction annulée avec succès</h2>
        <div class="succes" style="background-color: #d4edda; border-color: #c3e6cb;">
            <h3>✓ Transaction #<?= htmlspecialchars($transaction_id) ?> annulée</h3>
            <p>La transaction a été annulée avec succès.</p>
            <ul>
                <li>Le client a été remboursé</li>
                <li>Les stocks ont été remis à jour</li>
                <li>La transaction est maintenant marquée comme annulée</li>
            </ul>
        </div>

        <div style="margin-top: 20px;">
            <a href="index.php?action=derniereTransaction" class="btn btn-secondary">Voir la dernière transaction</a>
            <a href="index.php?action=creerTransaction" class="btn btn-primary">Nouvelle transaction</a>
            <a href="index.php?action=commandesEnCours" class="btn btn-secondary">Voir toutes les commandes</a>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function afficherHistoriqueCommandes($commandes)
    {
        $this->afficherHeader("Historique des commandes");
        $this->afficherMenu();
        ?>
        <h2>Historique des commandes</h2>

        <?php if (empty($commandes)): ?>
        <p>Aucune commande dans l'historique.</p>
    <?php else: ?>
        <div style="margin-bottom: 20px;">
            <p><strong>Total des commandes :</strong> <?= count($commandes) ?></p>
        </div>

        <table>
            <thead>
            <tr>
                <th>N° Commande</th>
                <th>Date et Heure</th>
                <th>Client</th>
                <th>Montant Total</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalTerminees = 0;
            $totalAnnulees = 0;
            $montantTotal = 0;

            foreach ($commandes as $commande):
                $classeStatut = '';
                $couleurStatut = '';

                switch ($commande['statut']) {
                    case 'payee':
                        $classeStatut = 'statut-terminee';
                        $couleurStatut = '#4CAF50';
                        $totalTerminees++;
                        $montantTotal += $commande['montant_total'];
                        break;
                    case 'annulee':
                        $classeStatut = 'statut-annulee';
                        $couleurStatut = '#f44336';
                        $totalAnnulees++;
                        break;
                    default:
                        $couleurStatut = '#FF9800';
                        break;
                }
                ?>
                <tr>
                    <td><strong>#<?= htmlspecialchars($commande['commande_id']) ?></strong></td>
                    <td><?= htmlspecialchars($commande['date_heure_affichage']) ?></td>
                    <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                    <td><?= htmlspecialchars(number_format($commande['montant_total'], 2)) ?> €</td>
                    <td>
                                <span style="color: <?= $couleurStatut ?>; font-weight: bold;">
                                    <?= htmlspecialchars($commande['statut_affichage']) ?>
                                </span>
                    </td>
                    <td>
                        <a href="index.php?action=detailCommande&id=<?= $commande['commande_id'] ?>"
                           class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                            Détails
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bilan récapitulatif -->
        <div style="margin-top: 30px; padding: 20px; background-color: #f0f0f0; border-radius: 5px;">
            <h3>📊 Bilan récapitulatif</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 15px;">
                <div style="background-color: white; padding: 15px; border-radius: 5px; text-align: center;">
                    <h4 style="margin: 0; color: #4CAF50;">Commandes terminées</h4>
                    <p style="font-size: 24px; font-weight: bold; margin: 10px 0;"><?= $totalTerminees ?></p>
                </div>
                <div style="background-color: white; padding: 15px; border-radius: 5px; text-align: center;">
                    <h4 style="margin: 0; color: #f44336;">Commandes annulées</h4>
                    <p style="font-size: 24px; font-weight: bold; margin: 10px 0;"><?= $totalAnnulees ?></p>
                </div>
                <div style="background-color: white; padding: 15px; border-radius: 5px; text-align: center;">
                    <h4 style="margin: 0; color: #2196F3;">Chiffre d'affaires</h4>
                    <p style="font-size: 24px; font-weight: bold; margin: 10px 0;"><?= number_format($montantTotal, 2) ?>
                        €</p>
                    <small style="color: #666;">(Commandes terminées uniquement)</small>
                </div>
            </div>
        </div>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
    }


    public function afficherClients($clients, $search = null)
    {
        $this->afficherHeader("Recherche client");
        $this->afficherMenu();
        ?>
        <h2>Recherche client</h2>
        <form method="get">
            <input type="hidden" name="action" value="rechercherClient">
            <input type="text" name="search" placeholder="Nom, prénom ou ID"
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>

        <?php if ($search): ?>
        <?php if (empty($clients)): ?>
            <p>Aucun client trouvé.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Solde</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['id']) ?></td>
                        <td><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['prenom']) ?></td>
                        <td><?= htmlspecialchars($c['solde']) ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
    }


    public function afficherDetailCommande($commande, $produits)
    {
        $this->afficherHeader("Détail commande");
        $this->afficherMenu();
        ?>
        <h2>Détail de la commande</h2>
        <?php if (!$commande): ?>
        <p>Commande introuvable.</p>
    <?php else:
        $datetime = new DateTime($commande['date_vente']);
        ?>
        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #4CAF50;">
            <h3 style="margin-top: 0;">Commande n°<?= htmlspecialchars($commande['commande_id']) ?></h3>
            <p><strong>Client :</strong> <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></p>
            <p><strong>Date/Heure :</strong> <?= $datetime->format('d/m/Y à H:i') ?></p>
            <p><strong>Montant total :</strong> <span
                        style="font-size: 20px; color: #4CAF50; font-weight: bold;"><?= htmlspecialchars(number_format($commande['montant_total'], 2)) ?> €</span>
            </p>
        </div>

        <h3>📦 Liste des produits</h3>
        <?php if (empty($produits)): ?>
        <p>Aucun produit dans cette commande.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th style="text-align: center;">Quantité</th>
                <th style="text-align: right;">Prix unitaire</th>
                <th style="text-align: right;">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($produits as $p):
                $sousTotal = $p['quantite'] * $p['prix_unitaire'];
                $total += $sousTotal;
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                    <td style="text-align: center; font-size: 18px;">
                        <strong><?= htmlspecialchars($p['quantite']) ?></strong></td>
                    <td style="text-align: right;"><?= htmlspecialchars(number_format($p['prix_unitaire'], 2)) ?> €</td>
                    <td style="text-align: right; font-weight: bold;"><?= htmlspecialchars(number_format($sousTotal, 2)) ?>
                        €
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="3" style="text-align: right; padding: 15px;">TOTAL :</td>
                <td style="text-align: right; font-size: 18px; color: #4CAF50; padding: 15px;"><?= htmlspecialchars(number_format($total, 2)) ?>
                    €
                </td>
            </tr>
            </tbody>
        </table>
    <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="index.php?action=commandesEnCours" class="btn btn-secondary">← Retour aux commandes</a>
        </div>
    <?php endif; ?>
        <?php
        $this->afficherFooter();
    }

    public function afficherErreur($message)
    {
        $this->afficherHeader("Erreur");
        $this->afficherMenu();
        ?>
        <div class="erreur">
            <strong>Erreur:</strong> <?= htmlspecialchars($message) ?>
        </div>
        <?php
        $this->afficherFooter();
    }

    public function afficherFormTransaction($produits = [], $erreur = null, $donneesSaisies = null)
    {
        $this->afficherHeader("Nouvelle transaction");
        $this->afficherMenu();
        ?>
        <h2>Créer une transaction</h2>

        <?php if ($erreur): ?>
        <div class="erreur">
            <strong>Erreur:</strong> <?= $erreur ?>
        </div>
    <?php endif; ?>

        <form method="post" action="index.php" onsubmit="return validerFormulaire()">
            <input type="hidden" name="action" value="traiterTransaction">

            <div style="margin-bottom: 15px;">
                <label for="client_id"><strong>ID Client:</strong></label><br>
                <input type="number" name="client_id" id="client_id"
                       value="<?= htmlspecialchars($donneesSaisies['client_id'] ?? '') ?>"
                       min="1" required style="width: 200px;">
            </div>

            <div style="margin-bottom: 15px;">
                <h3>Produits</h3>
                <div id="produits-container">
                    <?php
                    $produits_saisis = $donneesSaisies['produits'] ?? [['id' => '', 'quantite' => '1']];
                    foreach ($produits_saisis as $index => $produit_saisi):
                        ?>
                        <div class="produit-row">
                            <label>Produit:</label><br>
                            <select name="produits[<?= $index ?>][id]" required style="width: 300px;">
                                <option value="">-- Sélectionner un produit --</option>
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= $produit['id'] ?>"
                                        <?= ($produit_saisi['id'] ?? '') == $produit['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($produit['nom']) ?> - <?= $produit['prix'] ?> €
                                        (Stock: <?= $produit['disponibilite'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select><br>

                            <label>Quantité:</label><br>
                            <input type="number" name="produits[<?= $index ?>][quantite]"
                                   value="<?= htmlspecialchars($produit_saisi['quantite'] ?? '1') ?>"
                                   min="1" required style="width: 100px;">

                            <?php if ($index > 0): ?>
                                <button type="button" class="btn btn-danger" onclick="supprimerProduit(this)">
                                    Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-secondary" onclick="ajouterProduit()">Ajouter un produit</button>
            </div>

            <button type="submit" class="btn btn-primary">Créer la transaction</button>
        </form>

        <script>
            let produitsDisponibles = <?= json_encode($produits) ?>;
            let compteurProduit = <?= count($produits_saisis) ?>;

            function ajouterProduit() {
                const container = document.getElementById("produits-container");
                const div = document.createElement("div");
                div.className = "produit-row";

                let html = '<label>Produit:</label><br>';
                html += '<select name="produits[' + compteurProduit + '][id]" required style="width: 300px;">';
                html += '<option value="">-- Sélectionner un produit --</option>';

                produitsDisponibles.forEach(function (produit) {
                    html += '<option value="' + produit.id + '">' +
                        produit.nom + ' - ' + produit.prix + ' € (Stock: ' + produit.disponibilite + ')</option>';
                });

                html += '</select><br>';
                html += '<label>Quantité:</label><br>';
                html += '<input type="number" name="produits[' + compteurProduit + '][quantite]" value="1" min="1" required style="width: 100px;">';
                html += ' <button type="button" class="btn btn-danger" onclick="supprimerProduit(this)">Supprimer</button>';

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
                    alert("L'ID client doit être un nombre positif.");
                    return false;
                }

                const selectsProduits = document.querySelectorAll("select[name^='produits[']");
                let produitIds = [];

                for (let select of selectsProduits) {
                    if (!select.value) {
                        alert("Tous les produits doivent être sélectionnés.");
                        return false;
                    }

                    if (produitIds.includes(select.value)) {
                        alert("Un produit ne peut pas être ajouté deux fois.");
                        return false;
                    }
                    produitIds.push(select.value);

                    const quantiteInput = select.parentElement.querySelector("input[type='number']");
                    if (quantiteInput.value <= 0) {
                        alert("La quantité doit être au moins de 1.");
                        return false;
                    }

                    const produitInfo = produitsDisponibles.find(p => p.id == select.value);
                    if (produitInfo && quantiteInput.value > produitInfo.disponibilite) {
                        alert("Stock insuffisant pour \"" + produitInfo.nom + "\". Stock disponible: " + produitInfo.disponibilite);
                        return false;
                    }
                }

                return confirm("Êtes-vous sûr de vouloir créer cette transaction ?");
            }
        </script>
        <?php
        $this->afficherFooter();
    }

    public function afficherResultatTransaction($vente_id)
    {
        $this->afficherHeader("Transaction réussie");
        $this->afficherMenu();
        ?>
        <h2>Transaction créée avec succès !</h2>
        <div class="succes">
            <h3>Transaction #<?= htmlspecialchars($vente_id) ?></h3>
            <p><strong>Numéro de transaction:</strong> <?= htmlspecialchars($vente_id) ?></p>
            <p><strong>Statut:</strong> Transaction créée avec succès</p>
        </div>

        <p>La transaction a été enregistrée sous le numéro: <strong><?= htmlspecialchars($vente_id) ?></strong></p>
        <p>Le solde du client a été débité et les stocks ont été mis à jour.</p>

        <div style="margin-top: 20px;">
            <a href="index.php?action=creerTransaction" class="btn btn-primary">Nouvelle transaction</a>
            <a href="index.php?action=detailCommande&id=<?= $vente_id ?>" class="btn btn-secondary">Voir les détails</a>
        </div>
        <?php
        $this->afficherFooter();
    }
}