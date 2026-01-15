<?php
    include_once "vue_generique.php";
    include_once "modules/module_client/Mod_client.php";
    include_once "modules/module_barman/Mod_barman.php";
    include_once "modules/module_gestionnaire/Mod_gestionnaire.php";
    include_once "connexion/Connexion.php";

    session_start();

    Connexion::initConnexion();

    $mod = isset($_GET['module']) ? $_GET['module'] : "client";
/*
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

    $bdd = Connexion::getBdd();
    $stmt = $bdd->prepare("SELECT prenom, nom, email, role FROM compte WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: connexion.php');
        exit();
    }
*/
    switch($mod) {
        case "client":
            $mod = new Mod_client();
            break;
        case "barman":
            $mod = new Mod_barman();
            break;
        case "gestionnaire" :
            $mod = new Mod_gestionnaire();
            break;
    }

    include "template.php";
