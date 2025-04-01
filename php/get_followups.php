<?php
include '../includes/auth.inc.php'; // Ensure the user is authenticated
require_once '../includes/dbh.inc.php'; // Include the database connection

// Get user ID from session
$userId = $_SESSION['user_id'];

// Table name for the user's leads
$tableName = "leads_user_" . $userId;

// Fetch leads with Follow_Up set to 'Yes'
$query = "SELECT Name, Follow_Up FROM `$tableName` WHERE Follow_Up = 1";
$result = $mysqli->query($query);

// Prepare an array to store the leads
$followUpLeads = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $followUpLeads[] = $row;
    }
}

$mysqli->close();

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($followUpLeads);
?>