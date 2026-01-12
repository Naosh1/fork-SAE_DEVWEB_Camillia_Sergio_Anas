<?php

include_once 'VueGenerique.php';
class VueConnexion extends VueGenerique
{

    public function __construct()
    {
        parent::__construct();
    }

    function afficherInscriptionFormulaire()
    {
        echo "
    <form action='index.php?module=connexion&action=inscription' method='POST'>
        <label for='login'>Identifiant :</label>
        <input type='text' id='login' name='login' required>
    
        <label for='password'>Mot de passe :</label>
        <input type='password' id='password' name='password' required>
    
        <input type='hidden' name='action' value='inscription'>
        <button type='submit'>Continuer</button>
    </form>";

    }

    function afficherConnexionFormulaire()
    {
        echo "
    <form action='index.php?module=connexion' method='POST'>
        <label for='login'>Identifiant :</label>
        <input type='text' id='login' name='login' required>
    
        <label for='password'>Mot de passe :</label>
        <input type='password' id='password' name='password' required>
    
        <input type='hidden' name='action' value='connexion'>
        <button type='submit'>Connexion</button>
    </form>";
    }

    public function afficher_liste_nav()
    {
        if (isset($_SESSION['login'])) {
            echo '<p> Vous êtes connecté, bienvenue à toi : ' . $_SESSION["login"] . '</p>';
            echo '<a href="index.php?module=connexion&action=deconnexion">Deconnexion</a>"';
        } else {
            echo '<nav> 
            <a href="index.php?module=connexion&action=connexion">Se connecter</a>
            <br>
            <a href="index.php?module=connexion&action=inscription">Inscription</a>
              </nav>';
        }
    }


    public
    function afficherVue()
    {
        echo $this->getVueGenerique();
    }
}

?>