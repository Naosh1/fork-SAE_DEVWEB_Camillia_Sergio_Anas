/**
 * Gestion de l'interface mail
 */

function searchMessages() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.message-line');

    rows.forEach(row => {
        const text = row.getAttribute('data-search');
        if (text.includes(input)) {
            row.classList.remove('hidden-filter');
        } else {
            row.classList.add('hidden-filter');
        }
        if(row.nextElementSibling) row.nextElementSibling.classList.add('hidden');
    });
}

function filterMessages(filter, element) {
    document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('active'));
    element.classList.add('active');

    const rows = document.querySelectorAll('.message-line');
    rows.forEach(row => {
        if (row.nextElementSibling.classList.contains('detail-view')) {
            row.nextElementSibling.classList.add('hidden');
        }

        let shouldShow = false;
        switch(filter) {
            case 'all':
                shouldShow = !row.classList.contains('is-trashed');
                break;
            case 'sent':
                shouldShow = (row.getAttribute('data-type') === 'sent' && !row.classList.contains('is-trashed'));
                break;
            case 'starred':
                shouldShow = row.classList.contains('is-starred');
                break;
            case 'trash':
                shouldShow = row.classList.contains('is-trashed');
                break;
        }

        shouldShow ? row.classList.remove('hidden-filter') : row.classList.add('hidden-filter');
    });
}

function toggleStar(event, el) {
    event.stopPropagation();
    el.classList.toggle('text-amber-500');
    el.classList.toggle('fa-regular');
    el.classList.toggle('fa-solid');
    el.closest('.message-line').classList.toggle('is-starred');
}

function moveToTrash(event, el) {
    event.stopPropagation();
    const row = el.closest('.message-line');
    const detail = row.nextElementSibling;

    if(!row.classList.contains('is-trashed')) {
        row.classList.add('is-trashed');
        row.classList.add('hidden-filter');
    } else {
        if(confirm("Supprimer définitivement ?")) {
            row.remove();
            detail.remove();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.message-line').forEach(line => {
        line.addEventListener('click', function() {
            this.nextElementSibling.classList.toggle('hidden');

            const subject = this.querySelector('.msg-subject');
            if (subject) {
                subject.style.fontWeight = '400';
                subject.style.opacity = '0.6';
            }
        });
    });
});