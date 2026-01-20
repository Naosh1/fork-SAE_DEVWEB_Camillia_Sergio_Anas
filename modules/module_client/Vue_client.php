<?php
include_once 'vue_generique.php';
include "modules/module_commun/vue_commun.php";

class Vue_client extends VueCommun {
    public function __construct() {

    }

    public function form_inscription() {
        ob_start();

        echo '<form method="post" action="index.php?module=client&action=ajout_utilisateur"> <br>';
        echo    'Nom : ' . '<input type="text" name="nomUtilisateur" required> <br>';
        echo    'Prenom : ' . '<input type="text" name="prenomUtilisateur" required> <br>';
        echo    'Email : ' . '<input type="email" name="emailUtilisateur" pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Entrez une adresse email valide (ex : nom@gmail.com)" required> <br>';
        echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateur" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}" title="Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre" required> <br>';
        echo    'Confirmez le MDP : ' . '<input type="password" name="mdpUtilisateurConfirmation" required> <br>';
        echo    'Solde : ' . '<input type="number" value="0" name="soldeUtilisateur" required> <br>';
        echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
        echo '</form>';

        return ob_get_clean();
    }

    public function form_connexion() {
        ob_start();

        echo '<form method="post" action="index.php?module=client&action=verif_connexion">';
        echo    'Email : ' . '<input type="email"  name="emailUtilisateurConnexion" placeholder="votreEmail@gmail.com" required> <br>';
        echo    'Mot de passe : ' . '<input type="password" name="mdpUtilisateurConnexion" required> <br>';
        echo    '<input type="submit" name="bouton" value="Inscription"> <br>';
        echo '</form>';

        return ob_get_clean();
    }

    public function form_modification() {
        ob_start();

        echo '<form method="post" action="index.php?module=client&action=verif_modification"> <br>';
        echo    'Nouvelle Email : ' . '<input type="email" name="nvEmailUtilisateur" pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Entrez une adresse email valide (ex : nom@gmail.com)" required> <br>';
        echo    'Nouveau Mot de passe : ' . '<input type="password" name="nvMdpUtilisateur" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}" title="Doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre" required> <br>';
        echo    'Confirmez le MDP : ' . '<input type="password" name="nvMdpUtilisateurConfirmation" required> <br>';
        echo    '<input type="submit" name="bouton" value="Modifier"> <br>';
        echo '</form>';

        return ob_get_clean();
    }

    public function form_comptePasBonLogin() {
        ob_start();

        echo "Le login est deja utilisé !!!";

        return ob_get_clean();
    }

    public function form_compteBon() {
        ob_start();

        echo "Utilisateur entré avec succés !!!";

        return ob_get_clean();
    }

    public function form_mdpPasBon() {
        ob_start();

        echo "Le mdp n'est pas le meme !!! Recommencer";

        return ob_get_clean();
    }

    public function form_connexionReussie() {
        ob_start();

        echo "Bienvenue " . htmlspecialchars($_SESSION['prenom']) . "<br>";

        return ob_get_clean();
    }

    public function form_connexionPasBon() {
        ob_start();

        echo "Login ou mot de passe incorrect" . "<br>";

        return ob_get_clean();
    }

    public function form_personneEstConnectee() {
        ob_start();

        echo "Personne est connectée !" . "<br>";

        return ob_get_clean();
    }

    public function form_emailDejaUtilise() {
        ob_start();

        echo "Le mail est déja utilisée" . "<br>";

        return ob_get_clean();
    }

    public function form_modificationReussie() {
        ob_start();

        echo "Modification des infos réussie avec succés !" . "<br>";

        return ob_get_clean();
    }

    public function form_dejaConnecte() {
        ob_start();

        echo "Vous êtes déjà connecté(e)" . "<br>";

        return ob_get_clean();
    }

    public function form_deconnexion() {
        ob_start();

        echo '<a href="index.php?module=client&action=deconnexion">Se déconnecter</a>';

        return ob_get_clean();
    }

    public function form_deconnexionReussie() {
        ob_start();

        echo 'Vous êtes déconnecter !';

        return ob_get_clean();
    }

