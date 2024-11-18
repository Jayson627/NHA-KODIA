<?php
session_start();
include_once('connection.php');

// Fetch announcements from the database
$announcementQuery = "SELECT * FROM announcement ORDER BY created_at DESC";
$announcementResult = $conn->query($announcementQuery);

// Check if the query was successful
if (!$announcementResult) {
    die("Query failed: " . $conn->error);
}

// Default query to fetch all households
$sql = "SELECT *,
                CONCAT(lastname, ', ', firstname, ' ', middlename, ' ', owner_extension) AS fullname,
                CONCAT(spouse_lastname, ', ', spouse_firstname, ' ', spouse_middlename, ' ', spouse_extension) AS spouse_fullname
        FROM student_list";

// Check if a block number is selected and update the query accordingly
if (isset($_GET['block_no']) && !empty($_GET['block_no'])) {
    $block_no = intval($_GET['block_no']);
    $sql .= " WHERE block_no = $block_no";
}

// Execute the query
$qry = $conn->query($sql);

// Check if the query execution was successful
if (!$qry) {
    die("Query failed: " . $conn->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-image: url('houses.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        .header {
            background-color: rgba(0, 123, 255, 0.8);
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
        }
        .icons a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
        }
        .icons a:hover {
            text-decoration: underline;
        }
        .announcement-button {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .announcement-button:hover {
            background-color: #0056b3;
        }
        .announcements-container {
            padding: 20px;
            color: white;
            display: none;
        }
        table.announcement-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.announcement-table th, table.announcement-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table.announcement-table th {
            background-color: #007BFF;
            color: white;
        }
        .sidebar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: rgba(0, 123, 255, 0.9);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .menu-toggle {
            display: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            .header .icons {
                display: none;
            }
            .sidebar {
                display: none;
            }
            .sidebar.show {
                display: block;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Dashboard</h1>
    <span class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </span>
    <div class="icons" id="menuIcons">
        <a href="incident.php" class="btn">
            <i class="fas fa-exclamation-circle"></i> Report Incident
        </a>
        <a href="#" class="btn" onclick="toggleHouseholdResidents()">
            <i class="fas fa-users"></i> Household Residents
        </a>
        <a href="residents.php" class="btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <button class="announcement-button" id="announcementButton">View Announcements</button>
    </div>
</div>

<div id="blockSelectContainer" style="margin-top: 20px; text-align: center;">
    <label for="blockSelect" style="font-size: 1.2em; font-weight: bold;">Select Block Number:</label>
    <select id="blockSelect" onchange="filterHouseholdsByBlock()" style="
        font-size: 1.1em;
        padding: 12px;
        margin-top: 10px;
        width: 200px;
        border: 2px solid white;
        border-radius: 8px;
        background-color: white;
        color: black;
        text-align: center;
        cursor: pointer;">
        <option value="">Select Block</option>
        <?php for ($i = 1; $i <= 28; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo "Block " . $i; ?></option>
        <?php endfor; ?>
    </select>
</div>

<table class="table table-bordered table-hover table-striped" id="household-table">
    <thead>
        <tr class="bg-gradient-dark text-light">
            <th>#</th>
            <th>Name</th>
            <th>Spouse Name</th>
            <th>Block</th>
            <th>Lot</th>
            <th>Contact No.</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        while ($row = $qry->fetch_assoc()):
        ?>
        <tr>
            <td class="text-center"><?php echo $i++; ?></td>
            <td><p class="m-0 truncate-1"><?php echo $row['fullname']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo !empty($row['spouse_fullname']) ? $row['spouse_fullname'] : 'N/A'; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['block_no']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['lot_no']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['contact']; ?></p></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="sidebar" id="sidebarMenu">
    <a href="incident.php"><i class="fas fa-exclamation-circle"></i> Report Incident</a>
    <a href="residents.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="welcome-text" id="welcomeText">Welcome</div>

<div class="announcements-container" id="announcementsContainer">
    <h2>Announcements</h2>
    <?php if (isset($announcementResult) && $announcementResult->num_rows > 0): ?>
        <table class="announcement-table">
            <thead>
                <tr>
                    <th>Posted By</th>
                    <th>Announcement Date</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $announcementResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user']) ?></td>
                        <td><?= date('F j, Y', strtotime($row['created_at'])) ?></td>
                        <td><?= htmlspecialchars($row['content']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No announcements yet.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function filterHouseholdsByBlock() {
        var blockSelect = document.getElementById('blockSelect');
        var blockNo = blockSelect.value;

        // If a block is selected, reload the page with the block number as a query parameter
        if (blockNo) {
            window.location.href = "?block_no=" + blockNo;
        } else {
            // If no block is selected, reload the page without a block filter
            window.location.href = window.location.pathname;
        }
    }



document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    menuToggle.addEventListener('click', function() {
        sidebarMenu.classList.toggle('show');
    });
});
</script>

</body>
</html>
