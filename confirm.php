<?php include 'includes/auth.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CDN -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/upload_styles.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="index.php" class="logo">LeadHustle.pro</a>
        <div class="nav-links" id="navLinks">
            <a href="dashboard.php">
                <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
                Dashboard
            </a>
            <a href="leads.php">
                <span class="menu-icon"><i class="fa-solid fa-user-group"></i></span>
                View Leads
            </a>
            <a href="upload.php" class="active">
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
    <div class="container">
        <!-- Notification Message Container -->
        <div id="notification" class="notification error-notification" role="alert">
            <span id="message"></span>
            <button class="close-btn" onclick="hideNotification()">&times;</button>
        </div>
        <div class="row justify-content-center">
            <div class="col col-lg-8">
                <div class="progress-path">
                    <div class="progress-step"><a href="upload.php">
                        <span class="circle"></span>
                        <span class="label">Upload</span></a>
                    </div>
                    <div class="progress-step active">
                        <span class="circle"></span>
                        <span class="label">Confirm</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="data-table-container">
        <h2>Confirm Data</h2>
            <!-- Informative Alert Message -->
            <div class="row justify-content-center">
                <div class="col col-lg-8">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Note:</strong> Please click the <strong>Confirm</strong> button below after confirming your data. Only the first row of your data is displayed below for confirmation. If the data looks incorrect, click the Back button below to re-upload your CSV file.
                    </div>
                </div>
            </div>

            <div class="data-columns">
                <div class="headers-column" id="headersColumn"></div>
                <div class="data-column" id="dataColumn"></div>
            </div>
        </div>
        <div class="button-container">
            <button class="btn btn-secondary back-to-upload">Back</button>
            <button class="btn btn-primary confirm-data">Confirm</button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="js/upload_script.js"></script>
    </div>
</body>
</html>