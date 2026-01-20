<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once 'vue_generique.php';
    include_once 'connexion/Connexion.php';

    Connexion::initConnexion();

    /*
    |--------------------------------------------------------------------------
    | 1. Gestion du module demandé
    |--------------------------------------------------------------------------
    */
    $module = $_SESSION['role'] ?? 'client';

    /*
    |--------------------------------------------------------------------------
    | 2. Pages accessibles SANS connexion
    |--------------------------------------------------------------------------
    */
    $publicModules = ['connexion', 'inscription'];

    if (!in_array($module, $publicModules)) {

        // Utilisateur non connecté
        if (!isset($_SESSION['id'])) {
            header('Location: templates/connexion.php');
            exit();
        }

        // Recharge infos utilisateur
        $bdd = Connexion::getBdd();
        $stmt = $bdd->prepare(
            "SELECT prenom, nom, email, role FROM compte WHERE id = ?"
        );
        $stmt->execute([$_SESSION['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            session_unset();
            session_destroy();
            header('Location: templates/connexion.php');
            exit();
        }

        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom']    = $user['nom'];
        $_SESSION['email']  = $user['email'];
        $_SESSION['role']   = $user['role'];
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Sécurité par rôle
    |--------------------------------------------------------------------------
    */
    switch ($module) {
        case 'admin':
            if ($_SESSION['role'] !== 'admin') {
                header('Location: index.php?module=client');
                exit();
            }
            break;

        case 'gestionnaire':
            if (!in_array($_SESSION['role'], ['admin', 'gestionnaire'])) {
                header('Location: index.php?module=client');
                exit();
            }
            break;

        case 'barman':
            if (!in_array($_SESSION['role'], ['admin', 'barman'])) {
                header('Location: index.php?module=client');
                exit();
            }
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Chargement du module + template
    |--------------------------------------------------------------------------
    */

    switch ($module) {

        case 'admin':
            include_once 'modules/module_admin/Mod_admin.php';
            new Mod_admin();
            include_once 'templates/template_admin.php';
            break;

        case 'gestionnaire':
            include_once 'modules/module_gestionnaire/Mod_gestionnaire.php';
            new Mod_gestionnaire();
            include_once 'templates/template_gestionnaire.php';
            break;

        case 'barman':
            include_once 'modules/module_barman/Mod_barman.php';
            new Mod_barman();
            include_once 'templates/template_barman.php';
            break;

        case 'client':
            include_once 'modules/module_client/Mod_client.php';
            new Mod_client();
            include_once 'templates/template_client.php';
            break;

        default:
            header('Location: index.php?module=client');
            exit();
    }
