<?php

include_once 'connexion.php';

class ModeleConnexion extends Connexion
{
    public static function connexion()
    {

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);

            $query = self::getBdd()->prepare("SELECT login, password FROM utilisateur WHERE login = :login");
            $query->bindValue(':login', $login);
            $query->execute();

            $user = $query->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['login'] = $login;
                echo 'Connexion reussite !';
            }else{
                echo 'Erreur : login ou mot de passe incorrect';
            }
        }
    }

    public function deconnexion() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?module=connexion&action=connexion');
        exit();
    }



    public function inscription()
    {
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $query = self::getBdd()->prepare('SELECT * FROM utilisateur WHERE login = ?');
            $query->execute([$login]);

            if ($query->fetch()) {
                echo 'Login déjà existant !';
            }else{
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $query = self::getBdd()->prepare('INSERT INTO utilisateur (login, password) VALUES (?, ?)');
                $query->execute([$login, $hash]);
                $reponse = $query->execute([$login, $hash]);
                if ($reponse) {
                    echo 'L utilisateur a bien inscrit !';
                    $_SESSION['login'] = $login;
                }else{
                    echo 'Une erreur est survenue lors de l\'inscription !';
                }

            }
        }
    }

}