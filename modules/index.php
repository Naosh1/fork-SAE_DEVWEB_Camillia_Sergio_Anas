<?php
include_once 'modules/module_gestionnaire';
include_once 'modules/module_client';
include_once 'modules/module_barman';

connexion::initConnexion();
session_start();
$role = $_SESSION['role'] ?? 'client';
$action = $_GET['action'] ?? 'accueil';


switch ($action) {
    case 'gestionnaire':
        $controleur = new ControleurGestionnaire(new ModeleGestionnaire(), new VueGestionnaire());
        break;
}


$controleur->gererAction($action);