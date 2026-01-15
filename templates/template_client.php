<?php

if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    session_unset();
    session_destroy();
    header('Location: connexion.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit();
}
$role = $_SESSION['role'] ?? '';
if ($role !== 'client') {
    header('Location: index.php');
    exit();
}
$prenom = $_SESSION['prenom'] ?? 'Utilisateur';
$nom = $_SESSION['nom'] ?? '';
$email = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil – AssoManager </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        body::before {
            content:"";
            position: fixed;
            top:0; left:0; width:100%; height:100%;
            background: linear-gradient(135deg, #0F172A, #1E3A8A, #0F172A, #1E3A8A);
            background-size: 400% 400%;
            z-index: -1;
            animation: gradientMove 30s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<div class="bg-white shadow-2xl rounded-3xl max-w-lg w-full p-8 text-center animate-fade-in">
    <h1 class="text-3xl font-bold text-blue-900 mb-2">Bienvenue, <?= htmlspecialchars($prenom) ?> !</h1>
    <p class="text-gray-700 mb-1">Vous êtes connecté en tant que <span class="font-semibold">client</span>.</p>
    <p class="text-gray-500 text-sm mb-6">Email: <?= htmlspecialchars($email) ?></p>

    <div class="grid grid-cols-1 gap-4">
        <a href="#"
           class="block bg-blue-600 text-white py-3 rounded-xl shadow-md hover:bg-blue-700 transition duration-200 font-medium">
            Voir mon solde
        </a>
        <a href="#"
           class="block bg-green-600 text-white py-3 rounded-xl shadow-md hover:bg-green-700 transition duration-200 font-medium">
            Commander une boisson
        </a>
        <a href="#"
           class="block bg-gray-600 text-white py-3 rounded-xl shadow-md hover:bg-gray-700 transition duration-200 font-medium">
            Historique des commandes
        </a>
    </div>

    <div class="mt-8 pt-6 border-t border-gray-200">
        <a href="index.php?action=deconnexion"
           class="inline-block bg-red-600 text-white px-8 py-2 rounded-xl shadow-md hover:bg-red-700 transition duration-200 font-semibold">
            Déconnexion
        </a>
    </div>
</div>

<style>
    @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    .animate-fade-in { animation: fadeIn 0.6s ease-out; }
</style>

</body>
</html>
