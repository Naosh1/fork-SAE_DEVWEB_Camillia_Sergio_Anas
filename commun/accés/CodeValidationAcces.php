

<?php

class CodeValidationAcces
{
   private $bdd;


   public function __construct()
   {
       $this->bdd = Connexion::getBdd();

       if ($this->bdd === null) {
           throw new Exception("Erreur : Impossible de se connecter à la base de données");
       }

       $this->bdd->exec("SET time_zone = '+01:00'");
   }

   public function genererCode($compteId)
   {
       $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

       $stmt = $this->bdd->prepare(
           "UPDATE compte 
            SET code_validation = :code, 
                code_expiration = NOW() + INTERVAL 1 MINUTE
            WHERE id = :id"
       );

       $stmt->execute([
           ':code' => $code,
           ':id' => $compteId
       ]);

       $stmt = $this->bdd->prepare(
           "SELECT code_expiration FROM compte WHERE id = :id"
       );
       $stmt->execute([':id' => $compteId]);
       $result = $stmt->fetch(PDO::FETCH_ASSOC);

       return [
           'code' => $code,
           'expiration' => $result['code_expiration']
       ];
   }

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
