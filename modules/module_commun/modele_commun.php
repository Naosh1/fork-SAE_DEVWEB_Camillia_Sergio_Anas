<?php

class ModeleCommun extends Connexion
{



    public function getUtilisateur($id)
    {
        $sql = "SELECT *, mdp FROM compte WHERE id = ?";
        $query = self::getBdd()->prepare($sql);
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserPhoto($id, $nom_image)
    {
        $sql = "UPDATE compte SET photo = ? WHERE id = ?";
        $query = self::getBdd()->prepare($sql);
        return $query->execute([$nom_image, $id]);
    }

    public function updateUserInfos($id, $nom, $prenom, $email, $tel, $password = null)
    {
        if ($password) {
            $sql = "UPDATE compte SET nom = ?, prenom = ?, email = ?, tel = ?, mdp = ? WHERE id = ?";
            $query = self::getBdd()->prepare($sql);
            return $query->execute([$nom, $prenom, $email, $tel, $password, $id]);
        } else {
            $sql = "UPDATE compte SET nom = ?, prenom = ?, email = ?, tel = ? WHERE id = ?";
            $query = self::getBdd()->prepare($sql);
            return $query->execute([$nom, $prenom, $email, $tel, $id]);
        }
    }
}
