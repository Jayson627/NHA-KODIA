<?php
session_start();
include_once('connection.php');

// Fetch announcements from the database
$announcements = [];
$result = $conn->query("SELECT * FROM announcements WHERE CURDATE() BETWEEN start_date AND end_date");

if ($result) {
    $announcements = $result->fetch_all(MYSQLI_ASSOC);
} else {
    error_log("Database query failed: " . $conn->error);
}
// Default query to fetch all households
$sql = "SELECT *,
                concat(lastname, ', ', firstname, ' ', middlename, ' ', owner_extension) AS fullname,
                concat(spouse_lastname, ', ', spouse_firstname, ' ', spouse_middlename, ' ', spouse_extension) AS spouse_fullname
        FROM student_list";

// Check if a block number is selected and update the query accordingly
if (isset($_GET['block_no']) && !empty($_GET['block_no'])) {
    $block_no = intval($_GET['block_no']);
    $sql .= " WHERE block_no = $block_no";
}

// Execute the query
$qry = $conn->query($sql);

// Handle announcement creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_announcement'])) {
    $announcement = mysqli_real_escape_string($conn, $_POST['announcement']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    
    // Insert the new announcement into the database
    $sql = "INSERT INTO announcements (announcement, start_date, end_date) 
            VALUES ('$announcement', '$start_date', '$end_date')";
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = 'Announcement created successfully!';
    } else {
        $_SESSION['message'] = 'Error creating announcement: ' . $conn->error;
    }
    
    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements & Incident Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
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
        .icons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn {
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
        }
        .btn i {
            margin-right: 8px;
        }
        .btn:hover {
            background-color: blue;
        }
        .welcome-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            animation: fadeIn 3s ease-in-out infinite alternate;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translate(-50%, -60%); }
            100% { opacity: 1; transform: translate(-50%, -50%); }
        }
        .announcement-container, .incident-form-container, .create-announcement-container {
            padding: 20px;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: black;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .incident-form, .create-announcement-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            color: black;
            max-width: 400px;
            margin: 0 auto;
        }
        .incident-form h3, .create-announcement-form h3 {
            color: #007BFF;
        }
        .incident-form label, .create-announcement-form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        .incident-form select, .create-announcement-form select, .incident-form input, .create-announcement-form input, .incident-form textarea, .create-announcement-form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .incident-form button, .create-announcement-form button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .incident-form button:hover, .create-announcement-form button:hover {
            background-color: #0056b3;
        }
        /* Close Button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Dashboard</h1>
    <div class="icons">
        <a href="#" class="btn" onclick="toggleAnnouncements()">
            <i class="fas fa-bell"></i> Admin Announcements
        </a>
        <a href="#" class="btn" onclick="toggleIncidentForm()">
            <i class="fas fa-exclamation-triangle"></i> Report Incident
        </a>
        
        <a href="#" class="btn" onclick="toggleHouseholdResidents()">
    <i class="fas fa-users"></i> Household Residents
</a>

        <a href="residents.php" class="btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="welcome-text" id="welcomeText">Welcome</div>

<!-- Announcements Section -->
<div class="announcement-container" id="announcementContainer" style="display: none;">
    <h2>Current Announcements</h2>
    <?php if (count($announcements) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Announcement</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($announcements as $announcement): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($announcement['announcement']); ?></td>
                        <td><?php echo htmlspecialchars($announcement['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($announcement['end_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No announcements available at the moment.</p>
    <?php endif; ?>
</div>

<!-- Incident Report Form -->
<div class="incident-form-container" id="incidentFormContainer" style="display: none;">

    <form class="incident-form" action="submit_incident.php" method="POST">
        <h3>Incident Report</h3>
        
        <label for="incidentType">Incident Type</label>
        <select name="incidentType" id="incidentType" required>
            <option value="Fire">Fire</option>
            <option value="Accident">Accident</option>
            <option value="Flood">Flood</option>
            <option value="Theft">Theft</option>
            <option value="Other">Other</option>
        </select>
        
        <label for="reportBy">Report By</label>
        <input type="text" name="reportBy" id="reportBy" placeholder="Your name" required>
        
        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Describe the incident" rows="4" required></textarea>
        
        <label for="date">Date</label>
        <input type="date" name="date" id="date" required>
        
        <button type="submit">Submit Report</button>
    </form>
</div>
<div id="blockSelectContainer" style="margin-top: 20px; text-align: center;">
    <label for="blockSelect" style="font-size: 1.2em; font-weight: bold; color: transparent;">Select Block Number:</label>
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
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;">
        <option value="" style="font-size: 1.1em; color: #007BFF;">Select Block</option>
        <?php for ($i = 1; $i <= 28; $i++): ?>
            <option value="<?php echo $i; ?>" style="font-size: 1.1em;"><?php echo "Block " . $i; ?></option>
        <?php endfor; ?>
    </select>
</div>


<table class="table table-bordered table-hover table-striped" id="household-table">
    <thead>
        <tr class="bg-gradient-dark text-light">
            <th>#</th>
            <th>Name</th>
            <th>Age</th>
            <th>Spouse Name</th>
            <th>Spouse Age</th>
            <th>Block</th>
            <th>Lot</th>
            <th>Contact No.</th>
            <th>Barangay</th> 
            <th>Action</th>
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
            <td><p class="m-0 truncate-1"><?php echo $row['owner_age']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo !empty($row['spouse_fullname']) ? $row['spouse_fullname'] : 'N/A'; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['spouse_age']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['block_no']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['lot_no']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['contact']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['present_address']; ?></p></td>
            <td><p class="m-0 truncate-1"><?php echo $row['message']; ?></p></td>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>


<script>
    function toggleHouseholdResidents() {
        var container = document.getElementById('household-table');
        var blockSelectContainer = document.getElementById('blockSelectContainer'); // We will use this for the block select dropdown
        var welcomeText = document.getElementById('welcomeText');
        
        // Check if the table is currently visible
        if (container.style.display === 'none' || container.style.display === '') {
            container.style.display = 'table'; // Show the table
            blockSelectContainer.style.display = 'block'; // Show the block selection dropdown
            welcomeText.style.display = 'none'; // Hide the welcome text
        } else {
            container.style.display = 'none'; // Hide the table
            blockSelectContainer.style.display = 'none'; // Hide the block selection dropdown
            welcomeText.style.display = 'block'; // Show the welcome text
        }
    }

    // Other functions remain unchanged
    function toggleAnnouncements() {
        var container = document.getElementById('announcementContainer');
        var welcomeText = document.getElementById('welcomeText');
        container.style.display = (container.style.display === 'none') ? 'block' : 'none';
        welcomeText.style.display = (container.style.display === 'block') ? 'none' : 'block'; // Hide welcome text
    }

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

    function toggleIncidentForm() {
        var container = document.getElementById('incidentFormContainer');
        var welcomeText = document.getElementById('welcomeText');
        container.style.display = (container.style.display === 'none') ? 'block' : 'none';
        welcomeText.style.display = (container.style.display === 'block') ? 'none' : 'block'; // Hide welcome text
    }

   
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['message'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['message']; ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
});
</script>

</body>
</html>
