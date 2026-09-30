# Rapport d'audit de sécurité

> Audit **statique** du code (aucune exécution dynamique, aucune exploitation). Chaque faille retenue est
> accompagnée d'un test défensif automatisé (`tests/security/run.php`) dont la sortie réelle est jointe.
> Périmètres examinés un à un : Authentification/routage, Espace client, Gestion d'association, Configuration/fichiers.
> **Non audités en profondeur** : modules barman, admin et staff. Aucun secret réel n'est reproduit dans ce rapport.

## 1. Méthode et limites
- Lecture ciblée du code (`grep` puis lecture des fonctions concernées), sans lire `scriptBD/`, `uploads/` ni dépendances.
- Aucun `composer.json`/`package.json` : l'audit des dépendances se limite aux ressources chargées par CDN.
- MySQL n'est pas disponible dans l'environnement : **aucun test dynamique** n'a été fait. Le statut « confirmée » signifie ici :
  *présence vérifiée dans le code source + test défensif en échec*. L'exploitabilité réelle reste à valider par le groupe en environnement de TP.
- Les tests vérifient qu'une **protection attendue est présente** : FAIL = protection absente. Les contrôles positifs (`P-xx`) prouvent que le
  harnais sait aussi conclure « sain ».

## 2. Cartographie de la surface d'attaque
| Domaine | Entrées utilisateur | Fichiers / lignes |
| --- | --- | --- |
| Authentification | `POST email/mdp` ; inscription `POST nom, prenom, email, mdp, date_naissance` ; session PHP | `templates/connexion.php:1-49`, `templates/inscription.php:1-62`, `index.php:2` |
| Routage / portail | `POST rejoindre_asso`, `POST choisir_asso`, `GET search`, `GET action=deconnexion`, `GET reset` | `index.php:46-140` |
| Espace client | `POST idProduit, quantite, montant, vente_id` via `?action=` | `modules/module_client/Controleur_client.php:30-218`, `commun/modele/*Acces.php` |
| Gestion d'association | `GET/POST id, id_asso, association_id, id_client…` via `?action=` ; 2 téléversements | `modules/module_gestionnaire/Controleur_gestionnaire.php` (≈1100 lignes) |
| Base de données | PDO, requêtes préparées | `connexion/Connexion.php`, `*/Modele_*.php` |
| Fichiers | `uploads/dossiers_assos/`, `uploads/profiles/` | `Controleur_gestionnaire.php:296-337, 587-608` |
| Configuration | debug, cookies de session, identifiants BD, CDN | `index.php:3-4`, `connexion/Connexion.php:4-17` |
| Appels externes | CDN Tailwind, Font Awesome, Tom Select, Google Fonts (sans SRI) | `Vue_*.php`, `templates/*.php` |

## 3. Failles confirmées (statique + test défensif en échec)
Auteur de toutes les découvertes ci-dessous : **Claude (IA)**, à relire par le groupe.

