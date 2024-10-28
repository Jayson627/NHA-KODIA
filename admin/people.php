<?php
session_start(); // Start a session
include_once('connection.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $house_no = htmlspecialchars(trim($_POST['house_no']));
    $lot_no = htmlspecialchars(trim($_POST['lot_no']));
    $block_no = htmlspecialchars(trim($_POST['block_no']));
    $fullname = htmlspecialchars(trim($_POST['fullname']));

    // Strong validation: Check if inputs are numeric
    if (!preg_match('/^\d+$/', $house_no)) {
        $error_message = "House No must be a valid number.";
    } elseif (!preg_match('/^\d+$/', $lot_no)) {
        $error_message = "Lot No must be a valid number.";
    } elseif (!preg_match('/^\d+$/', $block_no)) {
        $error_message = "Block No must be a valid number.";
    } elseif (empty($fullname)) {
        $error_message = "Full Name is required.";
    } else {
        // Query to check if the data exists
        $qry = $conn->prepare("SELECT * FROM `student_list` WHERE `roll` = ? AND `block_no` = ? AND `lot_no` = ? AND CONCAT(lastname, ', ', firstname, ' ', middlename) = ?");
        $qry->bind_param("ssss", $house_no, $block_no, $lot_no, $fullname);
        $qry->execute();
        $result = $qry->get_result();

        if ($result->num_rows > 0) {
            // Match found, fetch first name and set success message
            $row = $result->fetch_assoc();
            $first_name = htmlspecialchars($row['firstname']);
            $success_message = "Welcome, $first_name!";
        } else {
            $error_message = "No matching household found.";
        }

        $qry->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('nha.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .header {
            width: 100%;
            background-color: #007bff;
            padding: 10px 0;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            color: #555;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 120px;
            background-color: #007bff;
            color: white;
            padding: 8px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="header" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007bff;">
    <div style="display: flex; align-items: center;">
        <img src="lo.png" alt="Logo" class="logo" style="height: 50px; width: auto; margin-right: 10px;">
        <span style="color: white; font-size: 24px;">NHA Residents Login Portal</span>
    </div>
    <a href="about.php" style="padding: 14px; color: white; text-decoration: none; font-size: 20px;">Home</a>
</div>

<div class="form-container">
    <h1>Login Form</h1>
    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="house_no">House No:</label>
        <input type="text" id="house_no" name="house_no" placeholder="Enter House Number" required pattern="\d+">

        <label for="lot_no">Lot No:</label>
        <input type="text" id="lot_no" name="lot_no" placeholder="Enter Lot Number" required pattern="\d+">

        <label for="block_no">Block No:</label>
        <input type="text" id="block_no" name="block_no" placeholder="Enter Block Number" required pattern="\d+">

        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" placeholder="Enter Full Name" required>

        <div class="submit-container" style="text-align: center; margin-top: 15px;">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($success_message)): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo addslashes($success_message); ?>',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'people_dashboard.php';
            });
        <?php elseif (!empty($error_message)): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo addslashes($error_message); ?>',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        <?php endif; ?>
    });
</script>
</body>
</html>
