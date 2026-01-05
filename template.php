<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Buvette</title>
</head>

<body class="min-h-screen bg-gray-50 flex flex-col">
<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-store text-blue-600 mr-2"></i>
                    Buvette
                </h1>
                <p class="text-gray-600 text-sm mt-1">Gestion buvette</p>
            </div>

            <nav class="flex flex-wrap gap-2">
                <?php
                $menuItems = [
                        'accueil' => ['icon' => 'home', 'label' => 'Accueil'],
                        'produits' => ['icon' => 'box', 'label' => 'Produits'],
                        'stock' => ['icon' => 'chart-bar', 'label' => 'Stock'],
                        'ventes' => ['icon' => 'shopping-cart', 'label' => 'Ventes'],
                        'utilisateurs' => ['icon' => 'users', 'label' => 'Utilisateurs'],
                        'statistiques' => ['icon' => 'chart-line', 'label' => 'Statistiques'],
                        'associations' => ['icon' => 'landmark', 'label' => 'Associations']
                ];

                $currentAction = $_GET['action'] ?? 'accueil';

                foreach ($menuItems as $action => $item) {
                    $isActive = $currentAction === $action;
                    $bgClass = $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-500 hover:text-white';

                    echo '<a href="index.php?action=' . $action . '" 
                          class="px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200 flex items-center ' . $bgClass . '">
                          <i class="fas fa-' . $item['icon'] . ' mr-2"></i>' . $item['label'] . '
                          </a>';
                }
                ?>
            </nav>
        </div>
    </div>
</header>

<main class="flex-1 container mx-auto px-4 py-8">
    <?php
    echo VueGenerique::getAffichage();

    ?>
</main>

<footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4 text-center">
        <p class="text-gray-300">
            <i class="fas fa-copyright mr-2"></i>Copyright Buvette du 93 &copy; Tous droits réservés
        </p>
    </div>
</footer>

<script>
    document.addEventListener('click', function(e) {
        if (e.target.matches('a[href*="supprimer"]') ||
            e.target.closest('a[href*="supprimer"]')) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>