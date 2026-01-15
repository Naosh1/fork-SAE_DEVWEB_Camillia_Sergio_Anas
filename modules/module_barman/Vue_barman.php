<?php

class Vue_barman {

    public function afficherAccueil() {
        ob_start();
        echo "Accueil barman";
        return ob_get_clean();
    }

    public function afficherDerniereTransaction($transaction) {
        ob_start();
        ?>

        <main>
            <?php if (!$transaction): ?>

                <div class="card">
                    <h2>Aucune transaction</h2>
                    <p>Aucune transaction récente n’a été trouvée.</p>
                </div>

            <?php else:
                $isAnnulee = ($transaction['statut'] ?? 'payee') === 'annulee';
            ?>

                <div class="card">
                    <h2>Détails de la transaction</h2>

                    <p><strong>Numéro :</strong> #<?= htmlspecialchars($transaction['transaction_id']) ?></p>
                    <p><strong>Date :</strong> <?= htmlspecialchars($transaction['date_vente']) ?></p>
                    <p>
                        <strong>Client :</strong>
                        <?= htmlspecialchars($transaction['prenom'] . ' ' . $transaction['nom']) ?>
                    </p>
                    <p>
                        <strong>Montant total :</strong>
                        <?= number_format($transaction['montant_total'], 2) ?> €
                    </p>

                    <p>
                        <strong>Statut :</strong>
                        <?= $isAnnulee ? 'Annulée' : 'Confirmée' ?>
                    </p>
                </div>

                <?php if (!empty($transaction['produits'])): ?>

                    <div class="card">
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
                                        <td><?= (int)$produit['quantite'] ?></td>
                                        <td><?= number_format($produit['prix_unitaire'], 2) ?> €</td>
                                        <td>
                                            <?= number_format(
                                                $produit['quantite'] * $produit['prix_unitaire'],
                                                2
                                            ) ?> €
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

                <?php if (!$isAnnulee): ?>
                    <div class="card">
                        <h3>Action</h3>

                        <form method="post" action="index.php"
                              onsubmit="return confirm('Voulez-vous vraiment annuler cette transaction ?')">
                            <input type="hidden" name="action" value="annulerTransaction">
                            <input type="hidden" name="transaction_id"
                                   value="<?= htmlspecialchars($transaction['transaction_id']) ?>">

                            <input type="submit" value="Annuler la transaction">
                        </form>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </main>
        <?php

        return ob_get_clean();
    }


    public function afficherConfirmationAnnulation($transaction_id) {
        ob_start();
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
        return ob_get_clean();
    }

    public function afficherHistoriqueCommandes($commandes) {
        ob_start();
       ?>

       <h2>Historique des commandes</h2>

       <?php if (empty($commandes)): ?>
           <p>Aucune commande enregistrée.</p>
       <?php else: ?>
          <table>
             <thead>
                <tr>
                   <th>ID</th>
                      <th>Date</th>
                      <th>Client</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      </tr>
             </thead>
               <tbody>
                  <?php foreach ($commandes as $c): ?>
                      <tr>
                         <td><?= htmlspecialchars($c['commande_id']) ?></td>
                         <td><?= htmlspecialchars($c['date_heure_affichage']) ?></td>
                         <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
                         <td><?= number_format($c['montant_total'], 2) ?> €</td>
                         <td><?= htmlspecialchars($c['statut']) ?></td>
                      </tr>
                  <?php endforeach; ?>
               </tbody>
          </table>
       <?php endif; ?>
       <?php

        return ob_get_clean();
    }

