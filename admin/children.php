<?php
include_once('connection.php'); 

// Initialize error message variable
$error_message = "";

// Success flag
$success = false;

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit;

$cid = $_GET['id'] ?? null; // Use null coalescing to avoid undefined index notice

// Handle form submission for adding a new child
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_child'])) {

    $cid = $_POST['cid'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $extension_name = $_POST['extension_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];
    $remark = $_POST['remark'];

    // Insert new child record
    $stmt = $conn->prepare("INSERT INTO children (child_id, first_name, middle_name, last_name, extension_name, age, gender, status, birthdate, educational_attainment, contact_number, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("issssssssss", $cid, $first_name, $middle_name, $last_name, $extension_name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number, $remark);

    if ($stmt->execute()) {
        $success = true; // Set success flag
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
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
    <script>
        function validateName(input) {
            input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
        }

        function validateContactNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11);
            }
        }

        function calculateAge() {
            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');
            const birthdate = new Date(birthdateInput.value);
            const today = new Date();
            if (birthdate) {
                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }
                ageInput.value = age;
            } else {
                ageInput.value = '';
            }
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Children Management</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <a href="./?page=students" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
    
    <!-- Form to add a new child -->
    <form method="POST" action="children" class="form-inline mb-3">
        <input type="hidden" class="form-control mb-2 mr-sm-2" id="cid" name="cid" value="<?php echo htmlspecialchars($cid); ?>" required>
        
        <input type="text" class="form-control mb-2 mr-sm-2" id="first_name" name="first_name" placeholder="First Name" oninput="validateName(this)" required>
        
        <input type="text" class="form-control mb-2 mr-sm-2" id="middle_name" name="middle_name" placeholder="Middle Name" oninput="validateName(this)">
        
        <input type="text" class="form-control mb-2 mr-sm-2" id="last_name" name="last_name" placeholder="Last Name" oninput="validateName(this)" required>
        
        <input type="text" class="form-control mb-2 mr-sm-2" id="extension_name" name="extension_name" placeholder="Extension (e.g. Jr., Sr.)" oninput="validateName(this)">
        
        <input type="number" class="form-control mb-2 mr-sm-2" id="age" name="age" placeholder="Age" min="1" readonly required>
        
        <select class="form-control mb-2 mr-sm-2" id="gender" name="gender" required>
            <option value="" disabled selected>Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        
        <select class="form-control mb-2 mr-sm-2" id="status" name="status" required>
            <option value="" disabled selected>Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widow">Widowed</option>
        </select>
        
        <input type="date" class="form-control mb-2 mr-sm-2" id="birthdate" name="birthdate" required onchange="calculateAge()">
        
        <select class="form-control mb-2 mr-sm-2" id="educational_attainment" name="educational_attainment" required>
            <option value="" disabled selected>Education</option>
            <option value="Elementary">Elementary</option>
            <option value="High School">High School</option>
            <option value="College">College</option>
            <option value="Vocational">Vocational</option>
            <option value="Post Graduate">Post Graduate</option>
            <option value="None">None</option>
        </select>
        <input type="text" class="form-control mb-2 mr-sm-2" id="contact_number" name="contact_number" placeholder="Contact Number" oninput="validateContactNumber(this)">
        <textarea class="form-control mb-2 mr-sm-2" id="remark" name="remark" placeholder="Remarks (optional)" rows="3"></textarea>
        <button type="submit" name="add_child" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> Add</button>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($success): ?>
            swal("Success!", "Record has been added!", "success").then(() => {
                window.location.reload();
            });
        <?php elseif (!empty($error_message)): ?>
            swal("Error!", "<?php echo addslashes($error_message); ?>", "error");
        <?php endif; ?>
    });
</script>
</body>
</html>
