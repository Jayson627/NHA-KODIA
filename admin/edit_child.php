<?php
// Database connection
$servername = "127.0.0.1:3306";
$username = "u510162695_sis_db";
$password = "1Sis_dbpassword";
$dbname = "u510162695_sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `children` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $child = $qry->fetch_array();
    } else {
        echo "Child not found.";
        exit();
    }
} else {
    echo "No child ID provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];

    $stmt = $conn->prepare("UPDATE children SET name=?, age=?, gender=?, status=?, birthdate=?, educational_attainment=?, contact_number=? WHERE id=?");
    $stmt->bind_param("sisssssi", $name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number, $_GET['id']);
    
    if ($stmt->execute()) {
        echo "Child record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
    header("Location: view_child.php?id=" . $_GET['id']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Child</title>
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
            <h2 class="text-center">Edit Child Information</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($child['name']); ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="age">Age:</label>
                        <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($child['age']); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gender">Gender:</label>
                        <select class="form-control" name="gender" required>
                            <option value="Male" <?= $child['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?= $child['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" name="status" required>
                        <option value="Single" <?= $child['status'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?= $child['status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
                        <option value="Widowed" <?= $child['status'] == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birthdate">Birthdate:</label>
                    <input type="date" class="form-control" name="birthdate" value="<?= htmlspecialchars($child['birthdate']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="educational_attainment">Educational Attainment:</label>
                    <select class="form-control" name="educational_attainment" required>
                        <option value="None" <?= $child['educational_attainment'] == 'None' ? 'selected' : ''; ?>>None</option>
                        <option value="Elementary" <?= $child['educational_attainment'] == 'Elementary' ? 'selected' : ''; ?>>Elementary</option>
                        <option value="High School" <?= $child['educational_attainment'] == 'High School' ? 'selected' : ''; ?>>High School</option>
                        <option value="Vocational" <?= $child['educational_attainment'] == 'Vocational' ? 'selected' : ''; ?>>Vocational</option>
                        <option value="College" <?= $child['educational_attainment'] == 'College' ? 'selected' : ''; ?>>College</option>
                        <option value="Post Graduate" <?= $child['educational_attainment'] == 'Post Graduate' ? 'selected' : ''; ?>>Post Graduate</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($child['contact_number']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="view_child.php?id=<?= $_GET['id'] ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
