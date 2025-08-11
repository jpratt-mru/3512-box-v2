<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/DatabaseQueries.php';
$db = new DatabaseQueries();

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: /admin');
    exit();
}

$sort = $_GET['sort'] ?? 'ImageID';  // Default sort by ImageID
$order = $_GET['order'] ?? 'ASC';    // Default order is ascending

// Handle sorting based on user selection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['flag'])) {
        $db->flagPhoto($_POST['photo_id']);
        $_SESSION['message'] = 'Photo flagged successfully.';
    } elseif (isset($_POST['unflag'])) {
        $db->unflagPhoto($_POST['photo_id']);
        $_SESSION['message'] = 'Photo unflagged successfully.';
    } elseif (isset($_POST['delete'])) {
        $db->deletePhoto($_POST['photo_id']);
        $_SESSION['message'] = 'Photo deleted successfully.';
    } elseif (isset($_POST['restore'])) {
        $db->restorePhoto($_POST['photo_id']);
        $_SESSION['message'] = 'Photo restored successfully.';
    } elseif (isset($_POST['restore_all'])) {
        $db->restoreAllPhotos();
        $_SESSION['message'] = 'All deleted photos restored successfully.';
    }
    header('Location: /admin/dashboard/photos');
    exit();
}

// Sorting photos based on selected sort column and order
$photos = $db->getAllPhotos($sort, false, $order);

include '../views/admin/photo.php';
