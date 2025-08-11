<?php
require_once '../database/DatabaseQueries.php';

// Set the content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Helper function for error responses
function sendErrorResponse($message, $statusCode) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit;
}

// Check if 'imageID' parameter is provided
if (!isset($_GET['imageID']) || empty($_GET['imageID'])) {
    sendErrorResponse('ImageID parameter is missing or empty', 400);
}

// Sanitize the input
$imageID = htmlspecialchars($_GET['imageID'], ENT_QUOTES, 'UTF-8');

$db = new DatabaseQueries();

try {
    // Fetch photo details
    $photo = $db->getPhotoDetails($imageID);

    // Check if the photo exists
    if (!$photo) {
        sendErrorResponse('No photo found for the specified ImageID', 404);
    }

    // Encode the response as JSON
    $response = json_encode($photo);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse('Failed to encode JSON: ' . json_last_error_msg(), 500);
    }

    echo $response;
} catch (Exception $e) {
    // Handle unexpected exceptions
    sendErrorResponse('An unexpected error occurred: ' . $e->getMessage(), 500);
}
