document.addEventListener('DOMContentLoaded', function () {
    if (typeof TomSelect !== "undefined") {
        new TomSelect('#select-client', { create: false, dropdownParent: 'body' });
    }

    const form = document.getElementById('clientForm');
    const overlay = document.getElementById('cyber-overlay');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            overlay.style.display = 'flex';
            overlay.style.opacity = '1';
            setTimeout(() => {
                form.submit();
            }, 1500);
        });
    }

    const toast = document.getElementById('success-toast');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 600);
        }, 3000);
    }

});