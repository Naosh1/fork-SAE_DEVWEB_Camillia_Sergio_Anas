<?php
// index.php
session_start();

// DEBUG - à désactiver en production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Si déconnexion demandée
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: connexion.php');
    exit();
}

// 2. Vérifier si connecté
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    // PAS CONNECTÉ -> page de connexion
    header('Location: connexion.php');
    exit();
}

// 3. Inclure la connexion
include_once '../connexion/Connexion.php';
Connexion::initConnexion();

// 4. Rediriger selon le rôle
$role = $_SESSION['role'];

switch($role) {
    case 'gestionnaire':
    case 'admin':
        include_once '../vue_generique.php';
        include_once '../modules/module_gestionnaire/Mod_gestionnaire.php';

        new Mod_gestionnaire();
        break;

    case 'barman':
        // Inclure le module barman
        include_once 'modules/module_barman/Mod_barman.php';

        // À créer si nécessaire
        $mod = new Mod_barman();
        break;

    case 'client':
    default:
        // Inclure le module client
        include_once 'modules/module_client/Mod_client.php';

        // À créer si nécessaire
        $mod = new Mod_client();
        break;
}