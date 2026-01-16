/**
 * Gestion du filtrage dynamique des contacts
 * Recherche par ID, Nom, Prénom ou Email
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('contactSearch');
    const select = document.getElementById('destinataireSelect');
    const counter = document.getElementById('resultCounter');

    if (searchInput && select) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const options = Array.from(select.options);
            let matches = [];

            for (let i = 1; i < options.length; i++) {
                const searchData = options[i].getAttribute('data-search') || "";
                const isMatch = searchData.includes(term);

                options[i].style.display = isMatch ? 'block' : 'none';
                options[i].disabled = !isMatch;

                if (isMatch) {
                    matches.push(options[i]);
                }
            }

            if (term !== "") {
                if (matches.length === 1) {
                    select.value = matches[0].value;
                } else if (matches.length > 1) {
                    const currentOption = select.options[select.selectedIndex];
                    if (currentOption.style.display === 'none') {
                        select.value = "";
                    }
                }
            }
            if (counter) {
                const count = matches.length;
                counter.textContent = term === "" ? "" : `${count} contact(s) trouvé(s)`;
                counter.className = count === 0 ? "text-red-500" : "text-blue-500";
            }
            if (matches.length === 0 && term !== "") {
                searchInput.classList.add('border-red-500');
            } else {
                searchInput.classList.remove('border-red-500');
            }
        });
    }
});