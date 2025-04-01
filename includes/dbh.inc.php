<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$db_host = 'localhost';
$db_user = 'claiyale_cold_caller_crm';
$db_password = 'IamRich87$$';
$db_db = 'claiyale_cold_caller_crm';

// Create a new MySQLi object for the database connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_db);

// Check connection and provide a descriptive error message if it fails
if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_errno . ' - ' . $mysqli->connect_error);
}
?>