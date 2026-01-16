<?php


if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
    session_unset();
    session_destroy();
    header('Location: templates/connexion.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    header('Location: templates/connexion.php');
    exit();
}
$role = $_SESSION['role'] ?? '';
if ($role !== 'client') {
    header('Location: templates/index.php');
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
    <title>Accueil – AssoManager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

<nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <span class="text-2xl font-bold text-blue-600">AssoManager</span>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-4 mr-4 border-r pr-4 border-slate-200">
                    <a href="index.php?module=client&action=messagerie" class="text-slate-600 hover:text-blue-600 font-medium">Messagerie</a>
                    <a href="index.php?module=client&action=monProfil" class="text-slate-600 hover:text-blue-600 font-medium">Mon Profil</a>
                </div>

                <div class="text-right">
                    <p class="text-sm font-bold text-slate-700"><?= htmlspecialchars($prenom . " " . $nom) ?></p>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($email) ?></p>
                </div>

                <a href="?action=deconnexion"
                   class="flex items-center gap-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded-xl transition-all duration-200 font-semibold text-sm border border-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Quitter
                </a>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Tableau de bord</h1>
        <p class="text-slate-500 mt-2">Bienvenue dans votre espace personnel, <?= htmlspecialchars($prenom) ?>.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="index.php?module=client&action=messagerie" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-500 transition-all">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold">Messagerie</h3>
            <p class="text-slate-500 text-sm mt-1">Consultez vos messages et contactez l'équipe.</p>
        </a>

        <a href="index.php?module=client&action=monProfil" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-500 transition-all">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold">Mon Profil</h3>
            <p class="text-slate-500 text-sm mt-1">Gérez vos informations personnelles et mot de passe.</p>
        </a>

    </div>
</main>

<footer class="mt-20 py-8 text-center text-slate-400 text-xs uppercase tracking-widest font-semibold">
    &copy; 2026 AssoManager - Tous droits réservés
</footer>

</body>
</html>