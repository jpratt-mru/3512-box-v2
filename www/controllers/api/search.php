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

// Check if 'query' parameter is provided
if (!isset($_GET['query']) || empty($_GET['query'])) {
    sendErrorResponse('Query parameter is missing or empty', 400);
}

// Sanitize the input
$query = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');

$db = new DatabaseQueries();

try {
    // Perform the search
    $results = $db->searchLocations($query);

    // Check if results were found
    if (!$results || count($results) === 0) {
        sendErrorResponse('No results found for the specified query', 404);
    }

    // Encode the response as JSON
    $response = json_encode($results);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse('Failed to encode JSON: ' . json_last_error_msg(), 500);
    }

    echo $response;
} catch (Exception $e) {
    // Handle unexpected exceptions
    sendErrorResponse('An unexpected error occurred: ' . $e->getMessage(), 500);
}
