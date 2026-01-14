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


</body>
</html>
