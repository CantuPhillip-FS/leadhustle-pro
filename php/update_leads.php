<?php
include '../includes/auth.inc.php'; // Ensure the user is authenticated
require_once '../includes/dbh.inc.php'; // Include the database connection

// Get the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

// Extract the lead ID, notes, and follow-up status from the input
$leadId = $input['leadId'] ?? null;
$notes = $input['notes'] ?? null;
$followUp = $input['followUp'] ?? null; // New Follow-Up status (Yes/No as 1/0)

// Validate the input
if (!$leadId || $notes === null || $followUp === null) {
    http_response_code(400); // Bad Request
    echo "Invalid input.";
    exit();
}

try {
    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Construct the table name for the current user
    $tableName = "leads_user_" . $userId;

    // Convert Follow-Up value to an integer for the database
    $followUpValue = ($followUp === "1") ? 1 : 0; // Ensure it is either 1 (Yes) or 0 (No)

    // Initialize Follow-Up Added On timestamp
    $followUpAddedOn = null;

    // Set the timestamp only if Follow_Up is "Yes"
    if ($followUpValue === 1) {
        $followUpAddedOn = date('Y-m-d H:i:s'); // Current timestamp
    }

    // Prepare the SQL query to update Notes, Follow_Up, and Follow_Up_Added_On
    $stmt = $mysqli->prepare("UPDATE `$tableName` SET Notes = ?, Follow_Up = ?, Follow_Up_Added_On = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $mysqli->error);
    }

    // Bind parameters (Notes: string, Follow_Up: integer, Follow_Up_Added_On: string/null, id: integer)
    $stmt->bind_param('sisi', $notes, $followUpValue, $followUpAddedOn, $leadId);

    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    echo "Notes and Follow-Up status saved successfully.";
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo "Error: " . $e->getMessage();
    } finally {
    if (isset($stmt)) {
        $stmt->close(); // Close the statement
    }
    $mysqli->close(); // Close the database connection
}
?>