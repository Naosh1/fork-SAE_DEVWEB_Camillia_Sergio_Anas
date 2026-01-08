<?php
    class Connexion {
        private static $bdd = null;
        private static $dsn = 'mysql:host=localhost;dbname=buvette;charset=utf8';
        private static $user = 'root';
        private static $password = '';

        public function __construct() {

        }

        public static function initConnexion() {
            self::$bdd = new PDO(self::$dsn,self::$user,self::$password);
        }

        protected static function getBdd() {
            if (!isset(self::$bdd)) {
                self::initConnexion();
            }
            return self::$bdd;
        }

    }
