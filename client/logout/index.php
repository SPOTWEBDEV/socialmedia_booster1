<?php
include("../../server/connection.php");
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define login URL
$url = $domain . 'login/';

// Logout logic

    // Clear all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Clear the session cookie (optional but recommended)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Redirect to login page
    header("Location: $url");
    exit();

?>