<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'vue_generique.php';
include_once 'connexion/Connexion.php';
Connexion::initConnexion();
$bdd = Connexion::getBdd();


if (!isset($_SESSION['id'])) {
    header('Location: templates/connexion.php');
    exit();
}

if (isset($_POST['choisir_asso'])) {

    $assoId = $_POST['choisir_asso'];

    $stmt = $bdd->prepare("
        SELECT 1 FROM gestionne
        WHERE compte_id = ? AND association_id = ?
    ");
    $stmt->execute([$_SESSION['id'], $assoId]);

    $_SESSION['asso_choisi'] = $assoId;
    if ($stmt->fetch()) {
        $_SESSION['role_effectif'] = 'gestionnaire';
    } else {
        $_SESSION['role_effectif'] = "client";
    }
    exit("OK");
}


if (isset($_GET['search'])) {
    $stmt = $bdd->prepare("
        SELECT id, nom, email, solde 
        FROM association
        WHERE nom LIKE ?
    ");
    $stmt->execute(["%".$_GET['search']."%"]);

    foreach ($stmt as $asso) {
        echo "
        <div class='asso' data-id='{$asso['id']}'>
            <strong>{$asso['nom']}</strong><br>
            {$asso['email']}<br>
            Solde : {$asso['solde']} €
        </div><hr>
        ";
    }
    exit;
}


$stmt = $bdd->prepare("SELECT prenom, nom, email, role FROM compte WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
if (!$user) {
    session_unset();
    session_destroy();
    header('Location: templates/connexion.php');
    exit();
}

$_SESSION['role_global'] = $user['role'];

if (!isset($_SESSION['role_effectif'])) {
    $_SESSION['role_effectif'] = $user['role'];
}

$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['email'] = $user['email'];
$_SESSION['login'] = $user['email'];

$login = $_SESSION['login'];
$role = $_SESSION['role_effectif'];

if(isset($_SESSION["asso_choisi"]) && !isset($_GET['reset'])){
    switch ($role) {
        case 'gestionnaire':
        case 'admin':
            include_once 'modules/module_gestionnaire/Mod_gestionnaire.php';
            new Mod_gestionnaire();
            include_once 'templates/template_gestionnaire.php';
            break;

        case 'barman':
            include_once 'modules/module_barman/Mod_barman.php';
            new Mod_barman();
            include_once 'templates/template_barman.php';
            break;

        case 'client':
        default:
            include_once 'modules/module_client/Mod_client.php';
            new Mod_client();
            include_once 'templates/template_client.php';
            break;
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Association</title>

    <style>
        .asso {
            cursor: pointer;
            padding: 10px;
            background: #f4f4f4;
        }
        .asso:hover {
            background: #ddd;
        }
    </style>
</head>

<body>

<?php if (!isset($_SESSION["asso_choisi"])): ?>

    <h2>🔍 Rechercher une association</h2>

    <input type="text" id="search" placeholder="Nom de l'association">

    <div id="resultats"></div>


<?php endif; ?>

<?php
if (isset($_GET['reset'])) {
    unset($_SESSION['asso_choisi']);
    $_SESSION['role_effectif'] = $_SESSION['role_global'];
    header("Location: index.php");
    exit;
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const searchInput = document.getElementById("search");
        const resultats = document.getElementById("resultats");

        if (searchInput) {
            searchInput.addEventListener("keyup", function () {

                fetch("index.php?search=" + encodeURIComponent(this.value))
                    .then(response => response.text())
                    .then(html => {
                        resultats.innerHTML = html;
                    });
            });
        }

        document.addEventListener("click", function (e) {
            if (e.target.closest(".asso")) {
                const asso = e.target.closest(".asso");
                const id = asso.dataset.id;

                fetch("index.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "choisir_asso=" + encodeURIComponent(id)
                })
                    .then(response => response.text())
                    .then(res => {
                        if (res === "OK") {
                            location.reload();
                        }
                    });
            }
        });

    });
</script>

</body>
</html>