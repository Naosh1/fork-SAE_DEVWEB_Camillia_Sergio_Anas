document.addEventListener("DOMContentLoaded", function() {
    // On initialise le premier menu (Client)
    const selectClient = document.querySelector('#select-client');
    if (selectClient) {
        new TomSelect(selectClient, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    }

    // On initialise le deuxième menu (Asso)
    const selectAsso = document.querySelector('#select-asso');
    if (selectAsso) {
        new TomSelect(selectAsso, {
            create: false
        });
    }
});