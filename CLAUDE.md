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
Aucun test. Vérifier manuellement dans le navigateur, et au minimum `php -l <fichier>` sur les fichiers modifiés.

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
