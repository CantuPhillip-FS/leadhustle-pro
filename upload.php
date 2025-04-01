<?php include 'includes/auth.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Custom Stylesheets -->
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
        <div id="notification" class="notification" role="alert">
            <span id="message"></span>
            <button class="close-btn" onclick="hideNotification()" aria-label="Close Notification">&times;</button>
        </div>

        <div class="row justify-content-center">
            <div class="col col-lg-8">
                <div class="progress-path">
                    <div class="progress-step active">
                        <span class="circle"></span>
                        <span class="label">Upload</span>
                    </div>
                    <div class="progress-step">
                        <a href="confirm.php">
                            <span class="circle"></span>
                            <span class="label">Confirm</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <h2 style="margin:0;">Upload CSV</h2>
        <!-- Informative Alert Message -->
        <div class="row justify-content-center">
            <div class="col col-lg-8">
                <div class="alert alert-info alert-dismissible fade show mt-4" role="alert">
                    <strong>Important!</strong> Your CSV file must include both <strong>"Name"</strong> and <strong>"Phone"</strong> columns. These headers are <em>case-sensitive</em>. Please use the provided template as a guideline.
                </div>
            </div>
        </div>
        <!-- End of Informative Alert Message -->
        
        <div class="row justify-content-center">
            <div class="col col-lg-8">
                <div class="dropzone mb-4" id="dropzone">
                    <p class="mb-2 font-weight-bold">Click to upload or drag and drop</p>
                    <p class="text-muted">Accepts .csv files only</p>
                    <input id="fileInput" class="d-none" type="file" accept=".csv" multiple>
                </div>
                <div class="sample-download text-center">
                    <a href="/downloads/Sample_Leads.csv" class="btn btn-link text-primary" download>
                        <i class="fas fa-download mr-2"></i>Download CSV Template
                    </a>
                    <p class="text-muted small">Use this template to format your leads file for upload.</p>
                </div>
            </div>
        </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="js/upload_script.js"></script>
</body>
</html>