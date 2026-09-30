<?php
/**
 * Tests défensifs de sécurité (statiques, non destructifs, sans base de données).
 * Chaque test vérifie qu'une PROTECTION attendue est PRÉSENTE dans le code source.
 *   - FAIL = la protection est absente (vulnérabilité présente).
 *   - PASS = la protection existe ("contrôle positif", utile pour écarter les faux positifs).
 * Aucune requête n'est envoyée à l'application, aucune attaque n'est exécutée.
 * Usage : php tests/security/run.php   (code de sortie 1 s'il y a au moins un FAIL)
 */

$racine = dirname(__DIR__, 2);
$resultats = [];

function source(string $chemin): string
{
    global $racine;
    $contenu = @file_get_contents($racine . '/' . $chemin);
    return $contenu === false ? '' : $contenu;
}

/** Retourne le corps (accolades comprises) d'une fonction/méthode, via le tokenizer PHP. */
function corps(string $chemin, string $nom): string
{
    $code = source($chemin);
    $tokens = token_get_all($code);
    $n = count($tokens);
    for ($i = 0; $i < $n; $i++) {
        if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION) {
            $j = $i + 1;
            while ($j < $n && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) { $j++; }
            if ($j < $n && is_array($tokens[$j]) && $tokens[$j][1] === $nom) {
                while ($j < $n && $tokens[$j] !== '{') { $j++; }
                $niveau = 0; $sortie = '';
                for (; $j < $n; $j++) {
                    $t = is_array($tokens[$j]) ? $tokens[$j][1] : $tokens[$j];
                    if ($t === '{') { $niveau++; }
                    if ($t === '}') { $niveau--; }
                    $sortie .= $t;
                    if ($niveau === 0) { return $sortie; }
                }
            }
        }
    }
    return '';
}

function test(string $id, string $titre, bool $protectionPresente, string $detail): void
{
    global $resultats;
    $resultats[] = [$id, $titre, $protectionPresente, $detail];
}

$ctrlClient = 'modules/module_client/Controleur_client.php';
$ctrlGest   = 'modules/module_gestionnaire/Controleur_gestionnaire.php';
$modGest    = 'modules/module_gestionnaire/Modele_gestionnaire.php';

// ---------- Tests sur les failles identifiées ----------
$c = corps($ctrlClient, 'verif_rechargement');
test('S-01', 'Rechargement de solde réservé à un rôle habilité ou lié à un paiement',
    $c !== '' && (bool)preg_match('/role_effectif|paiement|payment/i', $c),
    'verif_rechargement() (Controleur_client.php) crédite le compte depuis $_POST[\'montant\'] sans contrôle de rôle ni de paiement.');

$c = corps($ctrlGest, 'supprimerBarman');
$m = corps($modGest, 'supprimerBarman');
test('S-02', 'Suppression d\'un barman : limitée aux associations gérées (gestionne), sans supprimer le compte',
    $c !== '' && $m !== ''
        && str_contains($m, 'gestionne') && !preg_match('/DELETE\s+FROM\s+compte/i', $m)
        && (bool)preg_match('/supprimerBarman\([^)]*\$_SESSION\s*\[\s*[\'"]id[\'"]\s*\]/', $c),
    'supprimerBarman() supprime une ligne de la table compte à partir de $_GET[\'id\'] sans vérifier le rôle de la cible ni l\'association.');

$c = corps($ctrlGest, 'modifierPhotoProfil');
test('S-03', 'Photo de profil : liste blanche d\'extensions et vérification du type réel',
    $c !== '' && (bool)preg_match('/in_array|getimagesize|finfo_/', $c),
    'modifierPhotoProfil() garde l\'extension fournie par le client et ne vérifie pas le contenu.');

$m = corps('commun/modele/ProduitVenduAcces.php', 'enlever_commande');
test('S-04', 'Annulation de commande : vérifie que la commande appartient au compte connecté',
    $m !== '' && (bool)preg_match('/compte_id/', $m),
    'enlever_commande($venteId) n\'utilise aucun identifiant de compte dans ses requêtes.');

$m1 = corps($modGest, 'supprimerProduit');
$m2 = corps($modGest, 'modifierProduit');
test('S-05', 'Suppression/modification de produit limitée aux produits de l\'association',
    $m1 !== '' && $m2 !== '' && (bool)preg_match('/association|gere/i', $m1) && (bool)preg_match('/association|gere/i', $m2),
    'DELETE/UPDATE sur produit filtrés uniquement par id.');

$c = corps($ctrlGest, 'gererSoldeClient');
test('S-06', 'Modification du solde : client rattaché à l\'association du gestionnaire',
    $c !== '' && (bool)preg_match('/gestionne|asso_choisi/', $c),
    'gererSoldeClient() modifie le solde de n\'importe quel compte désigné par id.');

$c = corps($ctrlGest, 'envoyerDemande');
test('S-07', 'Dépôt des PDF : extension imposée côté serveur (pas celle du client)',
    $c !== '' && !preg_match('/PATHINFO_EXTENSION/', $c),
    'envoyerDemande() réutilise l\'extension du nom de fichier client.');

