<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and password from POST request
    $email = trim($_POST["email"]);
    $password = trim($_POST["pwd"]);

    // Include the database connection file
    require_once "../includes/dbh.inc.php";

    // Include the email sending script
    require_once "send_registration_emails.php";

    try {
        // Input validation
        if (empty($email) || empty($password)) {
            header('Location: ../welcome.php?error=Both%20fields%20are%20required');
            exit();
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: ../welcome.php?error=Invalid%20email%20format');
            exit();
        }

        // Check if the email already exists
        $checkStmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        if (!$checkStmt) {
            throw new Exception("Statement preparation failed: " . $mysqli->error);
        }
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $checkStmt->close();
            header('Location: ../login.php?error=This%20email%20is%20already%20registered.%20Please%20login.');
            exit();
        }
        $checkStmt->close();

        // Password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the insert statement
        $stmt = $mysqli->prepare("INSERT INTO users (email, pwd) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . $mysqli->error);
        }
        $stmt->bind_param("ss", $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Start a session and set session variables
            session_start();
            $_SESSION['user_id'] = $stmt->insert_id; // Get the inserted user ID
            $_SESSION['email'] = $email;

            // Optional: Derive user's first name from email (before "@")
            $userName = strstr($email, '@', true);
            if (!$userName) {
                $userName = "User";
            }

            // Send confirmation emails
            sendRegistrationEmails($email, $password, ucfirst($userName)); // Capitalize first letter

            header('Location: ../upload.php?success=Account created successfully');
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Handle any errors gracefully
        // Log the error message to a file for debugging (optional)
        error_log("Registration Error: " . $e->getMessage());

        // Redirect with a generic error message to avoid exposing sensitive details
        header('Location: ../welcome.php?error=An%20unexpected%20error%20occurred.%20Please%20try%20again.');
        exit();
    } finally {
        // Close the statement and the connection
        if (isset($stmt)) {
            $stmt->close();
        }
        $mysqli->close();
    }
} else {
    // Redirect to welcome.php if the request method is not POST
    header("Location: ../welcome.php");
    exit();
}
?>