<?php
// connexion.php
session_start();

// Inclure la connexion à la base
include_once "../connexion/Connexion.php";
Connexion::initConnexion();

// Si l'utilisateur est déjà connecté, rediriger vers l'accueil
if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    header('Location: index.php');
    exit();
}

// Gérer la soumission du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emailUtilisateurConnexion'])) {
    include_once "../commun/accés/CompteAcces.php";

    $compteAcces = new CompteAcces();

    // Appeler la méthode de connexion (à adapter)
    $email = $_POST['emailUtilisateurConnexion'];
    $mdp = $_POST['mdpUtilisateurConnexion'];

    // Récupérer l'utilisateur depuis la base
    $bdd = Connexion::getBdd();
    $requete = $bdd->prepare("SELECT id, prenom, nom, email, mdp, role FROM compte WHERE email = ?");
    $requete->execute([$email]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mdp, $user['mdp'])) {
        // Connexion réussie
        $_SESSION['id'] = $user['id'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Rediriger selon le rôle
        header('Location: index.php');
        exit();
    } else {
        $erreur = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Buvette</title>
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
        }

        .login-container {
            background: #fff;
            padding: 2.5rem;
            width: 100%;
            max-width: 380px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 0.3rem;
            color: #1e3c72;
        }

        .login-container p {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
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
            transition: background 0.2s, transform 0.1s;
        }

        button:hover {
            background: #1e3c72;
            transform: translateY(-1px);
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

        .footer-links a:hover {
            text-decoration: underline;
        }

        .error {
            background: #ffe1e1;
            color: #a40000;
            padding: 0.6rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Buvette</h1>
    <p>Connexion à votre espace</p>

    <?php if (isset($erreur)) : ?>
        <div class="error">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="connexion.php">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="emailUtilisateurConnexion"
                   placeholder="exemple@mail.com"
                   required>
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password"
                   id="mdp"
                   name="mdpUtilisateurConnexion"
                   placeholder="••••••••"
                   required>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <div class="footer-links">
        <p>
            Pas de compte ?
            <a href="inscription.php">
                Créer un compte
            </a>
        </p>
    </div>
</div>

</body>
</html>