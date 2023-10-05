const site = "https://urlshort.be/";

const btn = document.querySelector('#btn');
const result = document.querySelector('#result');
btn.addEventListener('click', () => {
    const url = document.getElementById('url').value;
    const donnees = {
        url: url
    };

    const options = {
        method: 'POST',
        body: new URLSearchParams(donnees), // Utilise URLSearchParams pour encoder les données correctement
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded' // Définit le type de contenu comme "application/x-www-form-urlencoded"
        }
    };

    // Envoyer la requête
    fetch('api/create.php', options)
        .then(response => response.json())
        .then(data => {
            if (data.short_url == "error") {
                result.textContent = "Une erreur est survenue";
                result.href = "#";

            }
            if (data.short_url == "url") {
                result.textContent = "Veuillez entrer une URL valide";
                result.href = "#";
            }
            else {
                navigator.clipboard.writeText(site + data.short_url);
                // Traitement de la réponse
                result.textContent = site + data.short_url;
                result.href = site + data.short_url;
            }
        })
        .catch(error => {
            console.error(error);
        });
});
