<?php
session_start();
include_once "../connexion/Connexion.php";
Connexion::initConnexion();

if (isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp   = $_POST['mdp'] ?? '';

    if ($email && $mdp) {
        $bdd = Connexion::getBdd();
        $requete = $bdd->prepare("SELECT id, prenom, nom, email, mdp, role FROM compte WHERE email = ?");
        $requete->execute([$email]);
        $user = $requete->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mdp'])) {
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
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        body::before {
            content:""; position: fixed; top:0; left:0; width:100%; height:100%;
            background: linear-gradient(135deg,#0099FF,#094179,#0099FF,#094179);
            background-size:400% 400%; z-index:-1; animation:gradientMove 20s ease infinite;
        }
        @keyframes gradientMove {0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
<div class="bg-white shadow-2xl rounded-2xl max-w-md w-full p-8">
    <h1 class="text-3xl font-bold text-center text-blue-800 mb-4">AssoManager</h1>
    <p class="text-center text-gray-600 mb-6">Connectez-vous à votre espace</p>

    <?php if ($erreur) : ?>
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center font-medium">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
        <input type="email" name="email" placeholder="Email"
               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        <input type="password" name="mdp" placeholder="Mot de passe"
               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        <button type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold hover:bg-blue-400 transition">
            Se connecter
        </button>
    </form>

    <p class="mt-6 text-center text-gray-600">
        Pas de compte ? <a href="inscription.php" class="text-blue-700 font-medium hover:underline">Créer un compte</a>
    </p>
</div>
</body>
</html>