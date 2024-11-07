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
        .announcement-container, .incident-form-container {
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
        .incident-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            color: black;
            max-width: 400px;
            margin: 0 auto;
        }
        .incident-form h3 {
            color: #007BFF;
        }
        .incident-form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        .incident-form select, .incident-form input, .incident-form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .incident-form button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .incident-form button:hover {
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
            <i class="fas fa-bell"></i> Announcements
        </a>
        <a href="#" class="btn" onclick="toggleIncidentForm()">
            <i class="fas fa-exclamation-triangle"></i> Report Incident
        </a>
        <a href="residents.php" class="btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="welcome-text" id="welcomeText">Welcome </div>

<!-- Announcements Section -->
<div class="announcement-container" id="announcementContainer" style="display: none;">
    <button class="close-btn" onclick="toggleAnnouncements()">×</button>
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
    <button class="close-btn" onclick="toggleIncidentForm()">×</button>
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

<script>
    function toggleAnnouncements() {
        var container = document.getElementById('announcementContainer');
        var welcomeText = document.getElementById('welcomeText');
        container.style.display = (container.style.display === 'none') ? 'block' : 'none';
        welcomeText.style.display = (container.style.display === 'block') ? 'none' : 'block'; // Hide welcome text
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
