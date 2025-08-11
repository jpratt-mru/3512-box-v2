import { handleCountryDetail } from './countryDetail.js'; // Import the function for handling country details
import { loadPhotoDetail } from './photoDetail.js'; // Import the function for handling photo detail

export function handlePhotoView(location) {
    const photosView = document.getElementById('photos-view');
    const searchTermText = document.getElementById('search-term-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const photosContainer = document.getElementById('photos-container');
    const backToSearchButton = document.getElementById('back-to-search');
    const sortCountryAscButton = document.getElementById('sort-country-asc');
    const sortCountryDescButton = document.getElementById('sort-country-desc');
    const sortCityAscButton = document.getElementById('sort-city-asc');
    const sortCityDescButton = document.getElementById('sort-city-desc');
    const toggleViewButton = document.getElementById('toggle-view');
    const searchInput = document.getElementById('search-bar');
    const searchResultsContainer = document.getElementById('search-results');

    let photos = []; // To store the photos for sorting
    let currentSort = { field: 'country', order: 'asc' }; // Default sorting option is 'country' ascending

    // Apply styles to the buttons for a smaller, visually appealing design
    function styleButtons() {
        const buttons = [
            sortCountryAscButton,
            sortCountryDescButton,
            sortCityAscButton,
            sortCityDescButton,
            toggleViewButton
        ];

        buttons.forEach(button => {
            button.style.fontSize = '14px';
            button.style.padding = '8px 12px';
            button.style.borderRadius = '4px';
            button.style.margin = '5px';
            button.style.backgroundColor = '#007BFF';
            button.style.color = 'white';
            button.style.border = 'none';
            button.style.cursor = 'pointer';
            button.style.transition = 'background-color 0.3s ease';
        });

        buttons.forEach(button => {
            button.addEventListener('mouseover', () => {
                button.style.backgroundColor = '#0056b3';
            });

            button.addEventListener('mouseout', () => {
                button.style.backgroundColor = '#007BFF';
            });
        });
    }

    if (backToSearchButton) {
        backToSearchButton.addEventListener('click', () => {
            photosView.style.display = 'none';
            document.getElementById('search-view').style.display = 'block';
            document.getElementById('dynamic-message').style.display = 'none';
            document.getElementById('page-header').style.display = 'block';
            document.getElementById('instructions').style.display = 'block';

            photosContainer.classList.remove('grid-view', 'list-view');
            photosContainer.classList.add('grid-view');
            toggleViewButton.textContent = 'Switch to List View';

            searchInput.value = '';
            searchResultsContainer.textContent = ''; // Clear the search results container
        });
    }

    loadingSpinner.style.display = 'block';
    photosView.style.display = 'block';

    searchTermText.textContent = `Photos for ${location}...`;

    fetch(`/api/photos?location=${location}`)
        .then(response => response.json())
        .then(fetchedPhotos => {
            photos = fetchedPhotos;

            searchTermText.textContent = `Photos for ${location} (${photos.length} photos)`;

            displayPhotos(photos);
        })
        .catch(error => {
            console.error('Error loading matching photos:', error);
            searchTermText.textContent = `Error loading photos for ${location}.`;
        })
        .finally(() => {
            loadingSpinner.style.display = 'none';
        });

    function displayPhotos(photos) {
        const cloudinaryBaseUrl = "https://res.cloudinary.com/dng6vk65h/image/upload/w_150,h_150,c_fill/";
        photosContainer.textContent = '';

        if (photos.length === 0) {
            const noPhotosMessage = document.createElement('div');
            noPhotosMessage.textContent = 'No photos found for this location.';
            photosContainer.appendChild(noPhotosMessage);
            return;
        }

        photos.forEach((photo, index) => {
            const photoItem = document.createElement('div');
            photoItem.classList.add('photo-item');
            photoItem.dataset.imageid = photo.ImageID;

            const photoImage = document.createElement('img');
            photoImage.src = `${cloudinaryBaseUrl}${photo.Path}`;
            photoImage.alt = photo.Title;
            photoImage.classList.add('photo-image');
            photoImage.dataset.imageid = photo.ImageID;

            const photoTitle = document.createElement('div');
            photoTitle.textContent = `${index + 1}. ${photo.Title}`;

            const photoCreator = document.createElement('div');
            const creatorStrong = document.createElement('strong');
            creatorStrong.textContent = "By:";
            photoCreator.appendChild(creatorStrong);
            photoCreator.appendChild(document.createTextNode(` ${photo.UserFirstName} ${photo.UserLastName}`));

            const countryLink = document.createElement('a');
            countryLink.href = '#';
            countryLink.textContent = photo.country;
            countryLink.classList.add('country-link');
            countryLink.dataset.country = photo.country;

            const countryWrapper = document.createElement('div');
            countryWrapper.appendChild(countryLink);

            if (currentSort.field === 'city') {
                const cityInfo = document.createElement('div');
                const cityStrong = document.createElement('strong');
                cityStrong.textContent = "City:";
                cityInfo.appendChild(cityStrong);
                cityInfo.appendChild(document.createTextNode(` ${photo.city}`));
                photoItem.appendChild(cityInfo);
            }

            if (photo.flagged) {
                const flaggedStatus = document.createElement('div');
                flaggedStatus.classList.add('flagged');
                const warningIcon = document.createElement('span');
                warningIcon.classList.add('warning-icon');
                warningIcon.textContent = "🚩";
                flaggedStatus.appendChild(warningIcon);
                flaggedStatus.appendChild(document.createTextNode(' Flagged'));
                photoItem.appendChild(flaggedStatus);
            }

            photoItem.appendChild(photoImage);
            photoItem.appendChild(photoTitle);
            photoItem.appendChild(photoCreator);
            photoItem.appendChild(countryWrapper);
            photosContainer.appendChild(photoItem);
        });
    }

    photosContainer.addEventListener('click', function (event) {
        if (event.target.closest('.photo-image')) {
            const photo = event.target.closest('.photo-image').getAttribute('data-imageid');
            showPhotoDetail(photo);
            window.location.hash = `#photo-detail-${photo}`;
            event.stopImmediatePropagation();
            return;
        }

        if (event.target.closest('.country-link')) {
            const country = event.target.closest('.country-link').getAttribute('data-country');
            showCountryDetail(country);
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    });

    function showPhotoDetail(imageID) {
        const photoDetailContainer = document.getElementById('photo-detail-container');
        const photosView = document.getElementById('photos-view');
        photosView.style.display = 'none';
        photoDetailContainer.style.display = 'block';
    
        // Fetch the photo details from the API using the imageID
        fetch(`/api/photo?imageID=${imageID}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch photo details');
                }
                return response.json();
            })
            .then(photo => {
                loadPhotoDetail(photo); // Pass the full photo object to loadPhotoDetail
            })
            .catch(error => {
                console.error('Error loading photo details:', error);
            });
    }
    

    function showCountryDetail(country) {
        const photoDetailContainer = document.getElementById('photo-detail-container');
        const countryDetailContainer = document.getElementById('country-detail-container');
        photoDetailContainer.style.display = 'none';
        countryDetailContainer.style.display = 'block';
        handleCountryDetail(country);
    }

    sortCountryAscButton.addEventListener('click', () => {
        photos.sort((a, b) => a.country.localeCompare(b.country));
        currentSort = { field: 'country', order: 'asc' };
        displayPhotos(photos);
    });

    sortCountryDescButton.addEventListener('click', () => {
        photos.sort((a, b) => b.country.localeCompare(a.country));
        currentSort = { field: 'country', order: 'desc' };
        displayPhotos(photos);
    });

    sortCityAscButton.addEventListener('click', () => {
        photos.sort((a, b) => a.city.localeCompare(b.city));
        currentSort = { field: 'city', order: 'asc' };
        displayPhotos(photos);
    });

    sortCityDescButton.addEventListener('click', () => {
        photos.sort((a, b) => b.city.localeCompare(a.city));
        currentSort = { field: 'city', order: 'desc' };
        displayPhotos(photos);
    });

    toggleViewButton.addEventListener('click', function () {
        if (photosContainer.classList.contains('grid-view')) {
            photosContainer.classList.remove('grid-view');
            photosContainer.classList.add('list-view');
            toggleViewButton.textContent = 'Switch to Grid View';
        } else {
            photosContainer.classList.remove('list-view');
            photosContainer.classList.add('grid-view');
            toggleViewButton.textContent = 'Switch to List View';
        }
    });

    styleButtons();
}
