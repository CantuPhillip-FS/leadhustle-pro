<?php include 'includes/session_redirect.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/login_styles.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <div class="form-container">
        <h1>Log into your Account</h1>
        <form action="includes/login.inc.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" autocomplete="on" required>
                <div id="emailError"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <i class="toggle-password fa fa-eye" onclick="togglePassword()"></i>
                <div id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
        <br /><p>Need an account? <a href="welcome.php">Register</a></p>
    </div>
    <!-- notification message container -->
    <div id="notification" class="notification error-notification" role="alert">
        <span id="message"></span>
        <button class="close-btn" onclick="hideNotification()">&times;</button>
    </div>
    <script src="js/script.js"></script>
</body>
</html>