<?php
session_start();

include_once 'modules/module_gestionnaire/Mod_gestionnaire.php';
include_once 'vue_generique.php';
include_once 'connexion/Connexion.php';

Connexion::initConnexion();

new Mod_gestionnaire();
include 'template.php';