<?php 
include 'includes/auth.inc.php'; 
require_once 'includes/dbh.inc.php';

// Get user ID from the session
$userId = $_SESSION['user_id'];

// Prepare the table name
$tableName = "leads_user_" . $userId;

// Check if the table exists
$tableExistsQuery = "SHOW TABLES LIKE '$tableName'";
$tableExistsResult = $mysqli->query($tableExistsQuery);

$headers = [];
$leads = [];
$noLeadsMessage = "";

if ($tableExistsResult && $tableExistsResult->num_rows > 0) {
    // Table exists, fetch leads
    $query = "SELECT * FROM `$tableName`";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        // Fetch headers dynamically
        $headers = array_keys($result->fetch_assoc());
        $result->data_seek(0);

        // Remove the 'id' header
        $headers = array_values(array_filter($headers, fn($header) => $header !== 'id'));

        // Fetch all leads data
        while ($row = $result->fetch_assoc()) {
            if (isset($row['Follow_Up'])) {
                $row['Follow_Up'] = $row['Follow_Up'] == 1 ? 'Yes' : 'No';
            }
            if (isset($row['Follow_Up_Added_On']) && $row['Follow_Up_Added_On'] !== null) {
                $row['Follow_Up_Added_On'] = date('F j, Y, g:i a', strtotime($row['Follow_Up_Added_On']));
            }
            $leads[] = $row;
        }
    } else {
        $noLeadsMessage = "No Leads Uploaded.";
    }
} else {
    $noLeadsMessage = "No Leads Uploaded.";
}

// Close the database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <!-- Font Awesome CDN -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/leads_styles.css">
    <link rel="stylesheet" href="css/leads_notifications.css">
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
            <a href="leads.php" class="active">
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
    <div class="container">
        <!-- Notification Message Container -->
        <div id="notification" class="notification" role="alert">
            <span id="message"></span>
        <button class="close-btn" onclick="hideNotification()">&times;</button>
        </div>
        <h1>Your Leads</h1>
        <?php if (!empty($noLeadsMessage)): ?>
            <div class="no-leads-message">
                <p><?php echo $noLeadsMessage; ?></p>
            </div>
        <?php else: ?>
            <div class="searchbar-container">
                <div class="top-bar">
                    <!-- Filter Leads Button -->
                    <button title="Filter" class="filter">
                        <i class="fas fa-filter filter-svgIcon"></i>
                        <span class="filter-tooltip">Filter Leads</span>
                    </button>
                    <!-- Search Bar -->
                    <input type="text" id="searchBar" placeholder="Search leads..." class="search-bar">
                    <!-- Download CSV Button -->
                    <button class="Btn">
                        <i class="fas fa-download svgIcon"></i>
                        <span class="export-tooltip">Download Leads</span>
                    </button>  
                </div>
            </div>
            <div class="leads-container" id="leadsContainer"></div>
            <!-- Modal Overlay -->
            <div class="modal-overlay" id="modalOverlay" role="dialog" aria-modal="true" aria-labelledby="leadDetailsTitle">
                <!-- Previous Arrow for Navigation -->
                <div class="prev-arrow" tabindex="0" aria-label="Previous">
                    <div class="prev-arrow-top"></div>
                    <div class="prev-arrow-bottom"></div>
                </div>
                <div class="lead-details" id="leadDetails">
                    <button class="close-btn" id="closeBtn" aria-label="Close Modal">&times;</button>
                    <div id="leadDetailsContent"></div>
                    <label class="follow-up-label">Need to Follow-Up?
                        <input type="radio" name="followUpOption" id="followUpNo" value="No" checked> No
                        <input type="radio" name="followUpOption" id="followUpYes" value="Yes"> Yes
                    </label>
                    <textarea id="leadNotes" placeholder="Enter notes here..."></textarea>
                    <div class="modal-buttons">
                        <button id="saveNotesButton">Save</button>
                        <button id="cancelButton" class="cancel-btn">Cancel</button>
                    </div>
                </div>
                <!-- Next Arrow for Navigation -->
                <div class="next-arrow" role="button" aria-label="Next Lead" tabindex="0">
                    <div class="next-arrow-top"></div>
                    <div class="next-arrow-bottom"></div>
                </div>
            </div>
            <!-- Scroll to Top Button -->
            <button class="scroll-top" aria-label="Scroll to Top">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <!-- Scroll to Bottom Button -->
            <button class="scroll-bottom" aria-label="Scroll to Bottom">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
        <?php endif; ?>
    </div>
    <script>
        const headers = <?php echo json_encode($headers); ?>;
        const leads = <?php echo json_encode($leads); ?>;
    </script>
    <script src="js/leads_script.js"></script>
</body>
</html>