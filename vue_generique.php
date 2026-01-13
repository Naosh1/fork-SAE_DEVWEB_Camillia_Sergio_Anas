<?php
class VueGenerique {
    private static $memoire;

    public static function getAffichage() {
        return self::$memoire;
    }

    public static function setAffichage($contenu) {
        self::$memoire = $contenu;

    }
}