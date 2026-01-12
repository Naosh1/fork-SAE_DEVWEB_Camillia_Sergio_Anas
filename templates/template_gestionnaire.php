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
if ($role !== 'gestionnaire') {
    header('Location: index.php');
    exit();
}

$prenom = $_SESSION['prenom'] ?? '';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Gestionnaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body {
            font-family: 'Inter', sans-serif;
        }</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<header class="bg-purple-800 text-white flex justify-between items-center p-4 shadow">
    <h1 class="text-xl font-bold">Tableau de bord Gestionnaire</h1>
    <a href="index.php?action=deconnexion"
       class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">Déconnexion</a>
</header>

<div class="flex flex-1">

    <aside class="w-64 bg-white shadow-md p-6 hidden md:block">
        <nav class="space-y-3">
            <a href="index.php?action=associations" class="block text-gray-700 hover:text-purple-700 font-medium">Associations</a>
            <a href="index.php?action=statistiques" class="block text-gray-700 hover:text-purple-700 font-medium">Statistiques</a>
        </nav>
    </aside>

    <main class="flex-1 p-6">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded">
                <?= htmlspecialchars($_SESSION['info']) ?>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Bienvenue, <?= htmlspecialchars($prenom)  ?></h2>
            <p class="text-gray-600">Sélectionnez une option dans le menu pour gérer le personnel, les produits, les
                ventes ou les associations.</p>
        </div>

    </main>
</div>

<footer class="bg-purple-800 text-white p-4 text-center">

</footer>

</body>
</html>
