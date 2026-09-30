<?php
/**
 * Test de non-régression F2 (S-02) : un gestionnaire ne peut retirer le rôle "barman" que dans les
 * associations qu'il gère, sans jamais supprimer le compte ni les autres rattachements.
 *
 * Test comportemental non destructif : il s'exécute sur une base SQLite EN MÉMOIRE avec des données
 * fictives, injectée dans Connexion par réflexion. Aucune base réelle n'est contactée.
 * Prérequis : extension pdo_sqlite (php -m | grep pdo_sqlite).
 * Usage : php tests/security/test_F2_suppression_barman.php   (code de sortie 1 s'il y a un FAIL)
 */

$racine = dirname(__DIR__, 2);
chdir($racine);

if (!in_array('sqlite', PDO::getAvailableDrivers(), true)) {
    fwrite(STDERR, "Extension pdo_sqlite absente : test impossible.\n");
    exit(2);
}

include_once 'modules/module_gestionnaire/Modele_gestionnaire.php';

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
(new ReflectionProperty('Connexion', 'bdd'))->setValue(null, $pdo);

// Garde-fou : on ne touche jamais autre chose qu'une base SQLite en mémoire.
if (Connexion::getBdd()->getAttribute(PDO::ATTR_DRIVER_NAME) !== 'sqlite') {
    fwrite(STDERR, "Abandon : la connexion active n'est pas SQLite.\n");
    exit(2);
}

/**
 * (Ré)initialise les données fictives. Les identifiants sont typés INTEGER (affinité SQLite) et les
 * clés étrangères reproduisent celles du schéma MySQL (cascade compte -> appartient/gestionne,
 * dispose -> compte sans cascade), car SQLite ne les applique pas par défaut.
 */
