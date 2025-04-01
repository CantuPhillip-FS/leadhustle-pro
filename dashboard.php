<?php include 'includes/auth.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="css/dashboard_styles.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <!-- Navigation Bar (Visible Only on Mobile Devices) -->
    <nav class="navbar">
        <a href="index.php" class="logo">LeadHustle.pro</a>
        <div class="nav-links" id="navLinks">
            <a href="dashboard.php" class="active">
                <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
                Dashboard
            </a>
            <a href="leads.php">
                <span class="menu-icon"><i class="fa-solid fa-user-group"></i></span>
                View Leads
            </a>
            <a href="upload.php">
                <span class="menu-icon"><i class="fa-solid fa-user-plus"></i></span>
                Add Leads
            </a>
            <a href="php/logout.php">
                <span class="menu-icon"><i class="fa fa-sign-out-alt"></i></span>
                Logout
            </a>
        </div>
        <!-- Hamburger Icon -->
        <div class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false" role="button" tabindex="0">
            <i class="fa fa-bars"></i>
        </div>
    </nav>

    <h1 class="dashboard-title">Dashboard</h1>
    <div class="dashboard-container">
        <!-- Notification Message Container -->
        <div id="notification" class="notification" role="alert">
            <span id="message"></span>
            <button class="close-btn" onclick="hideNotification()" aria-label="Close Notification">&times;</button>
        </div>
        <div class="stats-container">
            <div class="card">
                <h3><!-- Header will be dynamically loaded here by JavaScript --></h3>
                <div id="followUpsContainer">
                    <!-- Content will be dynamically loaded here by JavaScript -->
                </div>
            </div>
            <div class="card">
                <h3>Actions</h3>
                <div class="contact">
                    <span class="name">
                        <span class="menu-icon"><i class="fa-solid fa-user-group"></i></span>
                        <a href="leads.php">View Leads</a>
                    </span>
                </div>
                <div class="contact">
                    <span class="name">
                        <span class="menu-icon"><i class="fa-solid fa-user-plus"></i></span>
                        <a href="upload.php">Add Leads</a>
                    </span>
                </div>
                <div class="contact">
                    <span class="name">
                        <span class="menu-icon"><i class="fa fa-sign-out-alt"></i></span>
                        <a href="php/logout.php">Logout</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom JavaScript -->
    <script src="js/dashboard_script.js"></script>
</body>
</html>