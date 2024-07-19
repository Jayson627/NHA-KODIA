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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];

    $stmt = $conn->prepare("UPDATE children SET name = ?, age = ?, gender = ?, status = ?, birthdate = ?, educational_attainment = ?, contact_number = ? WHERE id = ?");
    $stmt->bind_param("sisssssi", $name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number, $child_id);
    if ($stmt->execute()) {
        echo "Success: Record has been updated!";
    } else {
        echo "Error: Could not update the record. Please try again.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Child</title>
    <link rel="stylesheet" href="path/to/your/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Child</h2>
        <form action="edit_child.php?id=<?= htmlspecialchars($child['id']) ?>" method="post">
            <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($child['name']) ?>"></label>
            <label>Age: <input type="number" name="age" value="<?= htmlspecialchars($child['age']) ?>"></label>
            <label>Gender: <input type="text" name="gender" value="<?= htmlspecialchars($child['gender']) ?>"></label>
            <label>Status: <input type="text" name="status" value="<?= htmlspecialchars($child['status']) ?>"></label>
            <label>Birthdate: <input type="date" name="birthdate" value="<?= htmlspecialchars($child['birthdate']) ?>"></label>
            <label>Educational Attainment: <input type="text" name="educational_attainment" value="<?= htmlspecialchars($child['educational_attainment']) ?>"></label>
            <label>Contact Number: <input type="text" name="contact_number" value="<?= htmlspecialchars($child['contact_number']) ?>"></label>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
