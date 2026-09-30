# CLAUDE.md

## Projet
SAE Développement Web (IUT Paris 8) : application web de gestion de buvettes associatives.
Rôles : client, barman, gestionnaire d'association, administrateur (plus un espace « staff »).
Fonctions : produits et stocks, ventes, solde/rechargement des clients, réapprovisionnement
fournisseurs, inventaires, statistiques, messagerie, demandes de création/adhésion d'associations.

## Stack
- PHP procédural/POO **sans framework ni Composer** (pas de `vendor/`), PDO + MySQL/MariaDB
- Front : HTML généré côté serveur, CSS et JS vanilla (`css/`, `js/`)
- Pas de tests automatisés, pas de linter, pas de CI

## Structure
- `index.php` : point d'entrée unique. Démarre la session, vérifie l'authentification,
  détermine le rôle (admin via la table `administrateur`, sinon rôle par association via
  `appartient`), instancie le module `Mod_*` puis inclut le `templates/template_*.php` du rôle
- `modules/module_<role>/` : un module MVC par rôle
  (`Mod_x` → `Controleur_x` → `Modele_x` + `Vue_x`) ; `mod_connexion` et `module_commun/staff` : variantes en minuscules
- `commun/modele/*Acces.php` : classes d'accès BD partagées (Compte, Produit, ProduitVendu, CodeValidation)
- `connexion/Connexion.php` : singleton PDO (`Connexion::getBdd()`)
- `vue_generique.php` : classe de base des vues ; `templates/` : gabarits de page par rôle
- `scriptBD/buvette.sql` : dump complet du schéma et des données
- `uploads/dossiers_assos/` : pièces justificatives déposées (PDF)

## Installation
1. Installer PHP ≥ 8.x avec l'extension `pdo_mysql`, et MariaDB/MySQL
2. Créer la base `buvette` et importer le dump :
   `mysql -u root -e "CREATE DATABASE buvette CHARACTER SET utf8mb4" && mysql -u root buvette < scriptBD/buvette.sql`
3. Vérifier les identifiants dans `connexion/Connexion.php` (par défaut : `127.0.0.1`, user `root`, mot de passe vide)

## Lancement
```
php -S localhost:8000      # depuis la racine, puis ouvrir http://localhost:8000/index.php
```
Sans session, `index.php` redirige vers `templates/connexion.php`.
Alternative : XAMPP (dossier dans `htdocs`, cf. dump phpMyAdmin).

## Tests
Aucun test automatisé. Vérifier manuellement dans le navigateur, et au minimum `php -l <fichier>` sur les fichiers modifiés.

### Dernière exécution (PHP 8.4.19, extensions `PDO`, `pdo_mysql` présentes)
- Installation : aucun `composer.json`, `package.json` ni verrou : **rien à installer**, aucune commande lancée.
- `find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l` : 38 fichiers, **0 erreur de syntaxe**.
- Tests unitaires/fonctionnels : aucun existant, donc non exécutés. `mysql`/`mariadb` absents du conteneur : import du dump et lancement de l'app **non testés**.
- Configuration : `.env.example` fourni (modèle sans secret) ; `.env` est ignoré par git mais **n'est pas encore lu** par `Connexion.php`.
- Branche par défaut : `master` (pas de `main`).

## Conventions observées
- Routage par paramètre GET `?action=...` : `Mod_x` lit l'action et appelle `Controleur_x::gererAction()`
- Requêtes SQL toujours préparées (`prepare`/`execute` avec `?`) ; garder cette pratique
- Nommage : code, tables et colonnes en **français** (`compte`, `appartient`, `prenom`, `association_id`)
- Fichiers de classes : `Mod_/Controleur_/Modele_/Vue_` + rôle, majuscule initiale (sauf anciens fichiers en minuscules)
- Un fichier CSS/JS par page ou fonctionnalité (`css/reappro.css`, `js/reappro.js`)
- Rôles stockés en session : `$_SESSION['id']`, `role`, `role_effectif`, `asso_choisi`
- Commentaires et messages en français

