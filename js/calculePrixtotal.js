function updateQty(btn, delta) {
    const input = delta > 0 ? btn.previousElementSibling : btn.nextElementSibling;
    let val = parseInt(input.value) + delta;
    if (val < 0) val = 0;
    input.value = val;
    calculateTotals();
}

function calculateTotals() {
    let totalItems = 0;
    let totalPrice = 0;

    document.querySelectorAll('.product-card').forEach(card => {
        const qty = parseInt(card.querySelector('.qty-input').value);
        const price = parseFloat(card.querySelector('.price-val').innerText.replace(',', '.'));
        if (qty > 0) {
            totalItems += qty;
            totalPrice += (qty * price);
        }
    });

    document.getElementById('totalItems').innerText = totalItems;
    document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);
    document.getElementById('cartSummary').style.opacity = totalItems > 0 ? "1" : "0.7";
}

window.addEventListener('DOMContentLoaded', calculateTotals);