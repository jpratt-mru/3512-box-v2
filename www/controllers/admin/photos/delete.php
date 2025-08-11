<?php
// delete.php
require_once '../../database/DatabaseQueries.php';
$db = new DatabaseQueries();

// Sanitize and validate photo_id from query string
if (isset($_GET['photo_id']) && is_numeric($_GET['photo_id'])) {
    $photoId = (int)$_GET['photo_id']; // Cast to an integer to avoid SQL injection

    // Delete the photo
    $db->deletePhoto($photoId);
    $_SESSION['message'] = 'Photo deleted successfully.';
} else {
    // Invalid photo_id
    $_SESSION['error_message'] = 'Invalid photo ID.';
}

header('Location: /admin/dashboard/photos');
exit();
