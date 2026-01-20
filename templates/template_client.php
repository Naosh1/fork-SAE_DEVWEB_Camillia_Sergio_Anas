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
$role = $_SESSION['role_effectif'] ?? '';
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
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style.css">
        <title>Buvette informatique</title>
    </head>


    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <?php
        echo VueGenerique::getAffichage();
        ?>
    </main>


</html>