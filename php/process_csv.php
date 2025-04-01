<?php
header('Content-Type: application/json');

include '../includes/auth.inc.php';
require_once '../includes/dbh.inc.php';

try {
    // Get user ID
    $userId = $_SESSION['user_id'];

    // Decode JSON data from the POST request
    $postData = file_get_contents("php://input");
    if (!mb_detect_encoding($postData, 'UTF-8', true)) {
        $postData = mb_convert_encoding($postData, 'UTF-8', 'auto');
    }
    $decodedData = json_decode($postData, true);
    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data: " . json_last_error_msg());
    }

    $headers = isset($decodedData['headers']) ? $decodedData['headers'] : [];
    $csvData = isset($decodedData['csvData']) ? $decodedData['csvData'] : [];

    // Check if headers and data are valid arrays
    if (!is_array($headers) || !is_array($csvData) || empty($csvData)) {
        throw new Exception("Headers or data are invalid or missing.");
    }

    // Sanitize headers strictly for MySQL column names
    $sanitizedHeaders = [];
    foreach ($headers as $header) {
        $sanitizedHeader = preg_replace('/[^a-zA-Z0-9_]/', '_', $header); // Replace invalid characters with underscores
        if (is_numeric(substr($sanitizedHeader, 0, 1))) {
            $sanitizedHeader = 'col_' . $sanitizedHeader; // Prevent starting with a number
        }
        if (empty($sanitizedHeader)) {
            throw new Exception("Invalid header name: $header");
        }
        if (in_array($sanitizedHeader, $sanitizedHeaders)) {
            throw new Exception("Duplicate sanitized header detected: $sanitizedHeader");
        }
        $sanitizedHeaders[] = $sanitizedHeader;
    }

    // Check if 'Name' exists
    if (!in_array('Name', $sanitizedHeaders)) {
        throw new Exception("The 'Name' column is required but not found. Please check your CSV file.");
    }

    // Check if 'Phone' exists
    if (!in_array('Phone', $sanitizedHeaders)) {
        throw new Exception("The 'Phone' column is required but not found. Please check your CSV file.");
    }

    // Check if all rows have the same number of elements as headers
    foreach ($csvData as $rowIndex => $row) {
        if (count($row) !== count($sanitizedHeaders)) {
            if (count($row) < count($sanitizedHeaders)) {
                // Pad missing fields with empty strings
                $csvData[$rowIndex] = array_pad($row, count($sanitizedHeaders), '');
            } else {
                // Trim extra fields
                $csvData[$rowIndex] = array_slice($row, 0, count($sanitizedHeaders));
            }
        }
    }

    // Construct the table name
    $tableName = "leads_user_" . $userId;

    // Ensure the table exists or create it if it doesn't
    $createQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        `Name` VARCHAR(255) UNIQUE NOT NULL,
        Notes TEXT,
        Follow_Up TINYINT(1) DEFAULT 0,
        Follow_Up_Added_On DATETIME NULL";
    foreach ($sanitizedHeaders as $sanitizedHeader) {
        if (!in_array($sanitizedHeader, ['id', 'Name', 'Notes', 'Follow_Up', 'Follow_Up_Added_On'])) {
            $createQuery .= ", `$sanitizedHeader` VARCHAR(255)";
        }
    }
    $createQuery .= ")";
    if (!$mysqli->query($createQuery)) {
        throw new Exception("Error creating table: " . $mysqli->error);
    }

    // Fetch existing columns from the database
    $existingColumns = [];
    $columnsResult = $mysqli->query("SHOW COLUMNS FROM `$tableName`");
    if ($columnsResult) {
        while ($column = $columnsResult->fetch_assoc()) {
            $existingColumns[] = $column['Field'];
        }
    }

    // Add missing columns
    $newColumns = array_diff($sanitizedHeaders, $existingColumns);
    foreach ($newColumns as $sanitizedHeader) {
        $alterTableQuery = "ALTER TABLE `$tableName` ADD `$sanitizedHeader` VARCHAR(255)";
        if (!$mysqli->query($alterTableQuery)) {
            error_log("Error adding column `$sanitizedHeader`: " . $mysqli->error);
            continue;
        }
    }

    // Prepare the insert statement dynamically
    $placeholders = rtrim(str_repeat('?, ', count($sanitizedHeaders)), ', ');
    $insertQuery = "INSERT IGNORE INTO `$tableName` (" . implode(', ', array_map(fn($header) => "`$header`", $sanitizedHeaders)) . ") VALUES ($placeholders)";
    $stmt = $mysqli->prepare($insertQuery);
    if (!$stmt) {
        throw new Exception("Error preparing insert statement: " . $mysqli->error);
    }

    // Insert data into the table
    $existingNamesQuery = "SELECT Name FROM `$tableName`";
    $existingNamesResult = $mysqli->query($existingNamesQuery);
    $existingNames = [];
    if ($existingNamesResult) {
        while ($row = $existingNamesResult->fetch_assoc()) {
            $existingNames[] = $row['Name'];
        }
    }

    foreach ($csvData as $row) {
        $nameIndex = array_search('Name', $sanitizedHeaders);
        if ($nameIndex !== false && in_array($row[$nameIndex], $existingNames)) {
            continue; // Skip duplicates
        }
        $stmt->bind_param(str_repeat('s', count($row)), ...$row);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting data: " . $stmt->error);
        }
    }

    echo "Your Leads have been uploaded successfully.";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $mysqli->close();
}
?>