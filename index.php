<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'vue_generique.php';
include_once 'connexion/Connexion.php';
Connexion::initConnexion();


if (!isset($_SESSION['id'])) {
    header('Location: templates/connexion.php');
    exit();
}

$bdd = Connexion::getBdd();
$stmt = $bdd->prepare("SELECT prenom, nom, email, role FROM compte WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: templates/connexion.php');
    exit();
}

$_SESSION['prenom'] = $user['prenom'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['email'] = $user['email'];
$_SESSION['login'] = $user['email'];
$_SESSION['role'] = $user['role'];

$login = $_SESSION['login'];
$role  = $_SESSION['role'];

switch ($role) {
    case 'gestionnaire':
    case 'admin':
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
    default:
        include_once 'modules/module_client/Mod_client.php';
        new Mod_client();
        include_once 'templates/template_client.php';
        break;
}

