<?php
    include_once 'vue_generique.php';
    include "modules/module_commun/vue_commun.php";

class Vue_client extends VueCommun {

    public function __construct() {

    }

    public function afficherFooter() {
       ob_start();
       ?>
           <footer>
              <p>Copyright Buvette du 93 &copy; Tous droits réservés</p>
           </footer>
       <?php
       return ob_get_clean();
    }

    public function afficherNav() {
        ob_start();
        ?>
        <link rel="stylesheet" href="style.css">

        <header>
            <section class="section-white">
                <div class="card">
                    <h1><?= htmlspecialchars($_SESSION['nom_buvette'] ?? 'Buvette') ?></h1>
                        <nav>
                            <?php if (isset($_SESSION['id'])): ?>

                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="index.php?module=client&action=gestion">
                                        Gestion
                                    </a>
                                <?php endif; ?>

                                <?php if ($_SESSION['role'] === 'barman'): ?>
                                    <a href="index.php?module=barman&action=creerTransaction">
                                        Vendre
                                    </a>
                                    <a href="index.php?module=barman&action=commandesEnCours">
                                        Commandes
                                    </a>
                                <?php endif; ?>

                                <a href="index.php?module=client&action=espace">
                                    Mon Espace
                                </a>

                                <a href="index.php?module=client&action=form_rechargement_utilisateur">
                                    Recharger
                                </a>

                                <a href="index.php?module=client&action=form_produits_utilisateur">
                                    Produits
                                </a>

                                <a href="index.php?module=client&action=form_historique_utilisateur">
                                     Historique
                                </a>

                                <a href="index.php?module=client&action=form_plus_utilisateur">
                                      Plus
                                </a>

                                <a href="?reset=1">
                                    Changer d'association
                                </a>

                                <a href="index.php?module=client&action=form_panier_utilisateur" class="panier">
                                       Panier
                                </a>

                                <a href="index.php?module=client&action=form_commande_statut_panier_utilisateur" class="panier">
                                    Suivi Commandes
                                </a>

                            <?php else: ?>
                                <a href="index.php?module=client&action=accueil" class="nav-item">
                                    <i class="fa-solid fa-house"></i> Accueil
                                </a>
                                <a href="index.php?module=client&action=form_inscription_utilisateur" class="nav-item">
                                    <i class="fa-solid fa-user-plus"></i> S'inscrire
                                </a>
                                <a href="index.php?module=client&action=form_connexion_utilisateur" class="nav-item nav-btn">
                                    <i class="fa-solid fa-key"></i> Connexion
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
            </section>
        </header>
        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_modification() {
        ob_start();
        echo $this->afficherNav();

        echo '<form method="post" action="index.php?module=client&action=verif_modification"> <br>';
        echo    'Nouvelle Email : ' . '<input type="email" name="nvEmailUtilisateur" pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Entrez une adresse email valide (ex : nom@gmail.com)" required> <br>';
        echo    'Nouveau Mot de passe : ' . '<input type="password" name="nvMdpUtilisateur" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}" title="Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre" required> <br>';
        echo    'Confirmez le MDP : ' . '<input type="password" name="nvMdpUtilisateurConfirmation" required> <br>';
        echo    '<input type="submit" name="bouton" value="Modifier"> <br>';
        echo '</form>';

        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_modificationReussie() {
        ob_start();

        echo "Modification des infos réussie avec succés !" . "<br>";

        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_deconnexionReussie() {
        ob_start();

        echo 'Vous êtes déconnecter !';

        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_rechargement() {
        ob_start();
        echo $this->afficherNav();

        echo '<form method="post" action="index.php?module=client&action=verif_rechargement"> <br>';
        echo    'Montant à recharger : ' . '<input type="number" name="montant" min="1" max="40" step="1" required> <br>';
        echo    'Nom du titulaire : ' . '<input type="text" inputmode="text" name="nomTitulaire" pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s-]+" title="Uniquement des lettres" required><br>';
        echo    'Numero de carte : ' . '<input type="text" inputmode="numeric" name="codeCarteUtilisateur" pattern="[0-9]{16}" maxlength="16" placeholder="XXXX-XXXX-XXXX-XXXX" title="Coordonnées numerique sans espace" required> <br>';
        echo    'CVV : ' . '<input type="text" inputmode="numeric" name="cvvUtilisateur" pattern="[0-9]{3}" maxlength="3" placeholder="123" title="3 chiffres" required> <br>';
        echo    'Date d\'expiration : ' . '<input type="text" inputmode="numeric" name="dateExpirationUtilisateur" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" placeholder="MM/AA" title="Entrez une date correcte" required> <br>';
        echo    '<input type="submit" name="bouton" value="Payer"> <br>';
        echo '</form>';

        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_plus() {
        ob_start();
        echo $this->afficherNav();
        ?>
        <nav class="nav-user">
            <ul>
                <li> <a href='index.php?module=client&action=form_modification_utilisateur'> Modifier mes infos </a> </li>
                <li> <a href='index.php?module=client&action=deconnexion'> Se deconnecter </a> </li>
            </ul>
        </nav>
        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_rechargementReussi() {
        ob_start();

        echo "Rechargement réussie !";

        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_espace($solde, $historique) {
        ob_start();
        echo $this->afficherNav();
        ?>
        <div class="bfor-card">
            <div class="bfor-top">
                <div class="bfor-bank">SOLDE</div>
                <div class="bfor-badge">VIRTUELLE</div>
            </div>

            <div class="bfor-account">
                <p>Mon compte</p>
                <h1>
                    <?php
                    echo htmlspecialchars(number_format($solde,2));
                    ?>
                </h1>
            </div>
        </div>

        <br>

        <div class="bfor-card">
            <div class="bfor-top">
                <div class="bfor-bank">RECHARGEMENT</div>
                <div class="bfor-badge">VIRTUELLE</div>
            </div>

            <div class="bfor-account">
                <?php if (empty($historique)): ?>
                    <br>
                    <p>Aucun rechargement effectué.</p>
                <?php else: ?>
                    <table>
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($historique as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['date_rechargement']) ?></td>
                                <td><?= htmlspecialchars(number_format($r['valeur'], 2)) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_liste_produits($produits) {
        ob_start();
        echo $this->afficherNav();
        ?>

        <h2>Produits disponibles</h2>

        <?php if (empty($produits)): ?>
            <p>Aucun produit disponible pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Prix (€)</th>
                    <th>Stock</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($produits as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['type']) ?></td>
                        <td><?= number_format($p['prix'], 2) ?> €</td>
                        <td><?= (int)$p['quantiteActuelle'] ?></td>
                        <td>
                            <form method="post" action="index.php?module=client&action=ajouter_panier">
                                <input type="hidden" name="idProduit" value="<?= (int)$p['id'] ?>">
                                <button type="submit"> Ajouter </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_panier_utilisateur($panier_details, $total_general) {
        ob_start();
        echo $this->afficherNav();
        ?>
        <main>
            <h2> Votre Panier</h2>

            <?php if (empty($panier_details)): ?>
                <div class="card" style="text-align: center;">
                    <p>Votre panier est vide.</p>
                    <nav><a href="index.php?module=client&action=form_produits_utilisateur">Voir les produits</a></nav>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($panier_details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nom']) ?></td>
                            <td><?= number_format($item['prix'], 2) ?> €</td>
                            <td>x <?= (int)$item['qte'] ?></td>
                            <td><strong><?= number_format($item['sous_total'], 2) ?> €</strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="bfor-card" style="margin-top: 30px;">
                    <div class="bfor-account">
                        <p>TOTAL À RÉGLER</p>
                        <h1><?= number_format($total_general, 2) ?> €</h1>
                    </div>

                    <form method="post" action="index.php?module=client&action=valider_commande" style="background:none; border:none; padding:0; box-shadow:none;">
                        <input type="submit" value="Valider la commande">
                    </form>
                </div>
            <?php endif; ?>
        </main>
        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_commande_statut_panier($commandes)
    {
        ob_start();
        echo $this->afficherNav();
        ?>

        <h2>Statut de mes commandes</h2>

        <?php if (empty($commandes)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
        <table class="table-commandes">
            <thead>
            <tr>
                <th>Commande n°</th>
                <th>Association</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td>#<?= (int)$commande['vente_id'] ?></td>
                    <td><?= $commande['nom_association']?></td>
                    <td>
                        <?php if ($commande['statut'] === 'validée'): ?>
                            <span class="statut validee">Validée</span>
                        <?php else: ?>
                            <span class="statut attente">En attente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post" action="index.php?module=client&action=enlever_commande" style="background:none; border:none; padding:0; box-shadow:none;">
                            <input type="hidden" name="vente_id" value="<?= (int)$commande['vente_id'] ?>">
                            <input type="submit" value="Enlever">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }

    public function form_historique($commandes)
    {
        ob_start();
        echo $this->afficherNav();
        ?>

        <h2>Mon historique</h2>

        <?php if (empty($commandes)): ?>
        <p>Pas de commandes faites !</p>
    <?php else: ?>
        <table class="table-commandes">
            <thead>
            <tr>
                <th>Commande n°</th>
                <th>Association</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Produits</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td>#<?= $commande['vente_id'] ?></td>
                    <td><?= $commande['nomAssociation']?></td>
                    <td><?= $commande['date'] ?></td>
                    <td><?= number_format($commande['montant'], 2) ?> €</td>
                    <td>
                        <ul>
                            <?php foreach ($commande['produits'] as $p): ?>
                                <li>
                                    <?= $p['nom'] ?> × <?= $p['quantite'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

        <?php
        echo $this->afficherFooter();
        return ob_get_clean();
    }
}