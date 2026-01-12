<?php


include_once "cont_connexion.php";

class ModConnexion{

    private $controller;

    function __construct(){
        $this->controller = new ControllerConnexion();
        $this->controller->executerAction();
    }


}