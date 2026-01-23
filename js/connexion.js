document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const loader = document.getElementById('loader');
    const loaderText = document.getElementById('loader-text');

    // Liste de messages fun pour l'ambiance Buvette
    const messages = [
        "Mise en perce du fût...",
        "Nettoyage des verres...",
        "Vérification des stocks...",
        "Préparation de la carte...",
        "Comptage des jetons..."
    ];

    if (form) {
        form.addEventListener('submit', function() {
            // Affichage du loader
            loader.classList.remove('hidden');
            loader.classList.add('flex');

            // Choisir un message aléatoire
            const randomMsg = messages[Math.floor(Math.random() * messages.length)];
            loaderText.innerText = randomMsg;

            // Désactivation du bouton pour éviter le spam
            const btn = form.querySelector('button');
            btn.disabled = true;
            btn.style.opacity = "0.5";
            btn.style.cursor = "not-allowed";
        });
    }
});