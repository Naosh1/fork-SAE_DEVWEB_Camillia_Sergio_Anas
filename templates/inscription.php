<?php
// inscription.php
session_start();

include_once "connexion/Connexion.php";
Connexion::initConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $mdp_confirmation = $_POST['mdp_confirmation'] ?? '';
    $role = 'client'; // Par défaut

    // Validation
    $erreurs = [];

    if (empty($nom)) $erreurs[] = "Le nom est requis";
    if (empty($prenom)) $erreurs[] = "Le prénom est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide";
    if (strlen($mdp) < 6) $erreurs[] = "Le mot de passe doit faire au moins 6 caractères";
    if ($mdp !== $mdp_confirmation) $erreurs[] = "Les mots de passe ne correspondent pas";

    // Vérifier si l'email existe déjà
    $bdd = Connexion::getBdd();
    $requete = $bdd->prepare("SELECT COUNT(*) FROM compte WHERE email = ?");
    $requete->execute([$email]);
    if ($requete->fetchColumn() > 0) {
        $erreurs[] = "Cet email est déjà utilisé";
    }

    if (empty($erreurs)) {
        // Insérer l'utilisateur
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $requete = $bdd->prepare(
            "INSERT INTO compte (nom, prenom, email, mdp, solde, role) 
             VALUES (?, ?, ?, ?, 0.00, ?)"
        );

        if ($requete->execute([$nom, $prenom, $email, $mdp_hash, $role])) {
            // Connecter automatiquement
            $id = $bdd->lastInsertId();
            $_SESSION['id'] = $id;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            header('Location: index.php');
            exit();
        } else {
            $erreurs[] = "Erreur lors de l'inscription";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – Buvette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .inscription-container {
            background: #fff;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .inscription-container h1 {
            text-align: center;
            margin-bottom: 0.3rem;
            color: #1e3c72;
        }

        .inscription-container p {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0.7rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #2a5298;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            background: #2a5298;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #1e3c72;
        }

        .error {
            background: #ffe1e1;
            color: #a40000;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        .error ul {
            margin: 0;
            padding-left: 20px;
        }

        .footer-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: #2a5298;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="inscription-container">
    <h1>Créer un compte</h1>
    <p>Rejoignez la communauté Buvette</p>

    <?php if (!empty($erreurs)) : ?>
        <div class="error">
            <ul>
                <?php foreach ($erreurs as $erreur) : ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="inscription.php">
        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="mdp">Mot de passe *</label>
                <input type="password" id="mdp" name="mdp" required
                       minlength="6">
                <small style="color: #666; font-size: 0.8rem;">6 caractères minimum</small>
            </div>
            <div class="form-group">
                <label for="mdp_confirmation">Confirmation *</label>
                <input type="password" id="mdp_confirmation"
                       name="mdp_confirmation" required>
            </div>
        </div>

        <button type="submit">S'inscrire</button>
    </form>

    <div class="footer-links">
        <p>
            Déjà un compte ?
            <a href="connexion.php">Se connecter</a>
        </p>
    </div>
</div>

</body>
</html>