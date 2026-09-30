<?php
/**
 * Test de non-régression F1 (S-01) : un client ne doit pas pouvoir créditer lui-même son compte.
 * Test statique et non destructif : il lit le code source, n'envoie aucune requête.
 *   - FAIL = la protection est absente (faille présente).
 * Usage : php tests/security/test_F1_recharge_solde.php   (code de sortie 1 s'il y a un FAIL)
 */

$racine = dirname(__DIR__, 2);
$fichierControleur = 'modules/module_client/Controleur_client.php';
$fichierModele = 'commun/modele/CompteAcces.php';

function lire(string $chemin): string
{
    global $racine;
    $contenu = @file_get_contents($racine . '/' . $chemin);
    return $contenu === false ? '' : $contenu;
}

/** Corps (accolades comprises) d'une fonction/méthode, via le tokenizer PHP. */
function corps(string $code, string $nom): string
{
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

$resultats = [];
function verifier(string $id, string $titre, bool $ok, string $detail = ''): void
{
    global $resultats;
    $resultats[] = [$id, $titre, $ok, $detail];
}

$codeControleur = lire($fichierControleur);
$c = corps($codeControleur, 'verif_rechargement');

// Garde-fou : si la fonction n'est pas trouvée, les tests suivants n'auraient aucun sens.
verifier('F1-00', 'verif_rechargement() est trouvée dans le contrôleur client', $c !== '',
    'Corps vide : chemin ou nom de fonction incorrect, les résultats suivants ne sont pas fiables.');

// Protection attendue : aucun crédit de solde depuis l'action client.
verifier('F1-01', 'verif_rechargement() n\'appelle pas recharger_solde()',
    $c !== '' && !str_contains($c, 'recharger_solde'),
    'Le solde est crédité depuis l\'action client.');
verifier('F1-02', 'verif_rechargement() ne lit pas le montant fourni par le client ($_POST[\'montant\'])',
    $c !== '' && !preg_match('/\$_(POST|GET|REQUEST)\s*\[\s*[\'"]montant[\'"]\s*\]/', $c),
    'Le montant vient directement de la requête.');
verifier('F1-03', 'Aucune autre méthode du contrôleur client ne crédite le solde',
    $codeControleur !== '' && substr_count($codeControleur, 'recharger_solde(') === 0,
    substr_count($codeControleur, 'recharger_solde(') . ' appel(s) à recharger_solde() dans Controleur_client.php.');

// Contrôles de non-régression : l'action reste une redirection propre, et le crédit reste disponible côté modèle.
verifier('F1-04', 'verif_rechargement() redirige toujours vers l\'espace client puis termine (header + exit)',
    $c !== '' && str_contains($c, 'header(') && str_contains($c, 'action=espace') && str_contains($c, 'exit'),
    'La méthode ne redirige plus correctement.');
verifier('F1-05', 'CompteAcces::recharger_solde() existe toujours (rechargement par un rôle habilité)',
    corps(lire($fichierModele), 'recharger_solde') !== '',
    'La méthode du modèle a disparu : hors périmètre de F1.');

$echecs = 0;
foreach ($resultats as [$id, $titre, $ok, $detail]) {
    if (!$ok) { $echecs++; }
    printf("[%s] %-5s %s\n", $ok ? 'PASS' : 'FAIL', $id, $titre);
    if (!$ok && $detail !== '') { echo "         -> $detail\n"; }
}
printf("\n%d test(s) : %d PASS, %d FAIL\n", count($resultats), count($resultats) - $echecs, $echecs);
exit($echecs > 0 ? 1 : 0);