    public function afficherProduits($produits) {
        ob_start();
        ?>

        <h2>Liste des produits</h2>

        <?php if (empty($produits)): ?>
            <p>Aucun produit disponible.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= number_format($p['prix'], 2) ?> €</td>
                            <td><?= (int)$p['disponibilite'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }

    public function afficherClients($clients) {
        ob_start();
        ?>

        <h2>Clients</h2>

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
                            <td>#<?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['prenom']) ?></td>
                            <td><?= number_format($c['solde'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }

    public function afficherCommandes($commandes) {
        ob_start();
        ?>

        <h2>Commandes en attente</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande en cours.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Heure</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td>#<?= $c['commande_id'] ?></td>
                            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['heure']) ?></td>
                            <td><?= number_format($c['montant_total'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }

    public function afficherDetailCommande($commande, $produits) {
        ob_start();
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
    }

    public function afficherErreur($message) {
        ?>
        <div class="erreur">
            <strong>Erreur:</strong> <?= htmlspecialchars($message) ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function afficherFormTransaction($produits = [], $erreur = null, $donneesSaisies = null) {
        ob_start();
        ?>

        <h2>Nouvelle vente</h2>

        <?php if ($erreur): ?>
            <div class="card">
                <p style="color:red; font-weight:bold;">
                    Erreur : <?= htmlspecialchars($erreur) ?>
                </p>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php" onsubmit="return validerFormulaire()">
            <input type="hidden" name="action" value="traiterTransaction">

            <!-- Client -->
            <label>ID Client</label>
            <input type="number"
                   name="client_id"
                   value="<?= htmlspecialchars($donneesSaisies['client_id'] ?? '') ?>"
                   min="1"
                   required>

            <h3>Produits</h3>

            <div id="produits-container">
                <?php
                $produits_saisis = $donneesSaisies['produits'] ?? [['id' => '', 'quantite' => 1]];
                foreach ($produits_saisis as $index => $produit_saisi):
                ?>
                    <div class="card produit-row">
                        <select name="produits[<?= $index ?>][id]" required>
                            <option value="">-- Sélectionner un produit --</option>
                            <?php foreach ($produits as $p): ?>
                                <option value="<?= $p['id'] ?>"
                                    <?= ($produit_saisi['id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nom']) ?> (<?= number_format($p['prix'], 2) ?> €)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="number"
                               name="produits[<?= $index ?>][quantite]"
                               value="<?= htmlspecialchars($produit_saisi['quantite'] ?? 1) ?>"
                               min="1"
                               required>

                        <?php if ($index > 0): ?>
                            <button type="button" onclick="supprimerProduit(this)">
                                Supprimer
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="ajouterProduit()">Ajouter un produit</button>

            <input type="submit" value="Encaisser">
        </form>

        <script>
            let produitsDisponibles = <?= json_encode($produits) ?>;
            let compteurProduit = <?= count($produits_saisis) ?>;

            function ajouterProduit() {
                const container = document.getElementById("produits-container");
                const div = document.createElement("div");
                div.className = "card produit-row";

                let options = '<option value="">-- Sélectionner un produit --</option>';
                produitsDisponibles.forEach(p => {
                    options += `<option value="${p.id}">${p.nom} (${parseFloat(p.prix).toFixed(2)} €)</option>`;
                });

                div.innerHTML = `
                    <select name="produits[${compteurProduit}][id]" required>
                        ${options}
                    </select>

                    <input type="number"
                           name="produits[${compteurProduit}][quantite]"
                           value="1"
                           min="1"
                           required>

                    <button type="button" onclick="supprimerProduit(this)">
                        Supprimer
                    </button>
                `;

                container.appendChild(div);
                compteurProduit++;
            }

            function supprimerProduit(btn) {
                btn.closest('.produit-row').remove();
            }

            function validerFormulaire() {
                const selects = document.querySelectorAll('select[name^="produits"]');
                let ok = false;

                selects.forEach(s => {
                    if (s.value !== "") ok = true;
                });

                if (!ok) {
                    alert("Veuillez sélectionner au moins un produit.");
                    return false;
                }

                return confirm("Confirmer la vente ?");
            }
        </script>

        <?php
        return ob_get_clean();
    }

    public function afficherResultatTransaction($vente_id) {
        ob_start();
            ?>

            <div class="card">
                <h2>Transaction validée</h2>

                <p>
                    La transaction <strong>#<?= htmlspecialchars($vente_id) ?></strong> a été enregistrée avec succès.
                </p>

                <p>
                    Le solde du client a été débité et les stocks ont été mis à jour.
                </p>

                <nav class="nav-user">
                    <ul>
                        <li>
                            <a href="index.php?action=creerTransaction">
                                Nouvelle transaction
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=detailCommande&id=<?= $vente_id ?>">
                                Voir les détails
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        <?php
        return ob_get_clean();
    }
}