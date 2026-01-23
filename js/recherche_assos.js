document.addEventListener('DOMContentLoaded', function() {
    const mainInput = document.getElementById('mainAssoInput');
    const resultsBox = document.getElementById('mainAssoResults');
    const catalogBtns = document.querySelectorAll('.catalog-btn');
    const selectorContainer = document.getElementById('selectorContainer');

    if (!mainInput || !resultsBox) return;

    // 1. Recherche
    mainInput.addEventListener('input', function() {
        const filter = this.value.trim().toUpperCase();
        const items = resultsBox.querySelectorAll('.result-item');
        resultsBox.style.display = filter.length > 0 ? 'block' : 'none';
        items.forEach(item => {
            item.style.display = item.innerText.toUpperCase().includes(filter) ? 'block' : 'none';
        });
    });

    // 2. Sélection
    resultsBox.addEventListener('click', function(e) {
        const item = e.target.closest('.result-item');
        if (!item) return;

        const id = item.getAttribute('data-id');
        const name = item.querySelector('strong').innerText;

        mainInput.value = "ASSOCIATION : " + name;
        resultsBox.style.display = 'none';

        // Activer les boutons
        catalogBtns.forEach(btn => {
            const baseUrl = btn.getAttribute('data-base-url');
            btn.href = `${baseUrl}&id_asso=${id}`;
            btn.classList.add('active');
            btn.classList.remove('disabled-link');

            const badge = btn.closest('.supplier-card').querySelector('.status-badge');
            badge.innerHTML = '<i class="fa-solid fa-check-circle text-orange-500"></i> Prêt pour commande';
            badge.style.color = "var(--electric-orange)";
        });
    });

    // 3. Gestion du clic sur bouton verrouillé
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.catalog-btn');
        if (btn && !btn.classList.contains('active')) {
            e.preventDefault();

            // Animation d'erreur sur le sélecteur d'asso
            selectorContainer.classList.add('shake');
            mainInput.placeholder = "CHOISISSEZ D'ABORD UNE ASSO !";

            setTimeout(() => {
                selectorContainer.classList.remove('shake');
                mainInput.placeholder = "Rechercher une association cliente...";
            }, 800);
        }

        // Fermer la liste si clic ailleurs
        if (!e.target.closest('.search-box')) {
            resultsBox.style.display = 'none';
        }
    });
});