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
    <title>Edit Leads | LeadHustle.pro</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <!-- Font Awesome CDN -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/edit_leads_styles.css">
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
            <a href="leads.php">
                <span class="menu-icon"><i class="fa-solid fa-user-group"></i></span>
                View Leads
            </a>
            <a href="upload.php">
                <span class="menu-icon"><i class="fa-solid fa-user-plus"></i></span>
                Add Leads
            </a>
            <a href="edit-leads.php" class="active">
                <span class="menu-icon"><i class="fa-regular fa-pen-to-square"></i></span>
                Edit Leads
            </a>
            <a href="php/logout.php">
                <span class="menu-icon"><i class="fa fa-sign-out-alt"></i></span>
                Logout
            </a>
        </div>
        <!-- Hamburger Icon -->
        <div class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false" role="button">
            <i class="fa fa-bars"></i>
        </div>
    </nav>
    <div class="container">
        <!-- Notification Message Container -->
        <div id="notification" class="notification" role="alert">
            <span id="message"></span>
            <button class="close-btn" onclick="hideNotification()">&times;</button>
        </div>
        <h1>Edit Leads</h1>
        <?php if (!empty($noLeadsMessage)): ?>
            <div class="no-leads-message">
                <p><?php echo $noLeadsMessage; ?></p>
            </div>
        <?php else: ?>
            <div class="top-bar">
                <input type="text" id="searchBar" placeholder="Search leads..." class="search-bar">
                <button class="export-btn">
                    <i class="fa-solid fa-download"></i>
                    Export Leads
                </button>
                <button id="SaveBtn" class="save-btn" disabled>
                    <i class="fa-solid fa-save"></i>
                    Save
                </button>
                <button id="CancelBtn" class="cancel-btn" disabled>
                    <i class="fa-solid fa-times-circle"></i>
                    Cancel
                </button>
                <div class="view-options">
                    <label for="viewSelect">View:</label>
                    <select id="viewSelect">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="leads-table-container">
            <table id="leadsTable">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th><?php echo str_replace('_', ' ', $header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <tr data-id="<?php echo htmlspecialchars($lead['id']); ?>">
                            <?php foreach ($headers as $header): ?>
                                <td>
                                    <?php if ($header === 'Follow_Up_Added_On'): ?>
                                        <?php echo htmlspecialchars($lead[$header]); ?>
                                    <?php else: ?>
                                        <span class="editable" data-field="<?php echo htmlspecialchars($header); ?>">
                                            <?php echo htmlspecialchars($lead[$header] ?: ''); ?>
                                        </span>
                                        <input type="text" class="edit-input" data-field="<?php echo htmlspecialchars($header); ?>" value="<?php echo htmlspecialchars($lead[$header] ?: ''); ?>" style="display: none;">
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <!-- Pagination Controls -->
            <div class="pagination-controls">
                <button id="prevPage" disabled>&laquo; Previous</button>
                <span id="currentPage">1</span> / <span id="totalPages">1</span>
                <button id="nextPage">Next &raquo;</button>
            </div>
        <?php endif; ?>
    </div>
    <script>
        const headers = <?php echo json_encode($headers); ?>;
        const leads = <?php echo json_encode($leads); ?>;
    </script>
    <script src="js/edit_leads_script.js"></script> <!-- New JS for Edit Leads -->
</body>
</html>