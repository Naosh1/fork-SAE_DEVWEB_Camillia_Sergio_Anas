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

$stmt = $bdd->prepare("SELECT prenom, nom, email FROM compte WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: templates/connexion.php');
    exit();
}

$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['email'] = $user['email'];


$stmtAdmin = $bdd->prepare("SELECT 1 FROM administrateur WHERE compte_id = ? LIMIT 1");
$stmtAdmin->execute([$_SESSION['id']]);
$isAdmin = $stmtAdmin->fetch();

if ($isAdmin) {
    $_SESSION['role_effectif'] = 'admin';
    $_SESSION['role'] = 'admin';

    include_once 'modules/module_admin/Mod_admin.php';
    new Mod_admin();
    include_once 'templates/template_admin.php';
    exit();
}

if (isset($_GET['reset'])) {
    unset($_SESSION['asso_choisi']);
    unset($_SESSION['role_effectif']);
    header('Location: index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    session_unset();
    session_destroy();
    header('Location: templates/connexion.php');
    exit();
}

if (isset($_POST['rejoindre_asso'])) {
    $assoId = (int)$_POST['rejoindre_asso'];
    $check = $bdd->prepare("SELECT 1 FROM appartient WHERE compte_id = ? AND association_id = ?");
    $check->execute([$_SESSION['id'], $assoId]);

    if (!$check->fetch()) {
        $insert = $bdd->prepare("INSERT INTO appartient (compte_id, association_id, role) VALUES (?, ?, 'client')");
        $insert->execute([$_SESSION['id'], $assoId]);
    }
    exit("JOINED");
}

if (isset($_POST['choisir_asso'])) {
    $assoId = (int)$_POST['choisir_asso'];

    $stmt = $bdd->prepare("SELECT 1 FROM gestionne WHERE compte_id = ? AND association_id = ?");
    $stmt->execute([$_SESSION['id'], $assoId]);

    if ($stmt->fetch()) {
        $_SESSION['asso_choisi'] = $assoId;
        $_SESSION['role_effectif'] = 'gestionnaire';
        exit("OK");
    }

    $stmt = $bdd->prepare("
        SELECT role 
        FROM appartient 
        WHERE compte_id = ? AND association_id = ? 
        ORDER BY FIELD(role, 'barman', 'gestionnaire', 'client') 
        LIMIT 1
    ");
    $stmt->execute([$_SESSION['id'], $assoId]);
    $appartenance = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appartenance) {
        $_SESSION['asso_choisi'] = $assoId;
        $_SESSION['role_effectif'] = $appartenance['role'];
        exit("OK");
    }
    exit("NOT_MEMBER");
}

if (isset($_GET['search']) && !isset($_SESSION['asso_choisi']) && !isset($_GET['module']) && !isset($_GET['action'])) {
    $stmt = $bdd->prepare("
        SELECT a.id, a.nom, a.email,
        (SELECT 1 FROM gestionne WHERE association_id = a.id AND compte_id = ?) as is_gest,
        (SELECT role FROM appartient WHERE association_id = a.id AND compte_id = ? ORDER BY FIELD(role, 'barman', 'gestionnaire', 'client') LIMIT 1) as user_role
        FROM association a WHERE a.nom LIKE ?
    ");
    $stmt->execute([$_SESSION['id'], $_SESSION['id'], "%" . $_GET['search'] . "%"]);

    $results = $stmt->fetchAll();
    if (empty($results)) {
        echo "<div class='no-result'>Aucune association trouvée...</div>";
        exit;
    }

    foreach ($results as $asso) {
        $initiale = strtoupper(substr($asso['nom'], 0, 1));
        if ($asso['is_gest']) {
            $badge = "<span class='status-badge badge-admin'>Gérant</span>";
        } elseif ($asso['user_role'] === 'barman') {
            $badge = "<span class='status-badge badge-barman'>Barman</span>";
        } elseif ($asso['user_role']) {
            $badge = "<span class='status-badge badge-member'>Membre</span>";
        } else {
            $badge = "";
        }
        $action = ($asso['is_gest'] || $asso['user_role'])
            ? "<button class='enter-btn' data-id='{$asso['id']}'><i class='fa-solid fa-arrow-right-to-bracket'></i> Entrer</button>"
            : "<button class='join-btn' data-id='{$asso['id']}'><i class='fa-solid fa-plus'></i> Rejoindre</button>";
        echo "
        <div class='asso-card'>
            <div class='asso-content'>
                <div class='asso-avatar'>$initiale</div>
                <div class='asso-info'>
                    <div class='asso-name-row'><span class='asso-name'>{$asso['nom']}</span>$badge</div>
                    <span class='asso-email'>{$asso['email']}</span>
                </div>
            </div>
            <div class='asso-actions'>$action</div>
        </div>";
    }
    exit;
}

if (isset($_SESSION['asso_choisi']) && isset($_SESSION['role_effectif'])) {
    $_SESSION['role'] = $_SESSION['role_effectif'];

    if ($_SESSION['role_effectif'] === 'gestionnaire') {
        include_once 'modules/module_gestionnaire/Mod_gestionnaire.php';
        new Mod_gestionnaire();
        include_once 'templates/template_gestionnaire.php';
    } elseif ($_SESSION['role_effectif'] === 'barman') {
        include_once 'modules/module_barman/Mod_barman.php';
        new Mod_barman();
        include_once 'templates/template_barman.php';
    } else {
        include_once 'modules/module_client/Mod_client.php';
        new Mod_client();
        include_once 'templates/template_client.php';
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AssoManager | Portail</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #020617;
            --card-bg: rgba(30, 41, 59, 0.4);
            --primary: #2563eb;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #020617);
            color: var(--text-main);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
        }

        .badge-barman {
            background: rgba(37, 99, 235, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
            gap: 20px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .top-nav {
            width: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .logo {
            font-weight: 800;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .logo span {
            color: var(--primary);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 15px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            transition: 0.2s;
        }

        .portal-container {
            width: 100%;
            max-width: 580px;
            padding: 60px 24px;
            text-align: center;
            box-sizing: border-box;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: var(--text-dim);
            margin-bottom: 40px;
        }

        .search-wrapper {
            position: relative;
            margin-bottom: 30px;
            width: 100%;
        }

        .search-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
        }

        #search {
            width: 100%;
            padding: 18px 18px 18px 55px;
            background: rgba(30, 41, 59, 0.7);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
        }

        #search:focus {
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.2);
        }

        #resultats {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .asso-card {
            width: 100%;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            padding: 16px 20px;
            border-radius: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
            animation: fadeIn 0.4s ease forwards;
            box-sizing: border-box;
        }

        .asso-card:hover {
            background: rgba(30, 41, 59, 0.7);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .asso-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .asso-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .asso-info {
            text-align: left;
        }

        .asso-name {
            font-weight: 700;
            color: #fff;
        }

        .asso-email {
            font-size: 0.85rem;
            color: var(--text-dim);
            display: block;
        }

        .status-badge {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 900;
            padding: 2px 8px;
            border-radius: 6px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .badge-admin {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-member {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .asso-actions button {
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .enter-btn {
            background: var(--primary);
            color: white;
        }

        .join-btn {
            background: white;
            color: black;
        }

        .no-result {
            padding: 40px;
            color: var(--text-dim);
            font-style: italic;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div id="loader-overlay">
    <div class="spinner"></div>
    <p style="font-weight: 600; color: var(--primary)">CHARGEMENT...</p></div>
<nav class="top-nav">
    <div class="logo">Asso<span>Manager</span></div>
    <div class="user-menu">
        <span style="font-size: 0.85rem; font-weight: 600;"><i class="fa-solid fa-circle-user"
                                                               style="color: var(--primary); margin-right: 5px;"></i><?= htmlspecialchars($user['prenom']) ?></span>
        <div style="width: 1px; height: 15px; background: rgba(255,255,255,0.2);"></div>
        <a href="index.php?action=deconnexion" class="logout-btn"><i class="fa-solid fa-power-off"></i></a>
    </div>
</nav>
<div class="portal-container">
    <h2>Bonjour, <?= htmlspecialchars($user['prenom']) ?></h2>
    <p class="subtitle">Quelle association souhaites-tu gérer aujourd'hui ?</p>
    <div class="search-wrapper"><i class="fa-solid fa-magnifying-glass"></i><input type="text" id="search"
                                                                                   placeholder="Rechercher une association..."
                                                                                   autocomplete="off"></div>
    <div id="resultats"></div>
</div>
<script>
    const search = document.getElementById("search");
    const resultats = document.getElementById("resultats");
    const loader = document.getElementById("loader-overlay");

    search.addEventListener("keyup", () => {
        if (search.value.length > 0) {
            fetch("index.php?search=" + encodeURIComponent(search.value))
                .then(r => r.text()).then(html => resultats.innerHTML = html);
        } else {
            resultats.innerHTML = "";
        }
    });

    document.addEventListener("click", e => {
        const btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains("join-btn")) {
            fetch("index.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "rejoindre_asso=" + btn.dataset.id
            })
                .then(() => search.dispatchEvent(new Event("keyup")));
        }

        if (btn.classList.contains("enter-btn")) {
            loader.style.display = "flex";
            fetch("index.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "choisir_asso=" + btn.dataset.id
            })
                .then(r => r.text()).then(res => {
                if (res === "OK") {
                    setTimeout(() => location.reload(), 800);
                } else {
                    loader.style.display = "none";
                    alert("Erreur d'accès");
                }
            });
        }
    });
</script>
</body>
</html>