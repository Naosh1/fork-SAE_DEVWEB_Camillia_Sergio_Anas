<?php
session_start();

include_once 'modules/module_barman/Mod_barman.php';
include_once 'vue_generique.php';
include_once 'connexion/Connexion.php';

Connexion::initConnexion();

new Mod_barman();

include 'template.php';