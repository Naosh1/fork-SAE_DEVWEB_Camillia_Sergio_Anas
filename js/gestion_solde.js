document.addEventListener('DOMContentLoaded', () => {
    const montantInput = document.getElementById('js-montant');
    const typeOp = document.getElementById('js-type-op');
    const soldeMax = parseFloat(document.getElementById('js-solde-max').value);
    const alerte = document.getElementById('js-alerte');
    const btnSubmit = document.getElementById('js-btn-submit');

    const verifierSolde = () => {
        const montant = parseFloat(montantInput.value) || 0;
        if (typeOp.value === 'retirer' && montant > soldeMax) {
            alerte.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = "0.5";
        } else {
            alerte.classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = "1";
        }
    };

    montantInput.addEventListener('input', verifierSolde);
    typeOp.addEventListener('change', verifierSolde);
});