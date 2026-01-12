<?php

class VueGenerique {
    private static $affichage = '';

    public static function setAffichage($contenu) {
        self::$affichage = $contenu;
    }

    public static function afficherVue() {
        echo self::$affichage;
    }
}
?>