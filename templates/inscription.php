<?php
    session_start();
    include_once "../connexion/Connexion.php";
    Connexion::initConnexion();

    if (isset($_SESSION['id'])) {
        header('Location: ../index.php');
        exit();
    }

    $erreurs = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $mdp_conf = $_POST['mdp_confirmation'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';
        $role = 'client';

        // Validation des champs
        if (!$nom) $erreurs[] = "Le nom est requis";
        if (!$prenom) $erreurs[] = "Le prénom est requis";
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide";
        if (!$date_naissance) $erreurs[] = "La date de naissance est requise";

        // Vérifier l'âge
        if ($date_naissance) {
            $aujourdhui = new DateTime();
            $dob = new DateTime($date_naissance);
            $age = $dob->diff($aujourdhui)->y;
            if ($age < 18) $erreurs[] = "Vous devez être majeur pour vous inscrire (18 ans minimum)";
        }

        if (strlen($mdp) < 12) $erreurs[] = "Le mot de passe doit faire au moins 12 caractères";
        if ($mdp !== $mdp_conf) $erreurs[] = "Les mots de passe ne correspondent pas";

        $bdd = Connexion::getBdd();
        $check = $bdd->prepare("SELECT COUNT(*) FROM compte WHERE email=?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) $erreurs[] = "Cet email est déjà utilisé";

        if (!$erreurs) {
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
            $insert = $bdd->prepare("
                INSERT INTO compte (nom, prenom, date_naissance, email, mdp, solde, role)
                VALUES (?,?,?,?,?,0.00,?)
            ");
            if ($insert->execute([$nom, $prenom, $date_naissance, $email, $mdp_hash, $role])) {
                $id = $bdd->lastInsertId();
                $_SESSION['id'] = $id;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['login'] = $email;
                header('Location: ../index.php');
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
        <title>Inscription</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            body::before {
                content:""; position: fixed; top:0; left:0; width:100%; height:100%;
                background: linear-gradient(135deg,#0099FF,#094179,#0099FF,#094179);
                background-size:400% 400%; z-index:-1; animation:gradientMove 20s ease infinite;
            }
            @keyframes gradientMove {0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
        </style>
    </head>
    <body class="flex items-center justify-center min-h-screen p-4">

    <div class="bg-white shadow-2xl rounded-2xl max-w-lg w-full p-8">
        <h1 class="text-3xl font-bold text-center text-blue-800 mb-4">Créer un compte</h1>
        <p class="text-center text-gray-600 mb-6">Rejoignez la communauté Buvette</p>

        <?php if ($erreurs) : ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center font-medium">
                <ul class="list-disc list-inside">
                    <?php foreach ($erreurs as $erreur) : ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="nom" placeholder="Nom *" required
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                       class="sm:w-1/3 w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                <input type="text" name="prenom" placeholder="Prénom *" required
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                       class="sm:w-1/3 w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                <input type="date" name="date_naissance" placeholder="Date de naissance *" required
                       value="<?= htmlspecialchars($_POST['date_naissance'] ?? '') ?>"
                       class="sm:w-1/3 w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition transform active:scale-95">
            </div>

            <input type="email" name="email" placeholder="Email *" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">

            <div class="flex flex-col sm:flex-row gap-4">
                <input type="password" name="mdp" placeholder="Mot de passe *" required minlength="12"
                       class="sm:w-1/2 w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                <input type="password" name="mdp_confirmation" placeholder="Confirmation *" required
                       class="sm:w-1/2 w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold hover:bg-blue-400 transition transform active:scale-95">
                S'inscrire
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Déjà un compte ? <a href="connexion.php" class="text-blue-700 font-medium hover:underline">Se connecter</a>
        </p>
    </div>

    </body>
    </html>