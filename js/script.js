// Variables globales
let searchTimeout;

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById("search");
    const resultats = document.getElementById("resultats");
    const loader = document.getElementById("loader-overlay");

    // Focus automatique sur la recherche
    if (search) {
        search.focus();

        // Gestion de la recherche avec debounce
        search.addEventListener("input", function() {
            clearTimeout(searchTimeout);

            const searchTerm = this.value.trim();

            if (searchTerm.length > 0) {
                searchTimeout = setTimeout(() => {
                    performSearch(searchTerm, resultats);
                }, 300); // 300ms de délai
            } else {
                resultats.innerHTML = "";
            }
        });

        // Gestion de la touche Entrée
        search.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const searchTerm = this.value.trim();
                if (searchTerm.length > 0) {
                    performSearch(searchTerm, resultats);
                }
            }
        });
    }

    // Gestion des clics sur les boutons
    document.addEventListener("click", function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains("join-btn")) {
            handleJoinAssociation(btn.dataset.id, search);
        }

        if (btn.classList.contains("enter-btn")) {
            handleEnterAssociation(btn.dataset.id, loader);
        }
    });
});

// Fonction pour effectuer la recherche
function performSearch(searchTerm, resultatsElement) {
    fetch("index.php?search=" + encodeURIComponent(searchTerm))
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(html => {
            resultatsElement.innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            resultatsElement.innerHTML = `
                <div class="no-result">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    Erreur lors de la recherche. Veuillez réessayer.
                </div>`;
        });
}

// Fonction pour rejoindre une association
function handleJoinAssociation(associationId, searchElement) {
    const formData = new FormData();
    formData.append('rejoindre_asso', associationId);

    fetch("index.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            if (result === "JOINED") {
                // Rafraîchir les résultats si on a une barre de recherche
                if (searchElement && searchElement.value.trim().length > 0) {
                    searchElement.dispatchEvent(new Event("input"));
                }

                // Afficher une notification visuelle
                showNotification("Association rejointe avec succès !", "success");
            } else {
                showNotification("Erreur lors de l'adhésion", "error");
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification("Erreur réseau", "error");
        });
}

// Fonction pour entrer dans une association
function handleEnterAssociation(associationId, loaderElement) {
    if (loaderElement) {
        loaderElement.style.display = "flex";
    }

    const formData = new FormData();
    formData.append('choisir_asso', associationId);

    fetch("index.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            if (result === "OK") {
                // Redirection après un délai
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                if (loaderElement) {
                    loaderElement.style.display = "none";
                }

                if (result === "NOT_MEMBER") {
                    showNotification("Vous n'êtes pas membre de cette association", "warning");
                } else {
                    showNotification("Erreur d'accès à cette association", "error");
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (loaderElement) {
                loaderElement.style.display = "none";
            }
            showNotification("Erreur réseau", "error");
        });
}

// Fonction pour afficher des notifications
function showNotification(message, type = "info") {
    // Supprimer toute notification existante
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fa-solid ${getIconForType(type)}"></i>
        <span>${message}</span>
    `;

    // Styles pour la notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getColorForType(type)};
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        font-weight: 600;
        font-size: 0.9rem;
    `;

    document.body.appendChild(notification);

    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);

    // Ajouter les animations CSS si elles n'existent pas
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// Fonctions utilitaires
function getIconForType(type) {
    switch(type) {
        case 'success': return 'fa-check-circle';
        case 'error': return 'fa-exclamation-circle';
        case 'warning': return 'fa-exclamation-triangle';
        default: return 'fa-info-circle';
    }
}

function getColorForType(type) {
    const colors = {
        'success': '#22c55e',
        'error': '#ef4444',
        'warning': '#f59e0b',
        'info': '#3b82f6'
    };
    return colors[type] || colors.info;
}