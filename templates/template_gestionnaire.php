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
if ($role !== 'gestionnaire') {
    header('Location: index.php');
    exit();
}

$prenom = $_SESSION['prenom'] ?? '';

?>

<?php
?>
<!DOCTYPE html>
<html lang="fr" style="background-color: #020617 !important;"> <head>
    <script>
        document.documentElement.style.display = 'none';
    </script>

    <meta charset="UTF-8">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { montserrat: ['Montserrat', 'sans-serif'] } } }
        }
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.documentElement.style.display = 'block';
        });
    </script>

    <style>
        body { background-color: #020617 !important; margin: 0; }
    </style>
</head>
<body class="bg-[#020617] text-white font-montserrat">