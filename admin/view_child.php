<?php
include_once('connection.php'); 

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM `children` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $child = $result->fetch_array();
    } else {
        echo "Child not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "No child ID provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Child</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 2rem;
        }
        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Child Information</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>First Name:</strong> <?= htmlspecialchars($child['first_name']); ?></p>
                    <p><strong>Middle Name:</strong> <?= htmlspecialchars($child['middle_name']); ?></p>
                    <p><strong>Last Name:</strong> <?= htmlspecialchars($child['last_name']); ?></p>
                    <p><strong>Extension Name:</strong> <?= htmlspecialchars($child['extension_name']); ?></p>
                    <p><strong>Age:</strong> <?= htmlspecialchars($child['age']); ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($child['gender']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <?= htmlspecialchars($child['status']); ?></p>
                    <p><strong>Birthdate:</strong> <?= date('F j, Y', strtotime($child['birthdate'])); ?></p> <!-- Improved birthdate format -->
                    <p><strong>Educational Attainment:</strong> <?= htmlspecialchars($child['educational_attainment']); ?></p>
                    <p><strong>Contact Number:</strong> <?= htmlspecialchars($child['contact_number']); ?></p>
                    <p><strong>Remark:</strong> <?= htmlspecialchars($child['remark']); ?></p>
                </div>
            </div>
           
        </div>
    </div>
</div>

<!-- Add Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
