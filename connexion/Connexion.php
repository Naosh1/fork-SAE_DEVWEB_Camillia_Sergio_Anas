<?php
    class Connexion {
        private static $bdd = null;
        private static $dsn = 'mysql:dbname=dutinfopw201633;host=database-etudiants.iut.univ-paris8.fr';
        private static $user = 'dutinfopw201633';
        private static $password = 'mejetuju';

        public function __construct() {

        }

        public static function initConnexion() {
            self::$bdd = new PDO(self::$dsn,self::$user,self::$password);
        }

        public static function getBdd() {
            if (!isset(self::$bdd)) {
                self::initConnexion();
            }
            return self::$bdd;
        }
    }
