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
                        <h1> Buvette </h1>
                            <nav>
                                <?php
                                    echo " <a href='index.php?module=client&action=menu'> Menu </a>";
                                    echo " <a href='index.php?module=client&action=form_inscription_utilisateur'> S'inscrire </a>";
                                    echo " <a href='index.php?module=client&action=form_connexion_utilisateur'> Connexion </a>";
                                ?>
                            </nav>
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

