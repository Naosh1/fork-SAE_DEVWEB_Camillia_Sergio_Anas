<?php
<<<<<<< HEAD
    include_once 'Composants/CompMenu/Cont_Menu.php';
=======

include_once 'Composants/CompMenu/Cont_Menu.php';
>>>>>>> travailClient

class Mod_Menu {
    private $controleur;
    private $affichage;

    public function __construct() {
        $this->controleur = new Cont_Menu();
        $this->controleur->exec();
        $this->affichage = $this->controleur->getAffichage();
    }

<<<<<<< HEAD
        public function affiche() {
            return $this->affichage;
        }
    }

=======
    public function affiche() {
        return $this->affichage;
    }
}
>>>>>>> travailClient
