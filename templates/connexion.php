<?php
session_start();
include_once "../connexion/Connexion.php";
Connexion::initConnexion();

// Redirection si déjà connecté
if (isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mdp'] ?? '';

    if (!empty($email) && !empty($mdp)) {
        $bdd = Connexion::getBdd();
        $requete = $bdd->prepare("SELECT id, prenom, nom, email, mdp, role FROM compte WHERE email = ?");
        $requete->execute([$email]);
        $user = $requete->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mdp'])) {
            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login'] = $user['email'];

            header('Location: ../index.php');
            exit();
        } else {
            $erreur = "Identifiants incorrects";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | AssoManager</title>
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
        /* Style du Loader */
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

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
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

        /* Styles du Loader */
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
        <i class="fa-solid fa-beer-mug-empty text-blue-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl animate-pulse"></i>
    </div>
    <p id="loader-text" class="mt-8 text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] animate-pulse">
        Vérification des fûts...
    </p>
</div>

<div class="glass-card rounded-[2.5rem] max-w-md w-full p-10 shadow-2xl overflow-hidden relative">
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl"></div>

    <div class="relative z-10">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg shadow-blue-600/30">
                <i class="fa-solid fa-shield-halved text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter">Asso<span class="text-blue-500">Manager</span></h1>
            <p class="text-slate-400 font-medium mt-2">Accédez à votre tableau de bord</p>
        </div>

        <?php if ($erreur) : ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3 animate-pulse">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="post" id="loginForm" class="space-y-5">
            <div class="relative">
                <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="email" name="email" placeholder="Email"
                       class="input-glass w-full pl-12 pr-4 py-4 rounded-2xl outline-none" required>
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="password" name="mdp" placeholder="Mot de passe"
                       class="input-glass w-full pl-12 pr-4 py-4 rounded-2xl outline-none" required>
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-4 rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98]">
                Se connecter <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-white/5 text-center">
            <p class="text-slate-500 text-sm">
                Nouveau sur la plateforme ?
                <a href="inscription.php" class="text-blue-400 font-bold hover:text-blue-300 transition-colors ml-1">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = this.querySelector('input[type="email"]').value.trim();
        const mdp = this.querySelector('input[type="password"]').value;

        if (email === "" || mdp === "") {
            return;
        }

        e.preventDefault();
        const form = this;

        const loaderOverlay = document.getElementById('loader-overlay');
        loaderOverlay.style.display = 'flex';

        const messages = [
            "Vérification des fûts...",
            "Nettoyage du comptoir...",
            "Calcul du solde...",
            "Sécurisation de la session..."
        ];
        document.getElementById('loader-text').innerText = messages[Math.floor(Math.random() * messages.length)];

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Vérification...';

        setTimeout(function() {
            form.submit();
        }, 1500);
    });
</script>

</body>

</body>
</html>