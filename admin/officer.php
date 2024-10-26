<?php
include_once('connection.php'); 

// Handle form submission for adding, editing, and deleting officers
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_officer'])) {
        // Add officer
        $name = $_POST['name'];
        $purok = $_POST['purok'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO officers (name, purok, email, password) VALUES ('$name', '$purok', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "New officer added successfully";
        } else {
            $message = "Error: " . $conn->error;
        }
    } elseif (isset($_POST['edit_officer'])) {
        // Edit officer
        $id = $_POST['id'];
        $name = $_POST['name'];
        $purok = $_POST['purok'];
        $email = $_POST['email'];
        $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $sql = "UPDATE officers SET name='$name', purok='$purok', email='$email'" . ($password ? ", password='$password'" : "") . " WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $message = "Officer updated successfully";
        } else {
            $message = "Error: " . $conn->error;
        }
    } elseif (isset($_POST['delete_officer'])) {
        // Delete officer
        $id = $_POST['id'];
        $sql = "DELETE FROM officers WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $message = "Officer deleted successfully";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Fetch all officers
$sql = "SELECT * FROM officers";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error); // Show error if the query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Officers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container button {
            padding: 15px;
            margin: 10px 0;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-container input:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-container button {
            background: #28a745;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .form-container button:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .form-container label {
            display: flex;
            align-items: center;
            margin: 10px 0;
            color: #555;
        }
        .table-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-container th,
        .table-container td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table-container th {
            background: #007bff;
            color: #fff;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media (min-width: 600px) {
            .form-container input[type="text"],
            .form-container input[type="email"],
            .form-container input[type="password"],
            .form-container button {
                width: 48%;
            }
        }
        @media (max-width: 600px) {
            .actions {
                flex-direction: column;
            }
            .actions button {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Officers</h1>

    <div class="form-container">
        <h2>Add Officer</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Name" required autocomplete="off">
            <input type="text" name="purok" placeholder="Purok" required autocomplete="off">
            <input type="email" name="email" placeholder="Email" required autocomplete="off">
            <input type="password" name="password" id="add_password" placeholder="Password" required autocomplete="new-password">
            <label>
                <input type="checkbox" onclick="togglePasswordVisibility('add_password')"> Show Password
            </label>
            <button type="submit" name="add_officer" class="add">Add Officer</button>
        </form>
    </div>

    <div id="edit_form_container" class="form-container" style="display: none;">
        <h2>Edit Officer</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" placeholder="Name" required autocomplete="off">
            <input type="text" name="purok" id="edit_purok" placeholder="Purok" required autocomplete="off">
            <input type="email" name="email" id="edit_email" placeholder="Email" required autocomplete="off">
            <input type="password" name="password" id="edit_password" placeholder="New Password (leave blank to keep the same)" autocomplete="new-password">
            <label>
                <input type="checkbox" onclick="togglePasswordVisibility('edit_password')"> Show Password
            </label>
            <button type="submit" name="edit_officer" class="edit">Update Officer</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Officers List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Purok</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['purok']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>******</td>"; // Do not display the actual password
                    echo "<td class='actions'>";
                    echo "<button class='edit' onclick=\"editOfficer(" . $row['id'] . ",'" . addslashes($row['name']) . "','" . addslashes($row['purok']) . "','" . addslashes($row['email']) . "')\">Edit</button>";
                    echo "<form method='POST' action='' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_officer' class='delete'>Delete</button>
                          </form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No officers found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($message): ?>
            swal({
                title: "<?= htmlspecialchars($message) ?>",
                icon: "success",
                button: "OK",
            });
        <?php endif; ?>
    });

    function togglePasswordVisibility(id) {
        var passwordField = document.getElementById(id);
        var checkbox = event.target;

        if (checkbox.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }

    function editOfficer(id, name, purok, email) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_purok').value = purok;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_form_container').style.display = 'block';
        window.scrollTo(0, document.getElementById('edit_form_container').offsetTop);
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
