document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.querySelector('input[name="q"]');
    const progressBar = document.getElementById('searchProgressBar');
    const progressContainer = document.getElementById('searchProgress');
    const grid = document.getElementById('resultsGrid');
    const resultsContainer = document.getElementById('resultsContainer');

    // --- 1. GESTION DE L'EFFACEMENT (INSTANTANÉ) ---
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Si le champ est vidé manuellement
            if (this.value.trim() === "") {
                if (grid) {
                    grid.style.transition = "opacity 0.3s ease";
                    grid.style.opacity = "0";

                    // On attend la fin de l'animation pour recharger ou changer l'état
                    setTimeout(() => {
                        // Option A : On recharge pour afficher l'écran "En attente de recherche"
                        window.location.href = "index.php?module=gestionnaire&action=rechercherPrix";
                    }, 300);
                }
            }
        });
    }

    // --- 2. BARRE DE PROGRESSION (SOUMISSION) ---
    if (searchForm && progressBar) {
        searchForm.addEventListener('submit', () => {
            progressContainer.style.display = 'block';
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 95) {
                    clearInterval(interval);
                } else {
                    width += (100 - width) * 0.1;
                    progressBar.style.width = width + '%';
                }
            }, 150);
        });
    }

    // --- 3. GESTION DU TRI ---
    const sortSelect = document.getElementById('sortResults');
    if (sortSelect && grid) {
        sortSelect.addEventListener('change', function () {
            const cards = Array.from(grid.children);
            const sortBy = this.value;

            if (sortBy === 'default') return;

            cards.sort((a, b) => {
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);
                const deliveryA = parseInt(a.dataset.delivery);
                const deliveryB = parseInt(b.dataset.delivery);

                if (sortBy === 'price-asc') return priceA - priceB;
                if (sortBy === 'price-desc') return priceB - priceA;
                if (sortBy === 'delivery-asc') return deliveryA - deliveryB;
                return 0;
            });

            grid.style.opacity = '0';

            setTimeout(() => {
                grid.innerHTML = '';
                cards.forEach((card, index) => {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    card.style.animation = null;
                    card.style.animationDelay = `${index * 0.05}s`;
                    grid.appendChild(card);
                });
                grid.style.opacity = '1';
            }, 200);
        });
    }
});