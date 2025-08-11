<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already active
}

require_once '../database/DatabaseQueries.php';
$db = new DatabaseQueries();

$error = ''; // Default error message is empty

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['remember_me']); // Check if "Remember Me" is checked

    // Check if username and password are both provided
    if (empty($username) && empty($password)) {
        $error = 'Both username and password are required.';
    } elseif (empty($username)) {
        $error = 'Invalid Login Credentials.';
    } elseif (empty($password)) {
        $error = 'Invalid Login Credentials.';
    } else {
        // Validate the login credentials
        $admin = $db->fetchSingle(
            "SELECT * FROM administrator WHERE Username = :username",
            ['username' => $username]
        );

        if ($admin && password_verify($password, $admin['Password'])) {
            // Successful login
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['admin_id'] = $admin['AdminID'];

            // Regenerate the session ID for security after successful login
            session_regenerate_id(true);

            // If "Remember Me" is checked, generate a token and set cookie
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32)); // Generate a random token
                $expiry = date('Y-m-d H:i:s', strtotime('+30 days')); // Token expiry (30 days)

                // Save token in the database
                $db->run(
                    "INSERT INTO user_tokens (token, expiry, admin_id) VALUES (:token, :expiry, :admin_id)",
                    ['token' => $token, 'expiry' => $expiry, 'admin_id' => $admin['AdminID']]
                );

                // Set the token in the "Remember Me" cookie
                setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true); // 30 days expiry
            }

            // Redirect to stats page after successful login
            header('Location: /admin/dashboard/stats');
            exit();
        } else {
            // Invalid username or password
            $error = 'Invalid Login Credentials.';
        }
    }
}

include '../views/admin/login.php';
