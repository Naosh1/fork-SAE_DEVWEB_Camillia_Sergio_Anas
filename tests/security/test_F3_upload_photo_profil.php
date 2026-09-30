<?php
/**
 * Test de non-régression F3 (S-03) : téléversement de la photo de profil.
 *
 * Deux familles de vérifications, affichées séparément :
 *   [FAILLE]   tests STATIQUES sur modifierPhotoProfil() : ils échouent tant que la faille existe
 *              (extension issue du client, dossier en 0777, nom prévisible, contenu jamais contrôlé).
 *   [COMPORT.] tests COMPORTEMENTAUX du helper extensionImageValide() avec de vrais fichiers
 *              temporaires : ils décrivent le nouveau comportement. Avant correctif, ils échouent
 *              seulement parce que le helper n'existe pas encore : ils ne démontrent PAS la faille.
 *
 * Non destructif : seuls des fichiers temporaires (supprimés en fin de test) sont créés ; aucune base
 * de données ni aucun dossier du projet n'est touché.
 * Usage : php tests/security/test_F3_upload_photo_profil.php   (code de sortie 1 s'il y a un FAIL)
 */

$racine = dirname(__DIR__, 2);
chdir($racine);
$fichierControleur = 'modules/module_gestionnaire/Controleur_gestionnaire.php';

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
function verifier(string $famille, string $id, string $titre, bool $ok, string $detail = ''): void
{
    global $resultats;
    $resultats[] = [$famille, $id, $titre, $ok, $detail];
}

// ---------- Famille [FAILLE] : vérifications statiques ----------
$code = file_get_contents($fichierControleur);
$c = corps($code, 'modifierPhotoProfil');
verifier('FAILLE', 'F3-00', 'modifierPhotoProfil() est trouvée (garde-fou)', $c !== '',
    'Corps vide : les vérifications statiques ne sont pas fiables.');
verifier('FAILLE', 'F3-S1', 'L\'extension du fichier ne vient pas du nom fourni par le client',
    $c !== '' && !str_contains($c, 'PATHINFO_EXTENSION'),
    'pathinfo($file[\'name\'], PATHINFO_EXTENSION) est utilisé.');
verifier('FAILLE', 'F3-S2', 'Le dossier de photos n\'est pas créé en 0777',
    $c !== '' && !str_contains($c, '0777'),
    'mkdir(..., 0777) : dossier modifiable par tous.');
verifier('FAILLE', 'F3-S3', 'Le nom du fichier est aléatoire côté serveur (random_bytes), pas basé sur time()',
    $c !== '' && str_contains($c, 'random_bytes') && !str_contains($c, 'time()'),
    'Nom prévisible : pp_<id>_<time>.');
$posValidation = strpos($c, 'extensionImageValide');
$posDeplacement = strpos($c, 'move_uploaded_file');
verifier('FAILLE', 'F3-S4', 'Le contenu est contrôlé (extensionImageValide) avant move_uploaded_file',
    $posValidation !== false && $posDeplacement !== false && $posValidation < $posDeplacement,
    'Aucun contrôle du contenu ou de la taille avant l\'enregistrement du fichier.');

// ---------- Famille [COMPORT.] : helper extensionImageValide() ----------
// Le contrôleur exige que Connexion soit déjà chargée (index.php s'en charge en production).
include_once 'connexion/Connexion.php';
include_once $fichierControleur;

$helper = null;
if (class_exists('ControleurGestionnaire') && method_exists('ControleurGestionnaire', 'extensionImageValide')) {
    $classe = new ReflectionClass('ControleurGestionnaire');
    $helper = $classe->getMethod('extensionImageValide');
    $instance = $classe->newInstanceWithoutConstructor();
}

$temporaires = [];
/** Crée un vrai fichier temporaire ; retourne son chemin. */
function fichier(string $contenu): string
{
    global $temporaires;
    $chemin = tempnam(sys_get_temp_dir(), 'f3_');
    file_put_contents($chemin, $contenu);
    $temporaires[] = $chemin;
    return $chemin;
}
function appeler(ReflectionMethod $helper, object $instance, array $fichier): mixed
{
    return $helper->invoke($instance, $fichier);
}

