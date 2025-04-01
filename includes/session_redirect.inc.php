<?php
session_start(); // Start the session

// Regenerate session ID to prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard if the user is logged in
    header('Location: ../dashboard.php');
    exit();
}
?>