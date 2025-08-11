// app.js
import { handleSearch } from './search.js';
import { handlePhotoView } from './photos.js';
import { handleCountryDetail } from './countryDetail.js';  // Import the handleCountryDetail function

// Initialize search functionality
handleSearch();

// When the page loads with the #photos hash, show the photos view
if (window.location.hash === '#photos') {
    handlePhotoView();
}

// Wait for DOM content to load
document.addEventListener('DOMContentLoaded', function () {
    // Get all country links and attach click event listeners to them
    const countryLinks = document.querySelectorAll('.country-link');

    countryLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            const country = event.target.getAttribute('data-country');  // Get the country name or code
            handleCountryDetail(country);  // Call the handleCountryDetail function when a country link is clicked
            event.preventDefault();  // Prevent the photo click from triggering its handler
        });
    });
});
