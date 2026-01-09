<?php
    include_once "Vue_generique.php";
    include_once "modules/module_client/Mod_client.php";
    include_once "modules/module_barman/Mod_barman.php";
    include_once "modules/module_gestionnaire/Mod_gestionnaire.php";
    include_once "connexion/Connexion.php";

    session_start();

    Connexion::initConnexion();

    $mod = isset($_GET['module']) ? $_GET['module'] : "client";

    $vueGen = new VueGenerique();

    switch($mod){
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

