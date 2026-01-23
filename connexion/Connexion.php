<?php
    class Connexion {

        private static $bdd = null;
        private static $dsn = 'mysql:dbname=dutinfopw201633;host=database-etudiants.iut.univ-paris8.fr';
        private static $user = 'dutinfopw201633';
        private static $password = 'mejetuju';

//        private static $bdd = null;
//        private static $dsn = 'mysql:dbname=buvette;host=127.0.0.1';
//        private static $user = 'root';
//        private static $password = '';

 //   private static $bdd = null;
  //  private static $dsn = 'mysql:dbname=dutinfopw201655;host=database-etudiants.iut.univ-paris8.fr';
  //  private static $user = 'dutinfopw201655';
  //  private static $password = 'hevenequ';

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
