<?php include 'includes/session_redirect.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/welcome_styles.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <div class="form-container">
        <h1>Create Your Account</h1>
        <form action="php/register.php" method="POST" id="accountForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" autocomplete="on" required>
                <div id="emailError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" name="pwd" id="password" class="form-control" required>
                    <i class="toggle-password fa fa-eye" onclick="togglePassword()"></i>
                </div>
                <div id="passwordError" class="error-message"></div>
                <!-- Password Checklist -->
                <div id="passwordChecklist" class="password-checklist">
                    <p>Password must contain:</p>
                    <ul>
                        <li id="length" class="invalid"><i class="fa fa-times-circle"></i> At least 8 characters</li>
                        <li id="uppercase" class="invalid"><i class="fa fa-times-circle"></i> At least one uppercase letter</li>
                        <li id="lowercase" class="invalid"><i class="fa fa-times-circle"></i> At least one lowercase letter</li>
                        <li id="number" class="invalid"><i class="fa fa-times-circle"></i> At least one number</li>
                        <li id="special" class="invalid"><i class="fa fa-times-circle"></i> At least one special character (@$!%*?&)</li>
                    </ul>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="createAccountBtn">Create Account</button>
        </form>
        <br /><p>Already registered? <a href="login.php">Login</a></p>
    </div>

    <!-- Dark overlay behind the popup and loader -->
    <div id="popup-overlay" class="popup-overlay" onclick="closePopup()">
        <!-- Loader from Uiverse.io by AbanoubMagdy1 --> 
        <div class="loader" id="loader">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <!-- Notification message container -->
    <div id="notification" class="notification error-notification" role="alert">
        <span id="message"></span>
        <button class="close-btn" onclick="hideNotification()">&times;</button>
    </div>
    <script src="js/script.js"></script>
</body>
</html>