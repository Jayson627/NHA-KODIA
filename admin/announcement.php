<?php
include_once('connection.php'); 

// Handle the form submission (saving new announcement)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['announcement'])) {
    $announcement = htmlspecialchars($_POST['announcement']); // Sanitize the content
    $user = 'Admin'; // You can replace this with the logged-in user's name

    // Insert the new announcement into the database with the current timestamp
    $stmt = $conn->prepare("INSERT INTO announcement (user, content, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $user, $announcement);

    if ($stmt->execute()) {
        $successMessage = "Announcement posted successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}

// Automatically delete announcements older than 2 days
$currentDate = date('Y-m-d H:i:s');
$deleteQuery = "DELETE FROM announcement WHERE created_at < DATE_SUB(?, INTERVAL 2 DAY)";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$stmt->close();

// Handle deletion of an announcement by user
if (isset($_GET['delete'])) {
    $announcementId = (int)$_GET['delete']; // Ensure it's an integer
    $deleteQuery = "DELETE FROM announcement WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $announcementId);

    if ($stmt->execute()) {
        $successMessage = "Announcement deleted successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle editing of an announcement
if (isset($_GET['edit'])) {
    $announcementId = (int)$_GET['edit']; // Ensure it's an integer

    // Fetch the announcement to be edited
    $sql = "SELECT * FROM announcement WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $announcementId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the announcement exists
    if ($result->num_rows > 0) {
        $announcement = $result->fetch_assoc();
    } else {
        $errorMessage = "Announcement not found.";
        exit;  // Exit if the announcement is not found
    }
    $stmt->close();
}

// Handle the form submission for updating an announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updated_announcement'])) {
    $updatedAnnouncement = htmlspecialchars($_POST['updated_announcement']);
    $announcementId = (int)$_GET['edit'];

    // Update the announcement in the database
    $updateQuery = "UPDATE announcement SET content = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $updatedAnnouncement, $announcementId);

    if ($stmt->execute()) {
        $successMessage = "Announcement updated successfully!";
        // Redirect to avoid resubmitting the form on refresh
        header("Location: announcement.php");
        exit;
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all announcements (those that are less than 2 days old)
$sql = "SELECT * FROM announcement ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Page</title>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* General styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        h2 {
            font-size: 20px;
            color: #333;
        }

        /* Announcement Form Styles */
        .announcement-form {
            margin-bottom: 30px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            resize: vertical;
        }

        button {
            background-color: #1877f2;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #165eab;
        }

        /* Announcement Display Section */
        .announcements {
            margin-top: 20px;
        }

        .announcement {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .announcement:last-child {
            border-bottom: none;
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #777;
        }

        .user {
            font-weight: bold;
        }

        .date {
            font-style: italic;
        }

        .announcement-content p {
            font-size: 16px;
            color: #333;
        }

        /* Edit and Delete button styles */
        .delete-btn, .edit-btn {
            background-color: #f44336; /* Red for delete */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .edit-btn {
            background-color: #4caf50; /* Green for edit */
        }

        .edit-btn:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <header>
        <h1>Site Announcements</h1>
    </header>

    <!-- Announcement Form Section -->
    <section class="announcement-form">
        <h2>Create a New Announcement</h2>
        <form action="announcement.php" method="POST">
            <textarea name="announcement" rows="4" placeholder="Write your announcement here..." required></textarea>
            <button type="submit">Post Announcement</button>
        </form>
    </section>

    <!-- Announcement Edit Section (Only show if in edit mode) -->
    <?php if (isset($announcement) && is_array($announcement)) : ?>
        <section class="announcement-form">
            <h2>Edit Announcement</h2>
            <form action="announcement.php?edit=<?= $announcement['id'] ?>" method="POST">
                <textarea name="updated_announcement" rows="4" required><?= htmlspecialchars($announcement['content']) ?></textarea>
                <button type="submit">Update Announcement</button>
            </form>
        </section>
    <?php endif; ?>

    <!-- Display All Announcements -->
    <section class="announcements">
        <h2>All Announcements</h2>
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="announcement">
                    <div class="announcement-header">
                        <span class="user"><?= $row['user'] ?></span>
                        <span class="date"><?= date('F j, Y', strtotime($row['created_at'])) ?></span>
                    </div>
                    <div class="announcement-content">
                        <p><?= $row['content'] ?></p>
                    </div>
                    <!-- Edit and Delete Buttons -->
                    <div>
                        <a href="announcement.php?edit=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements yet.</p>
        <?php endif; ?>
    </section>
</div>

<!-- SweetAlert2 success or error messages -->
<script>
    <?php if (isset($successMessage)) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= $successMessage ?>',
            confirmButtonText: 'OK'
        });
    <?php elseif (isset($errorMessage)) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= $errorMessage ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