$suivis = trim((string)shell_exec('cd ' . escapeshellarg($racine) . ' && git ls-files uploads 2>/dev/null'));
$nbSuivis = $suivis === '' ? 0 : count(explode("\n", $suivis));
test('S-08a', 'Aucun document déposé n\'est versionné dans Git',
    $nbSuivis === 0, "$nbSuivis fichier(s) de uploads/ sont suivis par Git (pièces justificatives d'associations).");
test('S-08b', 'uploads/ protégé (.htaccess) ou hors racine web',
    file_exists($racine . '/uploads/.htaccess'), 'Aucun uploads/.htaccess : les fichiers sont servis directement.');

$src = source('connexion/Connexion.php');
$nbSecrets = preg_match_all('/\$(?:user|password)\s*=\s*\'[^\']+\'/', $src);
test('S-09', 'Aucun identifiant BD en clair dans Connexion.php (y compris commentés)',
    $nbSecrets === 0, "$nbSecrets affectation(s) d'identifiant non vide détectée(s) (valeurs masquées volontairement).");

$idx = source('index.php');
$debut = strpos($idx, "isset(\$_GET['search'])");
$fin = strpos($idx, "isset(\$_SESSION['asso_choisi']) && isset(\$_SESSION['role_effectif'])");
$segment = ($debut !== false && $fin !== false) ? substr($idx, $debut, $fin - $debut) : '';
test('S-10', 'Recherche d\'associations : nom et email échappés avant affichage',
    $segment !== '' && substr_count($segment, 'htmlspecialchars') >= 2,
    'index.php insère $asso[\'nom\'] et $asso[\'email\'] tels quels dans le HTML.');

$tout = '';
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racine . '/modules', FilesystemIterator::SKIP_DOTS)) as $f) {
    if (str_ends_with($f->getFilename(), '.php')) { $tout .= file_get_contents($f->getPathname()); }
}
$tout .= $idx;
test('S-11', 'Jeton anti-CSRF présent dans l\'application',
    (bool)preg_match('/csrf/i', $tout), 'Aucune occurrence de "csrf" dans index.php et modules/ ; actions destructrices en GET.');

test('S-12', 'display_errors désactivé (ou non forcé à 1)',
    !preg_match('/ini_set\(\s*[\'"]display_errors[\'"]\s*,\s*1\s*\)/', $idx), 'index.php force display_errors=1.');

test('S-13', 'Cookie de session durci (HttpOnly/SameSite/Secure configurés)',
    (bool)preg_match('/session_set_cookie_params|cookie_httponly|cookie_samesite/', $tout . source('templates/connexion.php')),
    'Aucune configuration des attributs du cookie de session.');

test('S-14', 'Code de validation généré avec un générateur cryptographique',
    !preg_match('/\brand\s*\(/', source('commun/modele/CodeValidationAcces.php')), 'genererCode() utilise rand().');

// ---------- Contrôles positifs (protections présentes : doivent passer) ----------
$sqlConcat = 0;
foreach (['modules', 'commun', 'templates'] as $d) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racine . '/' . $d, FilesystemIterator::SKIP_DOTS)) as $f) {
        if (!str_ends_with($f->getFilename(), '.php')) { continue; }
        $sqlConcat += preg_match_all('/(SELECT|INSERT|UPDATE|DELETE)[^;]*\$_(GET|POST|REQUEST)/', file_get_contents($f->getPathname()));
    }
}
test('P-01', 'Contrôle positif : aucune requête SQL ne concatène directement GET/POST', $sqlConcat === 0, "$sqlConcat occurrence(s).");
test('P-02', 'Contrôle positif : le tri dynamique de modele_staff passe par une liste blanche',
    (bool)preg_match('/\$trisAutorises\[\$tri\]\s*\?\?/', source('modules/module_staff/modele_staff.php')), 'getProduitsFiltres().');
test('P-03', 'Contrôle positif : mots de passe hachés (password_hash/password_verify)',
    str_contains(source('templates/inscription.php'), 'password_hash') && str_contains(source('templates/connexion.php'), 'password_verify'), '');
test('P-04', 'Contrôle positif : session_regenerate_id après connexion/inscription',
    str_contains(source('templates/connexion.php'), 'session_regenerate_id') && str_contains(source('templates/inscription.php'), 'session_regenerate_id'), '');
$sys = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racine . '/modules', FilesystemIterator::SKIP_DOTS)) as $f) {
    if (str_ends_with($f->getFilename(), '.php')) {
        $sys += preg_match_all('/\b(shell_exec|system|passthru|popen|proc_open|eval)\s*\(/', file_get_contents($f->getPathname()));
    }
}
test('P-05', 'Contrôle positif : aucune exécution de commande système ni eval dans modules/', $sys === 0, "$sys occurrence(s).");

// ---------- Rapport ----------
$echecs = 0;
foreach ($resultats as [$id, $titre, $ok, $detail]) {
    if (!$ok) { $echecs++; }
    printf("[%s] %-5s %s\n", $ok ? 'PASS' : 'FAIL', $id, $titre);
    if (!$ok && $detail !== '') { echo "         -> $detail\n"; }
}
$total = count($resultats);
printf("\n%d test(s) : %d PASS, %d FAIL\n", $total, $total - $echecs, $echecs);
exit($echecs > 0 ? 1 : 0);
