<?php

class ModeleGestionnaire
{

    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=bar;charset=utf8',
            'root',
            ''
        );
    }

}