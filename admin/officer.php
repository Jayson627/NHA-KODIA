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


// Handle form submission for adding, editing, and deleting officers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_officer'])) {
        $name = $_POST['name'];
        $purok = $_POST['purok'];
        $password = $_POST['password'];
        $sql = "INSERT INTO officers (name, purok, password) VALUES ('$name', '$purok', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert success'>New officer added successfully</div>";
        } else {
            echo "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['edit_officer'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $purok = $_POST['purok'];
        $password = $_POST['password'];
        $sql = "UPDATE officers SET name='$name', purok='$purok', password='$password' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert success'>Officer updated successfully</div>";
        } else {
            echo "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete_officer'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM officers WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert success'>Officer deleted successfully</div>";
        } else {
            echo "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}

// Fetch all officers
$sql = "SELECT * FROM officers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Officers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-container, .table-container {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .form-container input[type="text"], .form-container input[type="password"], .form-container button {
            padding: 10px;
            margin: 5px 0;
            width: 48%;
        }
        .form-container button {
            padding: 10px;
            margin: 5px 0;
            width: 48%;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button.add {
            background: #28a745;
            color: #fff;
        }
        .form-container button.add:hover {
            background: #218838;
        }
        .form-container button.edit {
            background: #007bff;
            color: #fff;
        }
        .form-container button.edit:hover {
            background: #0056b3;
        }
        .form-container button.delete {
            background: #dc3545;
            color: #fff;
        }
        .form-container button.delete:hover {
            background: #c82333;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-container th {
            background: #333;
            color: #fff;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        .actions button {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .actions button.edit {
            background: #007bff;
            color: #fff;
        }
        .actions button.edit:hover {
            background: #0056b3;
        }
        .actions button.delete {
            background: #dc3545;
            color: #fff;
        }
        .actions button.delete:hover {
            background: #c82333;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
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
            <input type="password" name="password" id="add_password" placeholder="Password" required autocomplete="new-password">
            <label><input type="checkbox" onclick="togglePasswordVisibility('add_password')"> Show Password</label>
            <button type="submit" name="add_officer" class="add">Add Officer</button>
        </form>
    </div>

    <div id="edit_form_container" class="form-container" style="display: none;">
        <h2>Edit Officer</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" placeholder="Name" required autocomplete="off">
            <input type="text" name="purok" id="edit_purok" placeholder="Purok" required autocomplete="off">
            <input type="password" name="password" id="edit_password" placeholder="New Password" autocomplete="new-password">
            <label><input type="checkbox" onclick="togglePasswordVisibility('edit_password')"> Show Password</label>
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
                <th>Password</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['purok'] . "</td>";
                    echo "<td>" . $row['password'] . "</td>";
                    echo "<td class='actions'>";
                    echo "<button class='edit' onclick=\"editOfficer(" . $row['id'] . ",'" . $row['name'] . "','" . $row['purok'] . "','" . $row['password'] . "')\">Edit</button>";
                    echo "<form method='POST' action='' style='display:inline-block; margin-left: 5px;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_officer' class='delete'>Delete</button>
                          </form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No officers found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
    function togglePasswordVisibility(id) {
        var passwordField = document.getElementById(id);
        var checkbox = event.target;

        if (checkbox.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }

    function editOfficer(id, name, purok, password) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_purok').value = purok;
        document.getElementById('edit_password').value = ''; // Clear password field
        document.getElementById('edit_form_container').style.display = 'block'; // Show the edit form
        window.scrollTo(0, document.getElementById('edit_form_container').offsetTop); // Scroll to the edit form
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
