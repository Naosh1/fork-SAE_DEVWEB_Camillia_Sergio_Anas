document.addEventListener('DOMContentLoaded', function() {
    const mainInput = document.getElementById('mainAssoInput');
    const resultsBox = document.getElementById('mainAssoResults');
    const catalogBtns = document.querySelectorAll('.catalog-btn');

    if (!mainInput || !resultsBox) return;

    // 1. Filtrage ultra-rapide
    mainInput.addEventListener('input', function() {
        const filter = this.value.trim().toUpperCase();
        const items = resultsBox.querySelectorAll('.result-item');

        if (filter.length > 0) {
            resultsBox.style.display = 'block';
            items.forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(filter) ? 'block' : 'none';
            });
        } else {
            resultsBox.style.display = 'none';
        }
    });

    // 2. Sélection par délégation d'événement
    resultsBox.addEventListener('click', function(e) {
        // On récupère l'élément .result-item le plus proche du clic
        const item = e.target.closest('.result-item');
        if (!item) return;

        const assoId = item.getAttribute('data-id');
        // On nettoie le nom pour enlever le texte du solde entre parenthèses
        const assoName = item.innerText.split('(')[0].trim();

        // Mise à jour de l'interface
        mainInput.value = "ACTIF : " + assoName;
        mainInput.style.borderColor = "#f59e0b";
        resultsBox.style.display = 'none';

        // Activation des boutons catalogue
        catalogBtns.forEach(btn => {
            const baseUrl = btn.getAttribute('data-base-url');
            if (baseUrl && baseUrl !== "#") {
                btn.href = `${baseUrl}&id_asso=${assoId}`;
                btn.classList.add('active');
            }
        });
    });

    // 3. Fermeture si clic à l'extérieur
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            resultsBox.style.display = 'none';
        }
    });
});