<?php

class CodeValidationAcces
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = Connexion::getBdd();
    }

    /**
     * Génère un code de validation à 4 chiffres
     * Valide pendant 1 minute
     */
    public function genererCode($compteId)
    {
        // Générer un code aléatoire à 4 chiffres
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Date d'expiration : 1 minute
        $dateExpiration = date('Y-m-d H:i:s', strtotime('+1 minute'));

        // Mettre à jour le compte avec le nouveau code
        $stmt = $this->bdd->prepare(
            "UPDATE compte 
             SET code_validation = :code, 
                 code_expiration = :expiration
             WHERE id = :id"
        );

        $stmt->execute([
            ':code' => $code,
            ':expiration' => $dateExpiration,
            ':id' => $compteId
        ]);

        return [
            'code' => $code,
            'expiration' => $dateExpiration
        ];
    }

    /**
     * Récupère le code actuel d'un utilisateur
     */
    public function getCodeActuel($compteId)
    {
        $stmt = $this->bdd->prepare(
            "SELECT code_validation, code_expiration 
             FROM compte 
             WHERE id = :id 
             AND code_validation IS NOT NULL 
             AND code_expiration > NOW()"
        );
        $stmt->execute([':id' => $compteId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'code' => $result['code_validation'],
                'expiration' => $result['code_expiration']
            ];
        }

        return null;
    }

    /**
     * Vérifie si un code est valide pour un compte
     */
    public function verifierCode($code, $compteId)
    {
        $stmt = $this->bdd->prepare(
            "SELECT id, nom, prenom, solde 
             FROM compte 
             WHERE id = :id 
             AND code_validation = :code 
             AND code_expiration > NOW()"
        );

        $stmt->execute([
            ':id' => $compteId,
            ':code' => $code
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie un code sans connaître le compte (pour barman)
     */
    public function verifierCodeGlobal($code)
    {
        $stmt = $this->bdd->prepare(
            "SELECT id, nom, prenom, solde, code_expiration
             FROM compte 
             WHERE code_validation = :code 
             AND code_expiration > NOW()"
        );

        $stmt->execute([':code' => $code]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Invalide le code après utilisation
     */
    public function invaliderCode($compteId)
    {
        $stmt = $this->bdd->prepare(
            "UPDATE compte 
             SET code_validation = NULL, 
                 code_expiration = NULL 
             WHERE id = :id"
        );

        $stmt->execute([':id' => $compteId]);
    }

    /**
     * Nettoie les codes expirés (optionnel)
     */
    public function nettoyerCodesExpires()
    {
        $stmt = $this->bdd->prepare(
            "UPDATE compte 
             SET code_validation = NULL, 
                 code_expiration = NULL 
             WHERE code_expiration < NOW()"
        );

        $stmt->execute();
    }
}