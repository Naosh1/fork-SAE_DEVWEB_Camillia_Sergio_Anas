<?php

class ModeleCommun extends Connexion
{

    public function getBarmansParAssociation($id_association)
    {
        try {
            $sql = "SELECT c.id, c.nom, c.prenom, c.email, c.tel, c.photo, a.role, asso.nom AS nom_association
                FROM compte c
                JOIN appartient a ON c.id = a.compte_id
                JOIN association asso ON a.association_id = asso.id
                WHERE a.association_id = :id_asso 
                AND a.role = 'barman'";

            $stmt = self::getBdd()->prepare($sql);
            $stmt->execute([':id_asso' => $id_association]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur getBarmansParAssociation : ' . $e->getMessage());
            return [];
        }
    }


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
