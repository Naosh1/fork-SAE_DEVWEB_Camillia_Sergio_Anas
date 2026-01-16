function updateQty(btn, delta) {
    const input = btn.parentElement.querySelector('.qty-input');
    const card = btn.closest('.product-card');
    let val = parseInt(input.value) + delta;

    if (val < 0) val = 0;
    input.value = val;

    // Ajoute une classe visuelle si le produit est dans le panier
    val > 0 ? card.classList.add('active') : card.classList.remove('active');

    refreshCartSummary();
}

function filterCatalogue() {
    const search = document.getElementById('searchBar').value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const title = card.querySelector('h3').innerText.toLowerCase();
        card.style.display = title.includes(search) ? 'flex' : 'none';
    });
}

function refreshCartSummary() {
    let totalItems = 0;
    let totalPrice = 0;
    const summaryDiv = document.getElementById('cartSummaryList');
    summaryDiv.innerHTML = '';

    document.querySelectorAll('.product-card').forEach(card => {
        const qty = parseInt(card.querySelector('.qty-input').value);
        const price = parseFloat(card.dataset.price);
        const name = card.querySelector('h3').innerText;

        if (qty > 0) {
            totalItems += qty;
            totalPrice += (qty * price);

            summaryDiv.innerHTML += `
                <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.85rem;">
                    <span>${name} <strong>x${qty}</strong></span>
                    <span>${(qty * price).toFixed(2)}€</span>
                </div>
            `;
        }
    });

    if(totalItems === 0) summaryDiv.innerHTML = '<p style="text-align:center; color:#475569; font-size:0.8rem;">Panier vide</p>';

    document.getElementById('totalQtyCount').innerText = totalItems;
    document.getElementById('totalPriceSum').innerText = totalPrice.toFixed(2);
}