## Points d'attention
- Les identifiants BD sont en clair dans `Connexion.php` (plusieurs jeux commentés) : ne pas y committer de vrais secrets
- `index.php` active `display_errors` : à désactiver en production

## Règles de travail
1. Ne jamais committer de secrets (mots de passe, clés API, jetons, identifiants BD réels).
2. Une branche dédiée et une Pull Request par modification.
3. Tout correctif de sécurité est accompagné d'au moins un test pertinent (le dépôt n'a pas encore de framework de test : le signaler et proposer un script de test minimal).
4. Lancer les tests appropriés avant d'ouvrir une PR ; signaler clairement tout échec.
5. Tout changement de dépendance est expliqué : justification et conséquences.
6. Répondre en français.
7. Travailler sur **un seul périmètre fonctionnel** (une ligne du tableau ci-dessous) à la fois, et en respecter strictement les limites.
8. Ne pas lire les dépendances installées, fichiers de verrouillage, données (`scriptBD/`, `uploads/`), logs ni fichiers générés.
9. Ne pas explorer tout le frontend et tout le backend pour une tâche limitée à un périmètre.
10. Pour le contrat d'API frontend/backend, consulter `docs/API.md` (à créer s'il est absent, dans une PR dédiée).

## Domaines et périmètres
Il n'existe pas de dossiers `frontend/` ni `backend/` : les deux parties cohabitent dans `modules/`,
séparées par nom de fichier (`Vue_*`/`vue_*` = frontend ; `Mod_*`, `Controleur_*`, `Modele_*`/`modele_*` = backend).

| Domaine | Partie | Dossiers / fichiers | Responsabilité |
| --- | --- | --- | --- |
| Authentification | Frontend | `modules/mod_connexion/vue.connexion.php`, `templates/connexion.php`, `templates/inscription.php`, `css/connexion.css`, `js/connexion.js` | Formulaires de connexion et d'inscription |
| Authentification | Backend | `modules/mod_connexion/` (`mod_`, `cont_`, `modele_`), `connexion/`, `index.php` | Session, contrôle d'accès, accès PDO |
| Espace client | Frontend | `modules/module_client/Vue_client.php`, `templates/template_client.php` | Interface client |
| Espace client | Backend | `modules/module_client/` (`Mod_`, `Controleur_`, `Modele_`) | Logique client |
| Espace barman | Frontend | `modules/module_barman/Vue_barman.php`, `templates/template_barman.php` | Interface barman |
| Espace barman | Backend | `modules/module_barman/` (`Mod_`, `Controleur_`, `Modele_`) | Logique barman |
| Gestion d'association | Frontend | `modules/module_gestionnaire/Vue_gestionnaire.php`, `templates/template_gestionnaire.php` | Interface gestionnaire |
| Gestion d'association | Backend | `modules/module_gestionnaire/` (`Mod_`, `Controleur_`, `Modele_`) | Logique gestionnaire |
| Administration | Frontend | `modules/module_admin/Vue_admin.php`, `templates/template_admin.php` | Interface administrateur |
| Administration | Backend | `modules/module_admin/` (`Mod_`, `Controleur_`, `Modele_`) | Logique administrateur |
| Staff et éléments communs | Frontend | `modules/module_staff/vue_staff.php`, `modules/module_commun/vue_commun.php`, `vue_generique.php`, `templates/template_erreur.php` | Vues partagées |
| Staff et éléments communs | Backend | `modules/module_staff/modele_staff.php`, `modules/module_commun/modele_commun.php`, `commun/modele/` | Accès BD partagés |

`css/`, `js/` et `style.css` sont du frontend partagé : le rattachement de chaque fichier à un domaine reste à confirmer (hors `connexion.*`).
