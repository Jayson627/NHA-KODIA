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

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit;

$cid = $_GET['id'];
// Handle form submission for adding a new child
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_child'])) {

    $cid = $_POST['cid'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];

    // Insert new child record
    $stmt = $conn->prepare("INSERT INTO children (child_id, name, age, gender, status, birthdate, educational_attainment, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssss", $cid, $name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number);
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
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control, .btn, .table {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .pagination a {
            color: #007bff;
        }
        .pagination a:hover {
            color: #0056b3;
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
    <a href="./?page=students" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
    <!-- Form to add a new child -->
    <form method="POST" action="children.php" class="form-inline mb-3">
        <input type="hidden" class="form-control mb-2 mr-sm-2" id="cid" name="cid" placeholder="id" value="<?php echo $cid; ?>" required>
        <input type="text" class="form-control mb-2 mr-sm-2" id="name" name="name" placeholder="Name" required>
        <input type="number" class="form-control mb-2 mr-sm-2" id="age" name="age" placeholder="Age" min="1" required>
        <select class="form-control mb-2 mr-sm-2" id="gender" name="gender" required>
            <option value="" disabled selected>Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <select class="form-control mb-2 mr-sm-2" id="status" name="status" required>
            <option value="" disabled selected>Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widow">Widowed</option>
        </select>
        <input type="date" class="form-control mb-2 mr-sm-2" id="birthdate" name="birthdate" required>
        <select class="form-control mb-2 mr-sm-2" id="educational_attainment" name="educational_attainment" required>
            <option value="" disabled selected>Education</option>
            <option value="Elementary">Elementary</option>
            <option value="Elementary Undergraduate">Elementary Undergraduate</option>
            <option value="High School">High School</option>
            <option value="High School Undergraduate">High School Undergraduate</option>
            <option value="College">College</option>
            <option value="College Undergraduate">College Undergraduate</option>
            <option value="Vocational">Vocational</option>
            <option value="Post Graduate">Post Graduate</option>
            <option value="None">None</option>
        </select>
        <input type="text" class="form-control mb-2 mr-sm-2" id="contact_number" name="contact_number" placeholder="Contact Number" required>
        <button type="submit" name="add_child" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> Add</button>
    </form>
</body>
</html>
