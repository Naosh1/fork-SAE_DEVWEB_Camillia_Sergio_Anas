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
if ($role !== 'barman') {
    header('Location: index.php');
    exit();
}

$prenom = $_SESSION['prenom'] ?? 'Barman';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interface Barman – Buvette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-amber-900 to-amber-700 min-h-screen flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center">
    <h1 class="text-3xl font-bold text-amber-900 mb-4">Interface Barman</h1>
    <p class="text-gray-600 mb-6">Bienvenue, <?= htmlspecialchars($prenom) ?> !</p>

    <div class="space-y-4 mt-6">
        <a href="#"
           class="block bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition font-medium">
            📋 Commandes en attente
        </a>
        <a href="#"
           class="block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
            🍹 Préparer une boisson
        </a>
        <a href="#"
           class="block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
            📊 Stocks disponibles
        </a>
    </div>

    <div class="mt-8 pt-6 border-t border-gray-200">
        <a href="index.php?action=deconnexion"
           class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
            Déconnexion
        </a>
    </div>
</div>

</body>
</html>