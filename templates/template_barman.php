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
if ($role !== 'barman') {
    header('Location: templates/index.php');
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

</html>