<?php

// Message par défaut si non fourni
$message = $message ?? $_SESSION['error'] ?? "Une erreur inconnue est survenue.";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

<div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center">

    <div class="text-red-500 text-6xl mb-4">
        ⚠️
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-3">
        Oups… une erreur est survenue
    </h1>

    <p class="text-gray-600 mb-6">
        <?= htmlspecialchars($message) ?>
    </p>

    <div class="flex justify-center gap-4">
        <a href="javascript:history.back()"
           class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
            ⬅ Retour
        </a>

        <a href="index.php?action=accueil"
           class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
            🏠 Accueil
        </a>
    </div>

</div>

</body>
</html>