### S-01 — Rechargement de solde sans paiement ni contrôle (Critique)
- **Domaine** : Espace client · **Fichiers** : `Controleur_client.php:135-141`, `commun/modele/CompteAcces.php:47-56`
```php
$montant = $_POST['montant'] ?? 0;
if ($montant > 0) { $this->modeleCompte->recharger_solde($_SESSION['id'], $montant); }
```
- **Scénario** : tout client connecté envoie un montant positif de son choix ; son solde est crédité, sans plafond ni validation de paiement ni de rôle.
- **Impact** : création d'argent fictif, contournement de toute la logique de vente de la buvette. Lecture-modification-écriture non atomique (course possible).
- **Gravité** : Critique (intégrité financière, exploitable par n'importe quel compte). Réserve : si le rechargement « libre » est une simulation voulue pour le TP, l'intention métier est à confirmer.
- **Preuve** : test `S-01` en échec (voir §6). **Statut** : confirmée.

### S-02 — Suppression d'un compte quelconque via l'action `supprimerBarman` (Critique)
- **Fichiers** : `Controleur_gestionnaire.php:98-99, 734-737`, `Modele_gestionnaire.php:579-591`
```php
$success = $this->modele->supprimerBarman($_GET['id']);   // ... DELETE FROM compte WHERE id = ?
```
- **Scénario** : un gestionnaire (de n'importe quelle association) fournit l'identifiant d'un autre compte (client, gestionnaire d'une autre association…) ; le compte est supprimé. L'action est en **GET**, donc déclenchable par un lien piégé (voir S-11).
- **Impact** : suppression de comptes arbitraires, perte de données, déni de service applicatif.
- **Gravité** : Critique. Réserve : les clés étrangères peuvent faire échouer la suppression de certains comptes (non testé, pas de MySQL).
- **Preuve** : test `S-02` en échec. **Statut** : confirmée.

### S-03 — Téléversement de la photo de profil sans validation (Haute, Critique si PHP exécutable dans `uploads/`)
- **Fichier** : `Controleur_gestionnaire.php:587-608` (dossier créé en `0777`, ligne 596)
```php
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$nom_image = "pp_" . $id_user . "_" . time() . "." . $ext;
move_uploaded_file($file['tmp_name'], "uploads/profiles/" . $nom_image);
```
- **Scénario** : l'extension et le contenu sont ceux du client ; le nom est prévisible (id + horodatage) et le dossier est servi tel quel (pas de `.htaccess`).
- **Impact** : dépôt de fichiers actifs ou de contenu arbitraire sur le serveur ; exécution de code si le serveur interprète le PHP dans ce dossier.
- **Gravité** : Haute (Critique selon la configuration du serveur, non testée ici). **Preuve** : test `S-03` en échec, `S-08b` en échec. **Statut** : confirmée (absence de validation).

### S-04 — Annulation de la commande d'un autre utilisateur (Haute, IDOR)
- **Fichiers** : `Controleur_client.php:173-177`, `commun/modele/ProduitVenduAcces.php:73-118`
```php
"SELECT produit_id, quantite FROM ligne_vente WHERE vente_id = :vente_id AND statut = 'en_attente'"
```
- **Scénario** : un client modifie `vente_id` dans la requête ; aucune requête ne compare avec `$_SESSION['id']`.
- **Impact** : suppression des commandes en attente d'autrui, remise en stock indue. **Preuve** : test `S-04` en échec. **Statut** : confirmée.

### S-05 — Suppression/modification de produits sans contrôle de propriété (Haute)
- **Fichiers** : `Modele_gestionnaire.php:654-658, 708-714`, `Controleur_gestionnaire.php:45-46, 510-513`
```php
DELETE FROM produit WHERE id = ?      /  UPDATE produit SET ... WHERE id = ?
```
- **Scénario** : un gestionnaire agit sur un produit d'une autre association en changeant `id`. `supprimerProduit` est en GET.
- **Impact** : altération/suppression du catalogue d'autrui. **Preuve** : test `S-05` en échec. **Statut** : confirmée.

### S-06 — Modification du solde de n'importe quel client (Haute)
- **Fichiers** : `Controleur_gestionnaire.php:785-812`, `Modele_gestionnaire.php:720-723`
- **Scénario** : `gererSoldeClient` charge le compte par `id` (GET/POST) sans vérifier son rattachement à l'association du gestionnaire ; le solde peut aussi devenir négatif.
- **Impact** : fraude sur les soldes de comptes d'autres associations. **Preuve** : test `S-06` en échec. **Statut** : confirmée.

### S-07 — PDF de dossier : extension imposée par le client (Moyenne)
- **Fichier** : `Controleur_gestionnaire.php:325-337`. Le type MIME est contrôlé (`finfo`), mais `pathinfo($_FILES[..]['name'], PATHINFO_EXTENSION)` détermine l'extension finale : un contenu commençant par une signature PDF peut être enregistré avec une extension quelconque.
- **Impact** : dépôt de fichier à extension arbitraire. **Preuve** : test `S-07` en échec. **Statut** : confirmée.

### S-08 — Pièces justificatives dans Git et dossier non protégé (Haute)
- **Fichiers** : `uploads/dossiers_assos/*.pdf` (6 fichiers suivis par Git, noms `demande_<id>_CNI|PV|AGO_<uniqid>.pdf`), aucun `uploads/.htaccess`.
- **Scénario** : toute personne ayant accès au dépôt lit des documents (dont des pièces d'identité, `CNI`) ; côté serveur, les noms sont dérivés d'un `uniqid()` et servis sans authentification.
- **Impact** : fuite de données personnelles (RGPD). Le contenu des PDF n'a volontairement pas été ouvert.
- **Preuve** : tests `S-08a` et `S-08b` en échec. **Statut** : confirmée. Une purge de l'historique Git est à envisager (hors périmètre de cette PR).

### S-09 — Identifiants de base de données en clair dans le code (Haute)
- **Fichier** : `connexion/Connexion.php:6-7, 11-12, 16-17` (jeux d'identifiants IUT commentés + jeu actif).
- **Impact** : compromission de la base de l'IUT si les identifiants sont encore valides ; secrets présents dans l'historique Git. Valeurs volontairement **non reproduites**.
- **Recommandation** : rotation des mots de passe concernés, puis chargement par variables d'environnement (`.env.example` existe déjà sur la branche `chore/init-claude`).
- **Preuve** : test `S-09` en échec (5 affectations non vides). **Statut** : confirmée.

### S-10 — XSS stocké dans la recherche d'associations (Moyenne)
- **Fichier** : `index.php:135-136`
```php
<span class='asso-name'>{$asso['nom']}</span> ... <span class='asso-email'>{$asso['email']}</span>
```
- **Scénario** : le nom d'association vient d'un formulaire de demande (`nom_association`, `Controleur_gestionnaire.php:302`) ; s'il contient du HTML, il est rendu chez tous les utilisateurs qui recherchent. L'étape de validation par un administrateur limite l'exposition, sans l'éliminer.
- **Impact** : exécution de script dans le navigateur des visiteurs du portail. **Preuve** : test `S-10` en échec. **Statut** : confirmée.

### S-11 — Aucune protection CSRF, actions destructrices en GET (Moyenne)
- **Fichiers** : aucun jeton dans `index.php` ni `modules/` ; GET : `Controleur_gestionnaire.php:45-46, 98-99, 170-172`.
- **Impact** : un lien ou une image piégée déclenche suppression/modification à l'insu d'un gestionnaire connecté (aggrave S-02 et S-05). **Preuve** : test `S-11` en échec. **Statut** : confirmée.

### S-12 — Affichage des erreurs PHP activé (Moyenne)
- **Fichier** : `index.php:3-4` (`display_errors=1`). Impact : divulgation de chemins et de détails SQL. Test `S-12` en échec. **Statut** : confirmée.

### S-13 — Cookie de session non durci, pas de limitation des tentatives (Basse)
- **Fichiers** : `index.php:2`, `templates/connexion.php:2`. Aucun attribut `HttpOnly`/`SameSite`/`Secure` configuré ; aucun compteur d'échecs de connexion. Test `S-13` en échec. **Statut** : confirmée pour le cookie ; la limitation des tentatives n'est pas testée automatiquement.

### S-14 — Code de validation à 4 chiffres généré avec `rand()` (Basse)
- **Fichier** : `commun/modele/CodeValidationAcces.php:23`. Espace de 10 000 valeurs, générateur non cryptographique, validité 1 minute. Test `S-14` en échec. **Statut** : confirmée (le risque de force brute dépend de `verifierCode`, non audité : voir N-02).

## 4. Non confirmées
| ID | Sujet | Pourquoi non confirmée |
| --- | --- | --- |
| N-01 | `POST rejoindre_asso` (`index.php:59-68`) : tout compte rejoint n'importe quelle association comme client | Peut être le comportement voulu ; à valider fonctionnellement. |
| N-02 | Force brute du code de validation (barman) | Les appelants de `verifierCode` n'ont pas été audités (module barman hors périmètre). |
| N-03 | `getCommandesFournisseursRecentes` / `getHistoriqueAchatsComplet` (`Modele_gestionnaire.php:133-153`) ne filtrent pas par gestionnaire | Les fournisseurs sont peut-être communs ; à confirmer. |
| N-04 | Ressources CDN sans SRI (`cdn.tailwindcss.com`, `cdn.jsdelivr.net/.../tom-select@2.2.2`) | Risque de chaîne d'approvisionnement réel mais faible ; aucune vulnérabilité de version vérifiée (pas de manifeste ni de base de vulnérabilités consultée). |
| N-05 | Modification e-mail/mot de passe sans mot de passe actuel (`CompteAcces.php`) | Début de fonction non relu. |

## 5. Faux positifs écartés
| ID | Piste initiale | Raison |
| --- | --- | --- |
| FP-01 | Injection SQL via `ORDER BY` dynamique (`modele_staff.php:265`) | Valeur issue d'une liste blanche (`$trisAutorises`) — contrôle positif `P-02`. |
| FP-02 | Injection SQL générale | Requêtes préparées partout ; aucune concaténation de GET/POST (contrôle `P-01`). Les `query()` restants n'ont pas de paramètre. |
| FP-03 | XSS `echo $_SESSION["login"]` (`modules/mod_connexion/vue.connexion.php:88`) | Code mort : `mod_connexion` n'est inclus nulle part. |
| FP-04 | Exécution de commande (`CodeValidationAcces.php:18`, `->exec("SET time_zone …")`) | Requête SQL constante, pas une commande système (contrôle `P-05`). |
| FP-05 | XSS dans `Vue_client.php` (nombreux `<?= … ?>` non échappés) | Valeurs numériques formatées (`number_format`, entiers) ; non retenu. |
| FP-06 | Fixation de session | `session_regenerate_id(true)` présent (contrôle `P-04`). |

## 6. Preuves : sortie réelle des tests
Commande : `php tests/security/run.php` (PHP 8.4.19, code de sortie 1). Fichier joint : `docs/audit-resultats-tests.txt`.
```text
[FAIL] S-01  Rechargement de solde réservé à un rôle habilité ou lié à un paiement
         -> verif_rechargement() (Controleur_client.php) crédite le compte depuis $_POST['montant'] sans contrôle de rôle ni de paiement.
[FAIL] S-02  Suppression d'un barman : contrôle d'appartenance à l'association et méthode POST
         -> supprimerBarman() supprime une ligne de la table compte à partir de $_GET['id'] sans vérifier le rôle de la cible ni l'association.
[FAIL] S-03  Photo de profil : liste blanche d'extensions et vérification du type réel
         -> modifierPhotoProfil() garde l'extension fournie par le client et ne vérifie pas le contenu.
[FAIL] S-04  Annulation de commande : vérifie que la commande appartient au compte connecté
         -> enlever_commande($venteId) n'utilise aucun identifiant de compte dans ses requêtes.
[FAIL] S-05  Suppression/modification de produit limitée aux produits de l'association
         -> DELETE/UPDATE sur produit filtrés uniquement par id.
[FAIL] S-06  Modification du solde : client rattaché à l'association du gestionnaire
         -> gererSoldeClient() modifie le solde de n'importe quel compte désigné par id.
[FAIL] S-07  Dépôt des PDF : extension imposée côté serveur (pas celle du client)
         -> envoyerDemande() réutilise l'extension du nom de fichier client.
[FAIL] S-08a Aucun document déposé n'est versionné dans Git
         -> 6 fichier(s) de uploads/ sont suivis par Git (pièces justificatives d'associations).
[FAIL] S-08b uploads/ protégé (.htaccess) ou hors racine web
         -> Aucun uploads/.htaccess : les fichiers sont servis directement.
[FAIL] S-09  Aucun identifiant BD en clair dans Connexion.php (y compris commentés)
         -> 5 affectation(s) d'identifiant non vide détectée(s) (valeurs masquées volontairement).
[FAIL] S-10  Recherche d'associations : nom et email échappés avant affichage
         -> index.php insère $asso['nom'] et $asso['email'] tels quels dans le HTML.
[FAIL] S-11  Jeton anti-CSRF présent dans l'application
         -> Aucune occurrence de "csrf" dans index.php et modules/ ; actions destructrices en GET.
[FAIL] S-12  display_errors désactivé (ou non forcé à 1)
         -> index.php force display_errors=1.
[FAIL] S-13  Cookie de session durci (HttpOnly/SameSite/Secure configurés)
         -> Aucune configuration des attributs du cookie de session.
[FAIL] S-14  Code de validation généré avec un générateur cryptographique
         -> genererCode() utilise rand().
[PASS] P-01  Contrôle positif : aucune requête SQL ne concatène directement GET/POST
[PASS] P-02  Contrôle positif : le tri dynamique de modele_staff passe par une liste blanche
[PASS] P-03  Contrôle positif : mots de passe hachés (password_hash/password_verify)
[PASS] P-04  Contrôle positif : session_regenerate_id après connexion/inscription
[PASS] P-05  Contrôle positif : aucune exécution de commande système ni eval dans modules/

20 test(s) : 5 PASS, 15 FAIL
```

## 7. Retour critique
- **Faux positifs produits par l'IA** : FP-01 (le `ORDER BY` dynamique paraît injectable avant lecture de la liste blanche), FP-03 (un `echo` non échappé dans du code mort), FP-05 (beaucoup de sorties non échappées, en réalité numériques). Un premier essai de mon contrôle de fonctions a aussi produit des corps vides à cause d'un mauvais chemin : les FAIL ont été revérifiés (corps non vides) avant d'être retenus.
- **Faille découverte par le groupe** : *à compléter par le groupe.* Aucune faille découverte par les étudiants n'a été transmise à l'IA ; je n'en invente pas.
- **Prompt le plus utile** : *à confirmer par le groupe.* Observation : la consigne « ne rien présenter comme confirmé sans preuve » couplée à « un seul domaine à la fois » a été la plus efficace pour éviter les fausses alertes et garder des preuves reproductibles.
- **Limites** : audit statique uniquement ; barman/admin/staff non couverts ; exécution réelle (MySQL, serveur web) à faire en TP.
