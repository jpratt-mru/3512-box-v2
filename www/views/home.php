<?php include '../views/partials/headerjs.php'; ?>

<!-- External JS files -->
<script type="module" src="/js/app.js"></script>
<script type="module" src="/js/search.js"></script>
<script type="module" src="/js/photos.js"></script>
<script type="module" src="/js/photoDetail.js"></script>
<script type="module" src="/js/countryDetail.js"></script>

<div id="app">
    <!-- Page Header -->
    <header id="page-header">
        <h1>Welcome to the Travel Photo Website</h1>
        <p>Explore stunning photography from around the world. Search for photos by continent, country, or city.</p>
    </header>

    <!-- Instructions Section -->
    <section id="instructions">
        <h2>How to Search</h2>
        <p>Start typing in the search bar below to find photos based on places like:</p>
        <ul>
            <li>Continent: e.g., "Asia"</li>
            <li>Country: e.g., "India"</li>
            <li>City: e.g., "Paris"</li>
        </ul>
        <p>The results will show places starting with your search term and the number of photos available for each location.</p>
        <p>Click on any result to view the photos!</p>
    </section>

    <!-- New Section for Dynamic Message (appears when photos view is displayed) -->
    <section id="dynamic-message" style="display:none;">
        <h2>These are the images for your Results. Enjoy the view!</h2>
    </section>

    <!-- Search View Section -->
    <div id="search-view">
        <input type="text" id="search-bar" placeholder="Search places (continent, country, city)" />
        <div id="search-results"></div>
    </div>

    <!-- Matching Photos View Section -->
    <div id="photos-view" style="display:none;">
        <h2 id="search-term-text"></h2>
        <div id="loading-spinner" style="display:none;">Loading...</div> <!-- Loading Spinner -->

        <!-- New buttons for sorting and toggling grid/list view -->
        <div id="photo-view-options">
            <button id="toggle-view" class="button">Toggle View</button> <!-- Toggle grid/list view -->

            <!-- Sort buttons for country and city -->
            <button id="sort-country-asc" class="button">Sort by Country (Asc)</button>
            <button id="sort-country-desc" class="button">Sort by Country (Desc)</button>
            <button id="sort-city-asc" class="button">Sort by City (Asc)</button>
            <button id="sort-city-desc" class="button">Sort by City (Desc)</button>
        </div>

        <div id="photos-container"></div>

        <!-- Add the "Back to Search" button here -->
        <button id="back-to-search" class="button">Back to Search</button>
    </div>
    <div id="error-message" style="display:none; color:red;"></div>

    <!-- Photo Detail View -->
    <div id="photo-detail-container" style="display: none;">
        <div id="photo-detail-view">
            <!-- Photo Image -->
            <img id="photo-detail-image" src="https://via.placeholder.com/600x600?text=No+Image+Available" alt="Photo Detail" />

            <!-- Photo Description -->
            <p id="photo-description"></p>

            <!-- Exif Details -->
            <p id="photo-exif"></p>

            <!-- Photo Creator -->
            <p id="photo-creator"></p>

            <!-- Average Rating -->
            <p id="photo-rating"></p>

            <!-- City -->
            <p id="photo-city"></p>

            <!-- Latitude & Longitude -->
            <p id="photo-lat-lng"></p>

            <!-- Google Maps Static Image -->
            <img id="google-maps-image" src="https://via.placeholder.com/600x600?text=No+Image+Available" alt="Google Maps Image" />

            <!-- Flagged Status -->
            <div id="photo-flagged"></div>

            <!-- Close Button -->
            <button id="close-photo-detail">Close</button>
        </div>
    </div>

    <!-- Country Detail View -->
    <div id="country-detail-container" style="display: none;">
        <div id="country-detail-view">
            <!-- Country Name -->
            <h2 id="country-name"></h2>

            <!-- Country Flag -->
            <img id="country-flag" src="https://via.placeholder.com/600x600?text=No+Image+Available" alt="Country Flag" />

            <!-- Country Population -->
            <p id="country-population"></p>

            <!-- Country Capital -->
            <p id="country-capital"></p>

            <!-- Country Currency -->
            <p id="country-currency"></p>

            <!-- Country Languages -->
            <p id="country-languages"></p>

            <!-- Country Neighbours -->
            <p id="country-neighbours"></p>

            <!-- Country Description -->
            <p id="country-description"></p>

            <!-- Close Button -->
            <button id="close-country-detail">Close</button>
        </div>
    </div>

</div>

<?php include '../views/partials/footer.php'; ?>