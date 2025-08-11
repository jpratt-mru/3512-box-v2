export function handleCountryDetail(country) {
    const countryDetailView = document.getElementById('country-detail-view');
    const loadingSpinner = document.getElementById('loading-spinner');
    const photosView = document.getElementById('photos-view');
    const photoDetailView = document.getElementById('photo-detail-view');
    const countryName = document.getElementById('country-name');
    const countryFlag = document.getElementById('country-flag');
    const countryDescription = document.getElementById('country-description');
    const population = document.getElementById('country-population');
    const capital = document.getElementById('country-capital');
    const currency = document.getElementById('country-currency');
    const languages = document.getElementById('country-languages');
    const neighbours = document.getElementById('country-neighbours');
    const errorMessage = document.getElementById('error-message');
    const closeCountryDetailButton = document.getElementById('close-country-detail');
    const dynamicMessage = document.getElementById('dynamic-message');

    // Update the dynamic message
    dynamicMessage.textContent = "You are now viewing the country details. Enjoy!";
    dynamicMessage.style.fontWeight = 'bold';
    dynamicMessage.style.fontSize = '1.5rem';
    dynamicMessage.style.display = 'block';
    dynamicMessage.style.margin = '20px';

    photoDetailView.style.display = 'none';
    photosView.style.display = 'none';

    if (errorMessage) {
        errorMessage.style.display = 'none';
    }

    // Show the loading spinner
    loadingSpinner.style.display = 'block';
    countryDetailView.style.display = 'none';

    fetch(`/api/country?country=${country}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Country not found');
            }
            return response.json();
        })
        .then(countryDetail => {
            // Update country name
            countryName.textContent = countryDetail.name;

            // Update country flag
            countryFlag.src = countryDetail.flagUrl || 'https://via.placeholder.com/150';
            countryFlag.alt = `Flag of ${countryDetail.name}`;
            countryFlag.style.marginBottom = '20px'; // Add space below the flag

            // Update country description
            countryDescription.textContent = '';
            const descriptionHeading = document.createElement('strong');
            descriptionHeading.textContent = "Country Description";
            descriptionHeading.style.display = 'block'; // Separate heading
            descriptionHeading.style.marginBottom = '10px';
            countryDescription.appendChild(descriptionHeading);
            countryDescription.appendChild(document.createTextNode(countryDetail.description || "No description available."));
            countryDescription.style.marginBottom = '20px'; // Add space below the description

            // Update population
            population.textContent = '';
            const populationHeading = document.createElement('strong');
            populationHeading.textContent = "Population";
            populationHeading.style.display = 'block';
            populationHeading.style.marginBottom = '10px';
            population.appendChild(populationHeading);
            population.appendChild(document.createTextNode(
                countryDetail.population ? countryDetail.population.toLocaleString() : 'Data not available'
            ));
            population.style.marginBottom = '20px';

            // Update capital
            capital.textContent = '';
            const capitalHeading = document.createElement('strong');
            capitalHeading.textContent = "Capital";
            capitalHeading.style.display = 'block';
            capitalHeading.style.marginBottom = '10px';
            capital.appendChild(capitalHeading);
            capital.appendChild(document.createTextNode(countryDetail.capital || 'Not available'));
            capital.style.marginBottom = '20px';

            // Update currency
            currency.textContent = '';
            const currencyHeading = document.createElement('strong');
            currencyHeading.textContent = "Currency";
            currencyHeading.style.display = 'block';
            currencyHeading.style.marginBottom = '10px';
            currency.appendChild(currencyHeading);
            currency.appendChild(document.createTextNode(countryDetail.currency || 'Not available'));
            currency.style.marginBottom = '20px';

            // Update languages
            languages.textContent = '';
            const languagesHeading = document.createElement('strong');
            languagesHeading.textContent = "Languages";
            languagesHeading.style.display = 'block';
            languagesHeading.style.marginBottom = '10px';
            languages.appendChild(languagesHeading);
            languages.appendChild(document.createTextNode(
                countryDetail.languages ? countryDetail.languages.map(lang => lang.split('-')[0]).join(', ') : 'No languages available'
            ));
            languages.style.marginBottom = '20px';

            // Update neighbours
            neighbours.textContent = '';
            const neighboursHeading = document.createElement('strong');
            neighboursHeading.textContent = "Neighbours";
            neighboursHeading.style.display = 'block';
            neighboursHeading.style.marginBottom = '10px';
            neighbours.appendChild(neighboursHeading);
            neighbours.appendChild(document.createTextNode(
                Array.isArray(countryDetail.neighbours)
                    ? countryDetail.neighbours.join(', ')
                    : (countryDetail.neighbours ? countryDetail.neighbours.split(',').map(neighbour => neighbour.trim()).join(', ') : 'No neighbouring countries')
            ));
            neighbours.style.marginBottom = '20px';

            countryDetailView.style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading country details:', error);
            countryName.textContent = `Error loading details for ${country}.`;

            if (errorMessage) {
                errorMessage.style.display = 'block';
                errorMessage.textContent = `Error loading details for ${country}. Please try again later.`;
            }

            countryDetailView.style.display = 'none';
            photosView.style.display = 'block';
        })
        .finally(() => {
            loadingSpinner.style.display = 'none';
        });

    // Add event listener to close the country detail view
    closeCountryDetailButton.addEventListener('click', () => {
        countryDetailView.style.display = 'none';
        photosView.style.display = 'block';
    });
}
