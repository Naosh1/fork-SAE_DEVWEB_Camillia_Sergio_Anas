<?php
    class Vue_Menu {
        public $affichage = '';

        public function __construct() {

        }

        public function gen_menu() {
            ob_start();
            ?>

            <nav>
                <ul>
<!--                    <li><a href = "index.php?module=joueurs&action=liste"> Joueurs</a></li>-->
<!--                    <li><a href = "index.php?module=equipes&action=liste"> Equipes</a></li>-->
                    <li><a href = "index.php?module=connexion&action=connexion"> Connexion</a></li>
                </ul>
            </nav>

<!--            <div class='auth-section'>-->
<!--                --><?php //if (isset($_SESSION['loginUtilisateur'])):?>
<!--                    <p>-->
<!--                        Connecté:-->
<!--                        <strong>--><?//echo htmlspecialchars($_SESSION['loginUtilisateur']); ?><!--</strong>-->
<!--                    </p>-->
<!--                    <a href = "index.php?module=connexion&action=deconnexion"> Se déconnecter </a>-->
<!--                --><?php //else: ?>
<!--                    <a href = "index.php?module=connexion&action=connexion" > Se connecter </a>-->
<!--                --><?php //endif; ?>
<!--            </div>-->

            <?php
            $this->affichage = ob_get_clean();
        }
    }



