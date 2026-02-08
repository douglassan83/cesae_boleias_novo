document.addEventListener("DOMContentLoaded", function () {

    console.log("scrollCity carregado com sucesso");

    const locations = [

        "Águeda",
"Albergaria-a-Velha",
"Anadia",
"Arouca",
"Aveiro",
"Espinho",
"Estarreja",
"Gondomar",
"Ílhavo",
"Murtosa",
"Oliveira de Azeméis",
"Oliveira do Bairro",
"Ovar",
"Porto",
"Santa Maria da Feira",
"São João da Madeira",
"Sever do Vouga",
"Vale de Cambra",
"Vila Nova de Gaia",
"Vagos"
    ];

    function renderList(items, input, list) {
        list.innerHTML = "";

        items.forEach(item => {
            const li = document.createElement("li");
            li.className = "list-group-item";
            li.textContent = item;

            li.addEventListener("click", () => {
                input.value = item;
                list.classList.add("d-none");
            });

            list.appendChild(li);
        });

        list.classList.toggle("d-none", items.length === 0);
    }

    function autocomplete(inputId, listId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);

        if (!input || !list) return;

        // 👉 MOSTRA TODAS AO CLICAR
        input.addEventListener("focus", function () {
            renderList(locations, input, list);
        });

        // 👉 FILTRA AO ESCREVER
        input.addEventListener("input", function () {
            const value = this.value.toLowerCase();

            const filtered = locations.filter(loc =>
                loc.toLowerCase().includes(value)
            );

            renderList(filtered, input, list);
        });

        // 👉 FECHA AO CLICAR FORA
        document.addEventListener("click", function (e) {
            if (!input.contains(e.target) && !list.contains(e.target)) {
                list.classList.add("d-none");
            }
        });
    }

    autocomplete("pickup_location", "pickup_list");
    autocomplete("destination_location", "destination_list");

});
