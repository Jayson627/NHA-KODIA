<?php
session_start();
include_once('connection.php');

// Fetch announcements from the database
$announcementQuery = "SELECT * FROM announcement ORDER BY created_at DESC";
$announcementResult = $conn->query($announcementQuery);

// Check if the query was successful
if (!$announcementResult) {
    die("Query failed: " . $conn->error); // Show the error if the query fails
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

        /* Announcements Table Styles */
        .announcements-container {
            padding: 20px;
            color: white;
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

        /* Sidebar Menu */
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

        /* Media Query for Mobile View */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            .header .icons {
                display: none;
            }
            .sidebar {
                display: none; /* Initially hidden */
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
        <a href="incident" class="btn">
            <i class="fas fa-exclamation-circle"></i> Report Incident
        </a>
        <a href="#" class="btn" id="logoutBtn">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
    </div>
</div>

<div class="sidebar" id="sidebarMenu">
    <a href="incident.php"><i class="fas fa-exclamation-circle"></i> Report Incident</a>
    <a href="residents.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="welcome-text" id="welcomeText">Welcome</div>

<!-- Display Announcements -->
<div class="announcements-container">
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

document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default link behavior

    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your account.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log me out',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to logout URL
            window.location.href = 'residents.php'; // Replace with your logout URL
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const menuToggle = document.getElementById('menuToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    
    menuToggle.addEventListener('click', function() {
        sidebarMenu.classList.toggle('show');
    });

    // Display Success Message
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
