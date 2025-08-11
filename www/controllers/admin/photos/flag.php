<?php
// flag.php
require_once '../../database/DatabaseQueries.php';
$db = new DatabaseQueries();

// Sanitize and validate photo_id from query string
if (isset($_GET['photo_id']) && is_numeric($_GET['photo_id'])) {
    $photoId = (int)$_GET['photo_id']; // Cast to an integer to avoid SQL injection

    // Flag the photo
    $db->flagPhoto($photoId);
    $_SESSION['message'] = 'Photo flagged successfully.';
} else {
    // Invalid photo_id
    $_SESSION['error_message'] = 'Invalid photo ID.';
}

header('Location: /admin/dashboard/photos');
exit();
