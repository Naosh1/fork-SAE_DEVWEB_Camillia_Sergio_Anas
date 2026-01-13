<?php
    include_once 'Composants/CompMenu/Mod_Menu.php';
?>

<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style.css">
        <title>Buvette informatique</title>
    </head>

    <body>
        <header>
            <section class="section-white">
                <div class="card">
                    <h1>Buvette</h1>

                    <?php
                        $menu = new Mod_Menu();
                        echo $menu->affiche();
                    ?>

                </div>
            </section>
        </header>
        <main>
            <?php
                echo VueGenerique::getAffichage();
            ?>
        </main>

        <footer>
            <p>Copyright Buvette du 93 &copy; Tous droits réservés</p>
        </footer>
    </body>
</html>

