# Contrat d'API frontend / backend

> Document initial : seules les informations vérifiées dans `index.php` sont renseignées.
> Tout le reste est **à documenter** (ne pas deviner).

## Architecture
Application PHP monolithique : pas d'API REST/JSON séparée. Le frontend est du HTML rendu
côté serveur (`Vue_*`, `templates/`), le backend est routé par `index.php` et `modules/`.

## Authentification
- Session PHP (`session_start()`), identifiant en `$_SESSION['id']`.
- Sans session, `index.php` redirige (`Location`) vers `templates/connexion.php`.
- Rôle déterminé par `index.php` : administrateur (table `administrateur`) ou rôle par association.
- Déconnexion : `GET index.php?action=deconnexion`.
- Réinitialisation du rôle choisi : `GET index.php?reset`.

## Endpoints vérifiés
| Méthode | URL | Paramètres | Réponse |
| --- | --- | --- | --- |
| GET | `index.php?action=deconnexion` | — | Détruit la session, redirection vers `templates/connexion.php` |
| GET | `index.php?reset` | — | Supprime `asso_choisi` et `role_effectif`, redirection vers `index.php` |
| POST | `index.php` | `rejoindre_asso` (id d'association, entier) | Texte brut `JOINED` ; ajoute l'appartenance avec le rôle `client` |

## Routage des modules
Chaque `Mod_<role>` lit `$_GET['action']` (défaut `espace` pour le client) et délègue à
`Controleur_<role>::gererAction()`.

## À documenter
- Liste des actions `?action=...` de chaque module (client, barman, gestionnaire, admin, staff, connexion)
- Paramètres, formats de requête/réponse (HTML, JSON, texte) et codes d'erreur de chacune
- Endpoints appelés par les fichiers `js/` (AJAX)
- Règles d'autorisation par rôle
