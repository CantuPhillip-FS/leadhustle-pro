<?php
// Start the session only if it hasn't started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to welcome.php with an error message
    header('Location: ../welcome.php?error=Please%20login%20or%20register%20first');
    exit();
}

// Regenerate session ID (optional but recommended during sensitive user actions like login)
session_regenerate_id(true);
?>