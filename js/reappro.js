function updateQty(btn, delta) {
    const input = btn.parentElement.querySelector('.qty-input');
    const card = btn.closest('.product-card');

    // Safety check
    if(!input || !card) return;

    let val = parseInt(input.value) + delta;
    if (val < 0) val = 0;

    input.value = val;

    // Visual feedback
    if (val > 0) {
        card.classList.add('active');
        card.style.borderColor = '#f59e0b';
        card.style.background = 'rgba(245, 158, 11, 0.05)';
    } else {
        card.classList.remove('active');
        card.style.borderColor = 'rgba(255, 255, 255, 0.08)';
        card.style.background = '#0f172a';
    }

    refreshCartSummary();
}

function refreshCartSummary() {
    let totalItems = 0;
    let totalPrice = 0;
    const summaryDiv = document.getElementById('cartSummaryList');

    if(!summaryDiv) return;

    summaryDiv.innerHTML = '';

    document.querySelectorAll('.product-card').forEach(card => {
        const qtyInput = card.querySelector('.qty-input');
        const qty = parseInt(qtyInput.value);
        const price = parseFloat(card.getAttribute('data-price'));
        const name = card.querySelector('h3').innerText;

        if (qty > 0) {
            totalItems += qty;
            totalPrice += (qty * price);

            const itemHTML = `
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:0.9rem; align-items:center;">
                    <span style="color:white;"><strong style="color:#f59e0b; margin-right:5px;">${qty}x</strong> ${name}</span>
                    <span style="font-weight:700; color:#94a3b8;">${(qty * price).toFixed(2)}€</span>
                </div>`;
            summaryDiv.innerHTML += itemHTML;
        }
    });

    if(totalItems === 0) {
        summaryDiv.innerHTML = '<p class="empty-msg" style="text-align:center; color:#94a3b8; font-style:italic;">Aucun article sélectionné</p>';
    }

    document.getElementById('totalQtyCount').innerText = totalItems;
    document.getElementById('totalPriceSum').innerText = totalPrice.toFixed(2);
}

function filterCatalogue() {
    const search = document.getElementById('searchBar').value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.querySelector('h3').innerText.toLowerCase();
        card.style.display = name.includes(search) ? 'flex' : 'none';
    });
}

function validerPanier() {
    const total = parseInt(document.getElementById('totalQtyCount').innerText);
    if (total <= 0) {
        const err = document.getElementById('error-message');
        if(err) {
            err.style.display = 'block';
            setTimeout(() => err.style.display = 'none', 3000);
        }
    } else {
        document.getElementById('mainOrderForm').submit();
    }
}