function fixtures(PDO $pdo): void
{
    $pdo->exec("PRAGMA foreign_keys = OFF");
    foreach (['dispose', 'gestionne', 'appartient', 'compte'] as $t) {
        $pdo->exec("DROP TABLE IF EXISTS $t");
    }
    $pdo->exec("PRAGMA foreign_keys = ON");
    $pdo->exec("
        CREATE TABLE compte (id INTEGER PRIMARY KEY, nom TEXT, actif INTEGER);
        CREATE TABLE appartient (compte_id INTEGER, association_id INTEGER, role TEXT,
                                 PRIMARY KEY (compte_id, association_id, role),
                                 FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE);
        CREATE TABLE gestionne (compte_id INTEGER, association_id INTEGER,
                                PRIMARY KEY (compte_id, association_id),
                                FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE);
        CREATE TABLE dispose (role_id INTEGER, compte_id INTEGER,
                              FOREIGN KEY (compte_id) REFERENCES compte(id));
        -- 1 = gestionnaire G1 (asso 10), 2 = gestionnaire G2 (asso 20)
        -- 3 = barman en 10 ET en 20, client en 10 ; 4 = barman en 20 ; 5 = client en 10
        INSERT INTO compte VALUES (1,'G1',1),(2,'G2',1),(3,'B3',1),(4,'B4',1),(5,'C5',1);
        INSERT INTO gestionne VALUES (1,10),(2,20);
        INSERT INTO appartient VALUES (3,10,'barman'),(3,10,'client'),(3,20,'barman'),(4,20,'barman'),(5,10,'client');
        INSERT INTO dispose VALUES (7,3);
    ");
}

function etatAppartient(PDO $pdo): array
{
    $l = [];
    foreach ($pdo->query("SELECT compte_id, association_id, role FROM appartient ORDER BY 1,2,3") as $r) {
        $l[] = "({$r['compte_id']},{$r['association_id']},{$r['role']})";
    }
    return $l;
}

function nbComptes(PDO $pdo): int
{
    return (int)$pdo->query("SELECT COUNT(*) FROM compte")->fetchColumn();
}

function compteExiste(PDO $pdo, int $id): bool
{
    return (int)$pdo->query("SELECT COUNT(*) FROM compte WHERE id = $id")->fetchColumn() === 1;
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
function liste(array $l): string { return $l ? implode(' ', $l) : '(vide)'; }

$modele = new ModeleGestionnaire();

// F2-00 : garde-fou sur les données de départ.
fixtures($pdo);
$depart = etatAppartient($pdo);
verifier('F2-00', 'Données fictives en place (5 comptes, 5 lignes appartient)',
    nbComptes($pdo) === 5 && count($depart) === 5, 'Fixtures incorrectes : résultats suivants non fiables.');

// F2-01 : G1 (gère 10) tente de retirer le barman 4, qui n'est barman qu'en 20 (non gérée).
fixtures($pdo);
$res = $modele->supprimerBarman(4, 1);
verifier('F2-01a', 'G1 ne peut pas retirer un barman d\'une association qu\'il ne gère pas (retour false)',
    $res === false, 'Retour : ' . var_export($res, true));
verifier('F2-01b', '... et les rattachements sont inchangés',
    etatAppartient($pdo) === $depart, "Avant : " . liste($depart) . " | Après : " . liste(etatAppartient($pdo)));
verifier('F2-01c', '... et le compte cible n\'est pas supprimé', compteExiste($pdo, 4), 'Le compte 4 a été supprimé.');

// F2-02 : G1 retire le barman 3 (barman en 10 et 20, client en 10) : seule la ligne (3,10,barman) doit partir.
fixtures($pdo);
$res = $modele->supprimerBarman(3, 1);
$attendu = array_values(array_diff($depart, ['(3,10,barman)']));
verifier('F2-02a', 'G1 retire le rôle barman dans son association (retour true)',
    $res === true, 'Retour : ' . var_export($res, true));
verifier('F2-02b', '... seule la ligne (3,10,barman) disparaît ; (3,20,barman) et (3,10,client) restent',
    etatAppartient($pdo) === $attendu, "Attendu : " . liste($attendu) . " | Obtenu : " . liste(etatAppartient($pdo)));
verifier('F2-02c', '... le compte 3 et sa liaison dispose sont préservés',
    compteExiste($pdo, 3) && (int)$pdo->query("SELECT COUNT(*) FROM dispose WHERE compte_id = 3")->fetchColumn() === 1,
    'Le compte 3 ou sa ligne dispose a été supprimé.');

// F2-03 : G2 (gère 20) agit sur le même compte 3 : seule la ligne (3,20,barman) peut partir.
fixtures($pdo);
$res = $modele->supprimerBarman(3, 2);
$attendu = array_values(array_diff($depart, ['(3,20,barman)']));
verifier('F2-03', 'G2 ne retire que le rôle barman de son association (3,20) ; les rattachements en 10 restent',
    $res === true && etatAppartient($pdo) === $attendu,
    "Retour : " . var_export($res, true) . " | Attendu : " . liste($attendu) . " | Obtenu : " . liste(etatAppartient($pdo)));

// F2-04 : la cible n'est pas barman (client seulement) : rien ne change.
fixtures($pdo);
$res = $modele->supprimerBarman(5, 1);
verifier('F2-04', 'Cible qui n\'est pas barman : retour false, rattachements inchangés, compte conservé',
    $res === false && etatAppartient($pdo) === $depart && compteExiste($pdo, 5),
    "Retour : " . var_export($res, true) . " | Après : " . liste(etatAppartient($pdo)));

// F2-05 : un gestionnaire qui ne gère rien (compte 5) ne peut rien retirer.
fixtures($pdo);
$res = $modele->supprimerBarman(3, 5);
verifier('F2-05', 'Un compte qui ne gère aucune association ne peut rien retirer',
    $res === false && etatAppartient($pdo) === $depart,
    "Retour : " . var_export($res, true) . " | Après : " . liste(etatAppartient($pdo)));

// F2-06 : aucun compte n'est supprimé, quel que soit le scénario ci-dessus (rejoué sur des données neuves).
$minimum = 5;
foreach ([[4, 1], [3, 1], [3, 2], [5, 1], [3, 5]] as [$cible, $acteur]) {
    fixtures($pdo);
    $modele->supprimerBarman($cible, $acteur);
    $minimum = min($minimum, nbComptes($pdo));
}
verifier('F2-06', 'Aucun compte n\'est jamais supprimé (5 comptes conservés dans tous les scénarios)',
    $minimum === 5, "Minimum de comptes observé : $minimum.");

// F2-07 (statique) : le contrôleur transmet l'identifiant du gestionnaire connecté.
$ctrl = corps(file_get_contents($racine . '/modules/module_gestionnaire/Controleur_gestionnaire.php'), 'supprimerBarman');
verifier('F2-07', 'Le contrôleur passe $_SESSION[\'id\'] au modèle',
    $ctrl !== '' && (bool)preg_match('/supprimerBarman\([^)]*\$_SESSION\s*\[\s*[\'"]id[\'"]\s*\]/', $ctrl),
    'Le contrôleur n\'identifie pas le gestionnaire qui agit.');

$echecs = 0;
foreach ($resultats as [$id, $titre, $ok, $detail]) {
    if (!$ok) { $echecs++; }
    printf("[%s] %-6s %s\n", $ok ? 'PASS' : 'FAIL', $id, $titre);
    if (!$ok && $detail !== '') { echo "          -> $detail\n"; }
}
printf("\n%d test(s) : %d PASS, %d FAIL\n", count($resultats), count($resultats) - $echecs, $echecs);
exit($echecs > 0 ? 1 : 0);
