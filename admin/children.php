<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = "";

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit;

// Handle form submission for adding a new child
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_child'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];
    
    // Insert new child record
    $stmt = $conn->prepare("INSERT INTO children (name, age, gender, status, birthdate, educational_attainment, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Error: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sisssss", $name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number);
    if ($stmt->execute()) {
        $error_message = "Success: Record has been added!";
    } else {
        $error_message = "Error: Could not save the record. Please try again.";
    }
    $stmt->close();
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM children WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission
    header("Location: children.php");
    exit();
}

// Retrieve children for current page
$children = [];
$result = $conn->query("SELECT * FROM children LIMIT $start, $limit");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $children[] = $row;
    }
}

// Count total number of records
$total_records = $conn->query("SELECT COUNT(*) AS total FROM children")->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Children Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Custom CSS to enhance the appearance */
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black !important;
        }
        th, td {
            border-color: black !important;
            text-align: center;
        }
        .form-inline .form-group {
            margin-bottom: 15px;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Children Management</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-<?php echo strpos($error_message, 'Success') !== false ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <a href="./?page=students" class="btn btn-secondary mb-3"><i class="fa fa-angle-left"></i> Back</a>

    <!-- Form to add a new child -->
    <form method="POST" action="children.php" class="form-inline mb-3">
        <div class="form-group mx-sm-2">
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
        </div>
        <div class="form-group mx-sm-2">
            <input type="number" class="form-control" id="age" name="age" placeholder="Age" min="1" required>
        </div>
        <div class="form-group mx-sm-2">
            <select class="form-control" id="gender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group mx-sm-2">
            <select class="form-control" id="status" name="status" required>
                <option value="" disabled selected>Select Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widow">Widow</option>
            </select>
        </div>
        <div class="form-group mx-sm-2">
            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
        </div>
        <div class="form-group mx-sm-2">
            <select class="form-control" id="educational_attainment" name="educational_attainment" required>
                <option value="" disabled selected>Select Educational Attainment</option>
                <option value="Elementary">Elementary</option>
                <option value="Elementary Undergraduate">Elementary Undergraduate</option>
                <option value="High School">High School</option>
                <option value="High School Undergraduate">High School Undergraduate</option>
                <option value="College">College</option>
                <option value="College Undergraduate">College Undergraduate</option>
            </select>
        </div>
        <div class="form-group mx-sm-2">
            <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number" required>
        </div>
        <button type="submit" name="add_child" class="btn btn-success">Add Child</button>
    </form>

   
