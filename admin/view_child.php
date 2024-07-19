<?php
// Database connection
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

if (isset($_GET['id'])) {
    $child_id = $_GET['id'];
    $qry = $conn->query("SELECT * FROM children WHERE id = '$child_id'");
    if ($qry->num_rows > 0) {
        $child = $qry->fetch_assoc();
    } else {
        echo "Child not found.";
        exit();
    }
} else {
    echo "No child ID provided.";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Child</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .table {
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 10px;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
        }
        .table td {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Child Details</h2>
        <table class="table table-bordered">
            <tr><th>Name:</th><td><?= htmlspecialchars($child['name']) ?></td></tr>
            <tr><th>Age:</th><td><?= htmlspecialchars($child['age']) ?></td></tr>
            <tr><th>Gender:</th><td><?= htmlspecialchars($child['gender']) ?></td></tr>
            <tr><th>Status:</th><td><?= htmlspecialchars($child['status']) ?></td></tr>
            <tr><th>Birthdate:</th><td><?= htmlspecialchars($child['birthdate']) ?></td></tr>
            <tr><th>Educational Attainment:</th><td><?= htmlspecialchars($child['educational_attainment']) ?></td></tr>
            <tr><th>Contact Number:</th><td><?= htmlspecialchars($child['contact_number']) ?></td></tr>
        </table>
    </div>
</body>
</html>
