<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already active
}

require_once '../database/DatabaseQueries.php';
$db = new DatabaseQueries();

// Clear session and cookies
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    // Remove "Remember Me" cookie and token from the database if set
    if (isset($_COOKIE['remember_me'])) {
        // Remove token from the database
        $db->run("DELETE FROM user_tokens WHERE token = :token", ['token' => $_COOKIE['remember_me']]);
        // Expire the cookie
        setcookie('remember_me', '', time() - 3600, '/');
    }

    // Destroy the session
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
}

// Regenerate the session ID and start a new session (ensures PHPSESSID changes)
session_start(); // Start a new session
session_regenerate_id(true); // Generate a new session ID to ensure the PHPSESSID changes

// Redirect to login page after logging out
header('Location: /admin');
exit();
