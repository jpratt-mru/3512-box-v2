import { handlePhotoView } from './photos.js';

export function handleSearch() {
    const searchInput = document.getElementById('search-bar');
    const searchResultsContainer = document.getElementById('search-results');
    const searchView = document.getElementById('search-view');
    const photosView = document.getElementById('photos-view');
    const dynamicMessage = document.getElementById('dynamic-message');
    const loadingSpinner = document.getElementById('loading-spinner');
    const instructions = document.getElementById('instructions');
    const pageHeader = document.getElementById('page-header');
    const searchTermText = document.getElementById('search-term-text');

    searchInput.addEventListener('input', debounceSearch);

    let searchTimeout;

    // Debounced search to limit API calls
    function debounceSearch(event) {
        const query = event.target.value.trim();  // Remove whitespace around the query

        // If input is cleared, hide results and reset the view
        if (query.length === 0) {
            searchResultsContainer.textContent = '';  // Use textContent to clear the search results
            searchView.style.display = 'block';  // Show the search view
            photosView.style.display = 'none';  // Hide the photos view
            dynamicMessage.style.display = 'none';  // Hide the dynamic message
            pageHeader.style.display = 'block';  // Show the page header
            instructions.style.display = 'block';  // Show the instructions
            return; // Exit early
        }

        if (query.length < 2) return; // Only search if 2 or more characters are typed

        clearTimeout(searchTimeout); // Clear any previous timeouts
        searchTimeout = setTimeout(() => {
            // Check if results for the query are in sessionStorage
            if (sessionStorage.getItem(query)) {
                displaySearchResults(JSON.parse(sessionStorage.getItem(query)));
            } else {
                fetchSearchResults(query);  // Fetch new results if not cached
            }
        }, 500); // Wait for 500ms before performing the search
    }

    // Fetch search results from API
    async function fetchSearchResults(query) {
        try {
            const response = await fetch(`/api/search?query=${query}`);
            const results = await response.json();

            // Cache the results in sessionStorage
            sessionStorage.setItem(query, JSON.stringify(results));
            displaySearchResults(results);
        } catch (error) {
            console.error('Error fetching search results:', error);
        }
    }

    // Display search results dynamically
    function displaySearchResults(results) {
        // Clear previous results
        searchResultsContainer.textContent = ''; // Use textContent to clear the results

        if (results.length > 0) {
            results.forEach(result => {
                // Create a container for each result
                const resultDiv = document.createElement('div');
                resultDiv.classList.add('search-result');
                resultDiv.setAttribute('data-location', result.name);

                // Add result name and count
                const resultText = document.createTextNode(`${result.name} (${result.count} ${result.count === 1 ? 'photo' : 'photos'})`);
                resultDiv.appendChild(resultText);

                // Add hover effect and click event listener
                resultDiv.addEventListener('click', function () {
                    const location = resultDiv.getAttribute('data-location');
                    
                    // Hide the search view and show photos view
                    searchView.style.display = 'none';
                    photosView.style.display = 'block';

                    // Hide the header and instructions section
                    pageHeader.style.display = 'none';
                    instructions.style.display = 'none';

                    // Show the dynamic message in place of the search instructions
                    dynamicMessage.style.display = 'block';

                    // Set the search term in the photos view title
                    searchTermText.textContent = `Photos for ${location}`;

                    // Pass the location to photo.js to load the photos
                    handlePhotoView(location);
                });

                // Change cursor to pointer on hover
                resultDiv.style.cursor = 'pointer';

                // Append the result div to the container
                searchResultsContainer.appendChild(resultDiv);
            });
        } else {
            // Display a "no results found" message
            const noResultsMessage = document.createElement('div');
            noResultsMessage.textContent = 'No results found';
            searchResultsContainer.appendChild(noResultsMessage);
        }
    }
}