$images = [
    'png'  => base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='),
    'jpeg' => base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='),
    'webp' => base64_decode('UklGRhoAAABXRUJQVlA4TA0AAAAvAAAAEAcQERGIiP4HAA=='),
    'gif'  => base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'),
];

$cas = [
    ['F3-C1', 'PNG valide accepté, extension déduite du contenu : png', $images['png'], 'png'],
    ['F3-C2', 'JPEG valide accepté, extension déduite du contenu : jpg', $images['jpeg'], 'jpg'],
    ['F3-C3', 'WebP valide accepté, extension déduite du contenu : webp', $images['webp'], 'webp'],
    ['F3-C4', 'GIF (hors liste blanche) refusé', $images['gif'], null],
    ['F3-C5', 'Fichier PHP refusé (même s\'il porterait un nom d\'image)', "<?php echo 'test';\n", null],
    ['F3-C6', 'Fichier texte refusé', "bonjour\n", null],
    ['F3-C7', 'Document HTML refusé', "<html><body>x</body></html>", null],
    ['F3-C8', 'SVG refusé', '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"></svg>', null],
    ['F3-C9', 'Fichier vide refusé', '', null],
    ['F3-C10', 'Image valide mais de plus de 2 Mo refusée', $images['png'] . str_repeat("\0", 2 * 1024 * 1024 + 1), null],
    ['F3-C11', 'Image valide de taille limite (moins de 2 Mo) acceptée', $images['png'] . str_repeat("\0", 1024 * 1024), 'png'],
];

foreach ($cas as [$id, $titre, $contenu, $attendu]) {
    if ($helper === null) {
        verifier('COMPORT.', $id, $titre, false, 'Le helper extensionImageValide() n\'existe pas encore.');
        continue;
    }
    $resultat = appeler($helper, $instance, ['tmp_name' => fichier($contenu)]);
    verifier('COMPORT.', $id, $titre, $resultat === $attendu,
        'Attendu : ' . var_export($attendu, true) . ' | Obtenu : ' . var_export($resultat, true));
}
if ($helper === null) {
    verifier('COMPORT.', 'F3-C12', 'Chemin inexistant refusé', false, 'Le helper extensionImageValide() n\'existe pas encore.');
    verifier('COMPORT.', 'F3-C13', 'Le nom fourni par le client est ignoré (image PNG déclarée "x.php" → png)', false,
        'Le helper extensionImageValide() n\'existe pas encore.');
} else {
    verifier('COMPORT.', 'F3-C12', 'Chemin inexistant refusé',
        appeler($helper, $instance, ['tmp_name' => sys_get_temp_dir() . '/f3_inexistant_' . bin2hex(random_bytes(4))]) === null);
    $r = appeler($helper, $instance, ['tmp_name' => fichier($images['png']), 'name' => 'x.php']);
    verifier('COMPORT.', 'F3-C13', 'Le nom fourni par le client est ignoré (image PNG déclarée "x.php" → png)',
        $r === 'png', 'Obtenu : ' . var_export($r, true));
}

foreach ($temporaires as $t) { @unlink($t); }

// ---------- Rapport ----------
$echecs = 0;
$parFamille = ['FAILLE' => [0, 0], 'COMPORT.' => [0, 0]];
foreach ($resultats as [$famille, $id, $titre, $ok, $detail]) {
    $parFamille[$famille][$ok ? 0 : 1]++;
    if (!$ok) { $echecs++; }
    printf("[%s] [%-8s] %-6s %s\n", $ok ? 'PASS' : 'FAIL', $famille, $id, $titre);
    if (!$ok && $detail !== '') { echo "                        -> $detail\n"; }
}
printf("\nFAILLE (statique)      : %d PASS, %d FAIL\n", $parFamille['FAILLE'][0], $parFamille['FAILLE'][1]);
printf("COMPORT. (helper)      : %d PASS, %d FAIL\n", $parFamille['COMPORT.'][0], $parFamille['COMPORT.'][1]);
printf("%d test(s) : %d PASS, %d FAIL\n", count($resultats), count($resultats) - $echecs, $echecs);
exit($echecs > 0 ? 1 : 0);
