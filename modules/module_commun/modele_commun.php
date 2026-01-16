<?php

class ModeleCommun extends Connexion
{
    public function getNbMessagesNonLus($id_user)
    {
        $sql = "SELECT COUNT(*) as total FROM messages WHERE id_destinataire = ? AND lu = 0";
        $stmt = self::getBdd()->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetch()['total'];
    }
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

            // On utilise fetchAll pour récupérer TOUS les barmans de l'asso
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur getBarmansParAssociation : ' . $e->getMessage());
            return [];
        }
    }
    public function enregistrerMessage($id_expediteur, $id_destinataire, $objet, $contenu)
    {
        try {
            $sql = "INSERT INTO messages (id_expediteur, id_destinataire, objet, contenu, date_envoi) 
                VALUES (?, ?, ?, ?, NOW())";

            $req = self::getBdd()->prepare($sql);

            return $req->execute([
                $id_expediteur,
                $id_destinataire,
                $objet,
                $contenu
            ]);
        } catch (PDOException $e) {
            error_log("Erreur enregistrerMessage : " . $e->getMessage());
            return false;
        }
    }

    public function getMesMessages($id_user)
    {
        $sql = "SELECT m.*, 
        exp.nom as nom, exp.prenom as prenom,
        dest.nom as dest_nom, dest.prenom as dest_prenom
        FROM messages m
        JOIN compte exp ON m.id_expediteur = exp.id
        JOIN compte dest ON m.id_destinataire = dest.id
        WHERE m.id_destinataire = :id_dest OR m.id_expediteur = :id_exp
        ORDER BY m.date_envoi DESC";

        $stmt = self::getBdd()->prepare($sql);

        $stmt->execute([
            'id_dest' => $id_user,
            'id_exp'  => $id_user
        ]);

        return $stmt->fetchAll();
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
