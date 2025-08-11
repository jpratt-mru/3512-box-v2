export function loadPhotoDetail(photo) {
    const photoDetailView = document.getElementById('photo-detail-view');
    const dynamicMessage = document.getElementById('dynamic-message');

    // Update the dynamic message
    dynamicMessage.textContent = "You are now viewing the photo details. Enjoy!";
    dynamicMessage.style.fontWeight = 'bold';
    dynamicMessage.style.fontSize = '1.5rem';
    dynamicMessage.style.display = 'block';
    dynamicMessage.style.margin = '20px 0'; // Add margin above and below

    // Display the photo
    const photoDetailImage = document.getElementById('photo-detail-image');
    photoDetailImage.src = `https://res.cloudinary.com/dng6vk65h/image/upload/w_600,h_600,c_fill/${photo.Path}`;
    photoDetailImage.style.width = `${window.innerWidth * 0.7}px`; // Adjust size
    photoDetailImage.style.height = 'auto';
    photoDetailImage.style.display = 'block';
    photoDetailImage.style.margin = '20px auto'; // Center the image with spacing

    // Update photo description
    const descriptionSection = document.getElementById('photo-description');
    descriptionSection.textContent = ''; // Clear previous content
    const descriptionHeading = document.createElement('strong');
    descriptionHeading.textContent = "Photo Description";
    descriptionSection.appendChild(descriptionHeading);
    descriptionSection.appendChild(document.createElement('br'));
    descriptionSection.appendChild(document.createTextNode(photo.Description || "No description available."));
    descriptionSection.style.marginBottom = '20px'; // Add spacing below

    // Update Exif details
    const exifDetails = document.getElementById('photo-exif');
    exifDetails.textContent = ''; // Clear previous content
    const exifHeading = document.createElement('strong');
    exifHeading.textContent = "Exif Data";
    exifDetails.appendChild(exifHeading);
    exifDetails.appendChild(document.createElement('br'));
    const exifData = photo.Exif ? JSON.parse(photo.Exif) : {};
    exifDetails.appendChild(document.createTextNode(
        `Make: ${exifData.make || 'Unknown'}, Model: ${exifData.model || 'Unknown'}, Exposure Time: ${exifData.exposure_time || 'N/A'}, Aperture: f/${exifData.aperture || 'N/A'}, Focal Length: ${exifData.focal_length || 'N/A'}mm, ISO: ${exifData.iso || 'N/A'}`
    ));
    exifDetails.style.marginBottom = '20px'; // Add spacing below

    // Update creator information
    const creator = document.getElementById('photo-creator');
    creator.textContent = ''; // Clear previous content
    const creatorHeading = document.createElement('strong');
    creatorHeading.textContent = "Creator";
    creator.appendChild(creatorHeading);
    creator.appendChild(document.createElement('br'));
    creator.appendChild(document.createTextNode(photo.ActualCreator || "Unknown"));
    creator.style.marginBottom = '20px'; // Add spacing below

    // Update average rating
    const ratingElement = document.getElementById('photo-rating');
    ratingElement.textContent = ''; // Clear previous content
    const ratingHeading = document.createElement('strong');
    ratingHeading.textContent = "Average Rating";
    ratingElement.appendChild(ratingHeading);
    ratingElement.appendChild(document.createElement('br'));
    ratingElement.appendChild(document.createTextNode(photo.AverageRating ? `Average Rating: ${parseFloat(photo.AverageRating).toFixed(1)}` : "No ratings available"));
    ratingElement.style.marginBottom = '20px'; // Add spacing below

    // Update city
    const cityElement = document.getElementById('photo-city');
    cityElement.textContent = ''; // Clear previous content
    const cityHeading = document.createElement('strong');
    cityHeading.textContent = "City";
    cityElement.appendChild(cityHeading);
    cityElement.appendChild(document.createElement('br'));
    cityElement.appendChild(document.createTextNode(photo.city || "Unknown"));
    cityElement.style.marginBottom = '20px'; // Add spacing below

    // Update location (latitude and longitude)
    const locationElement = document.getElementById('photo-lat-lng');
    locationElement.textContent = ''; // Clear previous content
    const locationHeading = document.createElement('strong');
    locationHeading.textContent = "Location";
    locationElement.appendChild(locationHeading);
    locationElement.appendChild(document.createElement('br'));
    locationElement.appendChild(document.createTextNode(`Latitude: ${photo.Latitude}, Longitude: ${photo.Longitude}`));
    locationElement.style.marginBottom = '20px'; // Add spacing below

    // Update Google Maps image
    const mapImage = document.getElementById('google-maps-image');
    mapImage.src = `https://maps.googleapis.com/maps/api/staticmap?center=${photo.Latitude},${photo.Longitude}&zoom=15&size=400x200&markers=${photo.Latitude},${photo.Longitude}&key=AIzaSyCDbr7Hz2hyYCvLVN0SAaw5nd4jBPFZWgI`;
    mapImage.style.marginBottom = '20px'; // Add spacing below

    // Display flagged status
    const flaggedStatus = document.getElementById('photo-flagged');
    if (photo.Flagged) {
        flaggedStatus.textContent = "This photo has been flagged.";
        flaggedStatus.style.color = 'red';
        flaggedStatus.style.fontWeight = 'bold';
        flaggedStatus.style.marginBottom = '20px'; // Add spacing below
        photoDetailImage.style.border = '10px solid red';
    } else {
        flaggedStatus.textContent = '';
        photoDetailImage.style.border = '';
    }

    // Show the photo detail view
    photoDetailView.style.display = 'block';

    // Close button logic for the photo detail view
    document.getElementById('close-photo-detail').addEventListener('click', () => {
        const photoDetailContainer = document.getElementById('photo-detail-container');
        const photosView = document.getElementById('photos-view');
        const dynamicMessage = document.getElementById('dynamic-message');

       // Debugging

        // Hide the photo detail container
        photoDetailContainer.style.display = 'none';

        // Show the photos view again
        photosView.style.display = 'block';

        // Hide the dynamic message
        dynamicMessage.style.display = 'none';

        // Clear the hash in the URL
        window.location.hash = '';
    });

}
