<?php

include_once 'connexion.php';

class ModeleConnexion extends Connexion
{
    public static function connexion()
    {
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $email = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);

            $query = self::getBdd()->prepare("SELECT * FROM compte WHERE email = :email");
            $query->bindValue(':email', $email);
            $query->execute();

            $user = $query->fetch();

            if ($user && password_verify($password, $user['mdp'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['login'] = $user['email'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['photo'] = $user['photo'];

                echo 'Connexion réussie !';
            } else {
                echo 'Erreur : email ou mot de passe incorrect';
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