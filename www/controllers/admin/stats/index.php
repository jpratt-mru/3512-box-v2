<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../database/DatabaseQueries.php';

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: /admin');
    exit();
}

$db = new DatabaseQueries();
$totalPhotos = $db->getTotalPhotos();
$mostPopularCity = $db->getMostPopularCity();
$flaggedUsers = $db->getFlaggedUsers();

include '../views/admin/stats-dashboard.php';
