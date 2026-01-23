document.addEventListener('DOMContentLoaded', () => {
    // --- Sélecteurs ---
    const cards = document.querySelectorAll('.op-card');
    const btnSubmit = document.querySelector('.btn-cyber');
    const mInput = document.getElementById('mInput');
    const soldeForm = document.getElementById('soldeForm');

    const confirmModal = document.getElementById('customModal');
    const loaderModal = document.getElementById('loaderModal');
    const errorModal = document.getElementById('errorModal');

    const modalMessage = document.getElementById('modalMessage');
    const confirmBtn = document.getElementById('confirmBtn');

    // --- 1. Gestion du switch Recharger / Encaisser ---
    cards.forEach(card => {
        card.addEventListener('click', function () {
            cards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input').checked = true;

            // Changement dynamique des couleurs
            if (this.dataset.op === 'retirer') {
                btnSubmit.style.background = 'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)';
                btnSubmit.style.boxShadow = '0 10px 25px -5px rgba(239, 68, 68, 0.4)';
            } else {
                btnSubmit.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
                btnSubmit.style.boxShadow = '0 10px 25px -5px rgba(37, 99, 235, 0.4)';
            }
        });
    });

    // --- 2. Interception de la soumission du formulaire ---
    soldeForm.onsubmit = function(e) {
        e.preventDefault();

        const m = parseFloat(mInput.value);
        const op = document.querySelector('input[name="operation"]:checked').value;
        const currentSolde = parseFloat(window.currentSolde); // Défini dans la vue PHP

        // Validation montant
        if (isNaN(m) || m <= 0) {
            showErrorCustom('Veuillez entrer un montant valide');
            return false;
        }

        // Validation solde (si retrait)
        if (op === 'retirer' && m > currentSolde) {
            showErrorCustom('🚨 SOLDE INSUFFISANT POUR CETTE OPÉRATION');
            return false;
        }

        // Préparation et affichage de la modale de confirmation
        modalMessage.innerHTML = `Confirmer la transaction de <span class="text-white underline font-[900]">${m.toFixed(2)}€</span> ?`;

        // Couleur du bouton de la modale selon l'opération
        confirmBtn.className = (op === 'retirer')
            ? "flex-1 py-4 rounded-2xl bg-red-600 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-900/40"
            : "flex-1 py-4 rounded-2xl bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-900/40";

        confirmModal.classList.remove('hidden');
    };

    // --- 3. Action finale : Confirmation et Loader ---
    confirmBtn.onclick = function() {
        // Cacher la modale de confirmation
        confirmModal.classList.add('hidden');

        // Afficher le loader Cyber
        loaderModal.classList.remove('hidden');

        // Petit délai pour l'immersion avant envoi
        setTimeout(() => {
            soldeForm.submit();
        }, 850);
    };
});

// --- Fonctions Globales (Accessibles depuis le HTML) ---

function addVal(v) {
    const input = document.getElementById('mInput');
    input.value = (parseFloat(input.value || 0) + v).toFixed(2);
}

function closeModal() {
    document.getElementById('customModal').classList.add('hidden');
    if(document.getElementById('errorModal')) {
        document.getElementById('errorModal').classList.add('hidden');
    }
}

function showErrorCustom(msg) {
    const errorModal = document.getElementById('errorModal');
    if (errorModal) {
        document.getElementById('errorMessage').innerText = msg;
        errorModal.classList.remove('hidden');
    } else {
        alert(msg); // Fallback si la modale custom n'est pas dans le HTML
    }
}