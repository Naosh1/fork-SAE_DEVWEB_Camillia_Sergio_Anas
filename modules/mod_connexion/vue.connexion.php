<?php

include_once 'VueGenerique.php';

class VueConnexion extends VueGenerique
{

    public function __construct()
    {
        parent::__construct();
    }

    function afficherInscriptionFormulaire()
    {
        echo "
    <form action='index.php?module=connexion&action=inscription' method='POST'>
        <label for='login'>Identifiant :</label>
        <input type='text' id='login' name='login' required>
    
        <label for='password'>Mot de passe :</label>
        <input type='password' id='password' name='password' required>
    
        <input type='hidden' name='action' value='inscription'>
        <button type='submit'>Continuer</button>
    </form>";

    }

    public function afficherInterfaceClient()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['action']) && $_GET['action'] === 'deconnexion') {
            session_unset();
            session_destroy();
            header('Location: ../../templates/index.php?module=connexion&action=connexion');
            exit();
        }
        if (!isset($_SESSION['login'])) {
            header('Location: ../../templates/index.php?module=connexion&action=connexion');
            exit();
        }

        echo '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Interface Client – Buvette</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gradient-to-br from-blue-900 to-blue-700 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center">
        <h1 class="text-3xl font-bold text-blue-900 mb-4">Bienvenue, ' . htmlspecialchars($_SESSION['login']) . ' !</h1>
        <p class="text-gray-600 mb-6">Vous êtes connecté à votre espace client.</p>

        <a href="?action=deconnexion"
           class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
            Déconnexion
        </a>
    </div>

    </body>
    </html>';
    }


    function afficherConnexionFormulaire()
    {
        echo "
    <form action='index.php?module=connexion' method='POST'>
        <label for='login'>Identifiant :</label>
        <input type='text' id='login' name='login' required>
    
        <label for='password'>Mot de passe :</label>
        <input type='password' id='password' name='password' required>
    
        <input type='hidden' name='action' value='connexion'>
        <button type='submit'>Connexion</button>
    </form>";
    }

    public function afficher_liste_nav()
    {
        if (isset($_SESSION['login'])) {
            echo '<p> Vous êtes connecté, bienvenue à toi : ' . $_SESSION["login"] . '</p>';
            echo '<a href="index.php?module=connexion&action=deconnexion">Deconnexion</a>"';
        } else {
            echo '<nav> 
            <a href="index.php?module=connexion&action=connexion">Se connecter</a>
            <br>
            <a href="index.php?module=connexion&action=inscription">Inscription</a>
              </nav>';
        }
    }

}