    public function form_rechargement() {
        ob_start();

        echo '<form method="post" action="index.php?module=client&action=verif_rechargement"> <br>';
        echo    'Montant à recharger : ' . '<input type="number" name="montant" min="1" max="40" step="1" required> <br>';
        echo    'Nom du titulaire : ' . '<input type="text" inputmode="text" name="nomTitulaire" pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s-]+" title="Uniquement des lettres" required><br>';
        echo    'Numero de carte : ' . '<input type="text" inputmode="numeric" name="codeCarteUtilisateur" pattern="[0-9]{16}" maxlength="16" placeholder="XXXX-XXXX-XXXX-XXXX" title="Coordonnées numerique sans espace" required> <br>';
        echo    'CVV : ' . '<input type="text" inputmode="numeric" name="cvvUtilisateur" pattern="[0-9]{3}" maxlength="3" placeholder="123" title="3 chiffres" required> <br>';
        echo    'Date d\'expiration : ' . '<input type="text" inputmode="numeric" name="dateExpirationUtilisateur" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" placeholder="MM/AA" title="Entrez une date correcte" required> <br>';
        echo    '<input type="submit" name="bouton" value="Payer"> <br>';
        echo '</form>';

        return ob_get_clean();
    }

    public function form_plus() {
        ob_start();
        ?>
        <nav class="nav-user">
            <ul>
                <li> <a href='index.php?module=client&action=form_modification_utilisateur'> Modifier mes infos </a> </li>
                <li> <a href='index.php?module=client&action=deconnexion'> Déconnexion </a> </li>
            </ul>
        </nav>
        <?php
        return ob_get_clean();
    }

    public function form_rechargementReussi() {
        ob_start();

        echo "Rechargement réussie !";

        return ob_get_clean();
    }

    public function form_demandeConnexion() {
        ob_start();

        echo "Veuillez vous connecter !";

        return ob_get_clean();
    }

    public function form_espace($solde, $historique) {
        $this->afficherNav();
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
        return ob_get_clean();
    }

    public function form_liste_produits($produits) {
        ob_start();
        $this->afficherNav();
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
                                <input type="submit" value="Ajouter">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }

    public function form_panier_utilisateur($panier_details, $total_general) {
        ob_start();
        $this->afficherNav();
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
                            <td>
                                <form method="post" action="index.php?module=client&action=enlever_panier">
                                    <input type="hidden" name="idProduit" value="<?= (int)$item['id'] ?>">
                                    <input type="submit" value="Enlever">
                                </form>
                            </td>
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
        return ob_get_clean();
    }

    public function afficherNav() {
        ob_start();
        ?>
        <nav>
            <?php if (isset($_SESSION['id']) && $_SESSION['role'] === 'SuperAdmin'): ?>
                <a href='index.php?module=client&action=gestion'> Gestion </a>
                <a href='index.php?module=client&action=espace'> Espace Personnel </a>
                <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
            <?php elseif (isset($_SESSION['id'])): ?>
                <a href='index.php?module=client&action=espace'> Espace Personnel </a>
                <a href='index.php?module=client&action=form_rechargement_utilisateur'> Rechargement </a>
                <a href='index.php?module=client&action=form_produits_utilisateur'> Produits </a>
                <a href='index.php?module=client&action=form_plus_utilisateur'> Plus </a>
                <a class="panier" href='index.php?module=client&action=form_panier_utilisateur'> Panier </a>
                <a class="panier" href='index.php?module=client&action=form_commande_statut_panier_utilisateur'> Suivi Commandes </a>
            <?php else: ?>
                <a href='index.php?module=client&action=accueil'> Accueil </a>
                <a href='index.php?module=client&action=form_inscription_utilisateur'> S'inscrire </a>
                <a href='index.php?module=client&action=form_connexion_utilisateur'> Connexion </a>
            <?php endif; ?>
        </nav>
        <?php
        return ob_get_clean();
    }

    public function form_commande_statut_panier($commandes)
    {
        ob_start();
        ?>

        <h2>Statut de mes commandes</h2>

        <?php if (empty($commandes)): ?>
        <p>Aucune commande pour le moment.</p>
        <?php else: ?>
        <table class="table-commandes">
            <thead>
            <tr>
                <th>Commande n°</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td>#<?= (int)$commande['vente_id'] ?></td>
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
        return ob_get_clean();
    }

}