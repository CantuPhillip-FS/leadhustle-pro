<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and password from POST request
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Include the database connection file
    require_once "dbh.inc.php";

    try {
        // Input validation
        if (empty($email) || empty($password)) {
            header('Location: ../login.php?error=Both%20fields%20are%20required');
            exit();
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: ../login.php?error=Invalid%20email%20format');
            exit();
        }

        // Check if the email exists
        $checkStmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        if (!$checkStmt) {
            throw new Exception("Statement preparation failed: " . $mysqli->error);
        }
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            $checkStmt->close();
            header('Location: ../login.php?error=Email%20not%20found');
            exit();
        }

        // Fetch user data
        $user = $result->fetch_assoc();
        $checkStmt->close();

        // Verify the password
        if (!password_verify($password, $user['pwd'])) {
            header('Location: ../login.php?error=Incorrect%20password');
            exit();
        }

        // Password is correct; start a session and set session variables
        session_start();
        session_regenerate_id(true); // Regenerate session ID to secure the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header('Location: ../dashboard.php');
        exit();
    } catch (Exception $e) {
        // Handle any errors gracefully
        header('Location: ../login.php?error=' . urlencode($e->getMessage()));
        exit();
    } finally {
        // Close the database connection
        $mysqli->close();
    }
} else {
    // Redirect to login.html if the request method is not POST
    header("Location: ../login.php");
    exit();
}
?>