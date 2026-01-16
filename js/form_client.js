document.addEventListener("DOMContentLoaded", function() {
    const selectClient = document.querySelector('#select-client');
    if (selectClient) {
        new TomSelect(selectClient, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    }

    const selectAsso = document.querySelector('#select-asso');
    if (selectAsso) {
        new TomSelect(selectAsso, {
            create: false
        });
    }
});