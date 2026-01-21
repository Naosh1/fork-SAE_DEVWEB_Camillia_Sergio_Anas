document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialisation TomSelect
    if (typeof TomSelect !== "undefined") {
        new TomSelect('#select-client', { create: false, dropdownParent: 'body' });
    }

    // 2. Gestion de l'animation de confirmation (CORRIGÉ)
    const form = document.getElementById('clientForm');
    const overlay = document.getElementById('cyber-overlay');

    if (form) {
        form.addEventListener('submit', function (e) {
            // On bloque l'envoi immédiat pour laisser le temps à l'animation
            e.preventDefault();

            // On affiche l'overlay
            overlay.style.display = 'flex';
            overlay.style.opacity = '1';

            // On attend 1.5 seconde avant d'envoyer réellement les données
            setTimeout(() => {
                form.submit();
            }, 1500);
        });
    }

    // 3. Gestion du Toast de succès
    const toast = document.getElementById('success-toast');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 600);
        }, 3000);
    }
});