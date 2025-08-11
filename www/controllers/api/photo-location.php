<?php
require_once '../database/DatabaseQueries.php';

// Set the content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Define a helper function for error responses
function sendErrorResponse($message, $statusCode) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit;
}

// Check if the 'location' parameter is provided
if (!isset($_GET['location']) || empty($_GET['location'])) {
    sendErrorResponse('Location parameter is missing or empty', 400);
}

// Sanitize the input
$location = htmlspecialchars($_GET['location'], ENT_QUOTES, 'UTF-8');

$db = new DatabaseQueries();

try {
    // Fetch photos by location
    $photos = $db->getPhotosByLocation($location);

    // Check if photos were found
    if (!$photos || count($photos) === 0) {
        sendErrorResponse('No photos found for the specified location', 404);
    }

    // Return the photos as a JSON response
    $response = json_encode($photos);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse('Failed to encode JSON: ' . json_last_error_msg(), 500);
    }

    echo $response;
} catch (Exception $e) {
    // Handle unexpected exceptions
    sendErrorResponse('An unexpected error occurred: ' . $e->getMessage(), 500);
}
