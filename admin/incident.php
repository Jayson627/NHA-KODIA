<?php
// Start session for handling form responses
session_start();

<?php
// Database credentials
$servername = "127.0.0.1:3306";
$username = "u510162695_sis_db";
$password = "1Sis_dbpassword";
$dbname = "u510162695_sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = "";

// Function to handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incidentType = trim($_POST['incident_type']);
    $description = trim($_POST['description']);
    $incidentDate = $_POST['incident_date'];

    // Basic validation
    if (empty($incidentType)) {
        $_SESSION['error'] = "Incident type is required.";
    } elseif (empty($description)) {
        $_SESSION['error'] = "Description is required.";
    } elseif (empty($incidentDate)) {
        $_SESSION['error'] = "Date of incident is required.";
    } else {
        // Insert data into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO incidents (incident_type, description, incident_date) VALUES (:incident_type, :description, :incident_date)");
            $stmt->execute([
                ':incident_type' => $incidentType,
                ':description' => $description,
                ':incident_date' => $incidentDate,
            ]);

            $_SESSION['success'] = "Incident successfully submitted.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Failed to submit incident: " . $e->getMessage();
        }
    }

    // Redirect back to clear POST data
    header('Location: incident.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Form</title>
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 12px;
        }
    </style>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Create an Incident</h1>
        <form action="incident.php" method="POST">
            <div class="form-group">
                <label for="incident_type">Incident Type</label>
                <input type="text" id="incident_type" name="incident_type" placeholder="Enter the incident type" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" placeholder="Describe the incident" required></textarea>
            </div>
            <div class="form-group">
                <label for="incident_date">Date of Incident</label>
                <input type="date" id="incident_date" name="incident_date" required>
            </div>
            <button type="submit">Submit Incident</button>
        </form>
    </div>
    <footer>&copy; <?php echo date("Y"); ?> Incident Management System</footer>

    <script>
        // Display success or error message using SweetAlert2
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_SESSION['success']; ?>',
                confirmButtonText: 'OK',
                timer: 3000
            });
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $_SESSION['error']; ?>',
                confirmButtonText: 'OK',
                timer: 3000
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
