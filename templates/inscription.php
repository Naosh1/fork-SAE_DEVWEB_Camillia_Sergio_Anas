<?php
session_start();
include_once "../connexion/Connexion.php";
Connexion::initConnexion();

if (isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp = $_POST['mdp'] ?? '';
    $mdp_conf = $_POST['mdp_confirmation'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $role = 'client';

    // Validations PHP
    if (!$nom) $erreurs[] = "Le nom est requis";
    if (!$prenom) $erreurs[] = "Le prénom est requis";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide";
    if (!$date_naissance) $erreurs[] = "La date de naissance est requise";

    if ($date_naissance) {
        $aujourdhui = new DateTime();
        $dob = new DateTime($date_naissance);
        $age = $dob->diff($aujourdhui)->y;
        if ($age < 18) $erreurs[] = "Vous devez être majeur (18 ans minimum)";
    }

    if (strlen($mdp) < 12) $erreurs[] = "Le mot de passe doit faire au moins 12 caractères";
    if ($mdp !== $mdp_conf) $erreurs[] = "Les mots de passe ne correspondent pas";

    $bdd = Connexion::getBdd();
    $check = $bdd->prepare("SELECT COUNT(*) FROM compte WHERE email=?");
    $check->execute([$email]);
    if ($check->fetchColumn() > 0) $erreurs[] = "Cet email est déjà utilisé";

    if (!$erreurs) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $insert = $bdd->prepare("
            INSERT INTO compte (nom, prenom, date_naissance, email, mdp, solde, role)
            VALUES (?,?,?,?,?,0.00,?)
        ");
        if ($insert->execute([$nom, $prenom, $date_naissance, $email, $mdp_hash, $role])) {
            session_regenerate_id(true);
            $_SESSION['id'] = $bdd->lastInsertId();
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['login'] = $email;
            header('Location: ../index.php');
            exit();
        } else {
            $erreurs[] = "Erreur lors de l'inscription";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rejoindre | AssoManager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .bg-animated {
            background: linear-gradient(-45deg, #020617, #0f172a, #1e3a8a, #020617);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-glass {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            border-color: #3b82f6;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }

        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: #020617;
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-animated flex items-center justify-center min-h-screen p-4">

<div id="loader-overlay">
    <div class="relative">
        <div class="w-24 h-24 border-4 border-blue-900/30 border-t-blue-500 rounded-full animate-spin"></div>
        <i class="fa-solid fa-user-plus text-blue-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl animate-pulse"></i>
    </div>
    <p id="loader-text" class="mt-8 text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] animate-pulse text-center">
        Création de votre badge...
    </p>
</div>

<div class="glass-card rounded-[2.5rem] max-w-lg w-full p-8 shadow-2xl relative overflow-hidden">
    <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl"></div>

    <div class="relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-white italic tracking-tighter uppercase">
                Rejoindre l'<span class="text-blue-500">Asso</span>
            </h1>
            <p class="text-slate-400 font-medium mt-2">Créez votre compte en quelques secondes</p>
        </div>

        <?php if ($erreurs) : ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-2xl mb-6 text-xs font-bold italic">
                <ul class="space-y-1">
                    <?php foreach ($erreurs as $erreur) : ?>
                        <li><i class="fa-solid fa-xmark mr-2"></i> <?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" id="regForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="nom" placeholder="NOM" required
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                           class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase tracking-widest">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="prenom" placeholder="PRÉNOM" required
                           value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                           class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase tracking-widest">
                </div>
            </div>

            <div class="relative">
                <i class="fa-solid fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="date" name="date_naissance" required
                       value="<?= htmlspecialchars($_POST['date_naissance'] ?? '') ?>"
                       class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-600 font-black uppercase">Naissance</span>
            </div>

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="email" name="email" placeholder="EMAIL@EXEMPLE.FR" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="password" name="mdp" placeholder="MOT DE PASSE" required minlength="12"
                           class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="password" name="mdp_confirmation" placeholder="CONFIRMER" required
                           class="input-glass w-full pl-10 pr-4 py-4 rounded-2xl outline-none text-sm font-bold uppercase">
                </div>
            </div>

            <p class="text-[10px] text-slate-500 font-bold italic text-center px-4">
                En vous inscrivant, vous certifiez être majeur et acceptez les conditions de la buvette associative.
            </p>

            <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-5 rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98]">
                Finaliser l'inscription <i class="fa-solid fa-chevron-right ml-2"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-white/5 text-center">
            <p class="text-slate-500 text-sm font-medium">
                Déjà membre ?
                <a href="connexion.php" class="text-blue-400 font-bold hover:text-blue-300 transition-colors ml-1">Connectez-vous</a>
            </p>
        </div>
    </div>
</div>

<script>
    document.getElementById('regForm').addEventListener('submit', function(e) {
        // Empêcher l'envoi immédiat
        e.preventDefault();
        const form = this;

        // Afficher le loader
        document.getElementById('loader-overlay').style.display = 'flex';

        // Messages d'ambiance
        const messages = [
            "Vérification de l'éligibilité...",
            "Préparation de votre compte...",
            "Génération du badge virtuel...",
            "Mise à jour des registres..."
        ];
        document.getElementById('loader-text').innerText = messages[Math.floor(Math.random() * messages.length)];

        // UI du bouton
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Traitement...';

        // Délai de 2 secondes avant l'envoi
        setTimeout(function() {
            form.submit();
        }, 2000);
    });
</script>

</body>
</html>