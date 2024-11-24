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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter by Block</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Filter Household Owners by Block</h2>

    <!-- Filter Form -->
    <form method="GET" action="">
        <div class="mb-3">
            <label for="block_no" class="form-label">Select Block:</label>
            <select class="form-select" name="block_no" id="block_no" onchange="this.form.submit()">
                <option value="">-- Select Block --</option>
                <?php
                // Generate block options 1 to 27
                for ($i = 1; $i <= 27; $i++) {
                    $selected = (isset($_GET['block_no']) && $_GET['block_no'] == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>Block $i</option>";
                }
                ?>
            </select>
        </div>
    </form>

    <?php
    // Display the list based on the selected block
    if (isset($_GET['block_no']) && !empty($_GET['block_no'])) {
        $block_no = $conn->real_escape_string($_GET['block_no']);
        
        // Query to get all columns from student_list based on selected block
        $list_query = "SELECT * FROM student_list WHERE block_no = '$block_no'";
        $list_result = $conn->query($list_query);

        if ($list_result->num_rows > 0) {
            echo "<table class='table table-bordered table-striped mt-4'>
                    <thead>
                        <tr>
                           
                            <th>House No.</th>
                            <th>Full Name</th>
                            <th>Age</th>
                            <th>Spouse Name</th>
                            <th>Spouse Age</th>
                            <th>Block</th>
                            <th>Lot</th>
                            <th>Gender</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $list_result->fetch_assoc()) {
                echo "<tr>
                        
                        <td>{$row['roll']}</td>
                        <td>{$row['fullname']}</td>
                        <td>{$row['owner_age']}</td>
                        <td>{$row['spouse_name']}</td>
                        <td>{$row['spouse_age']}</td>
                        <td>{$row['block_no']}</td>
                        <td>{$row['lot_no']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['present_address']}</td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-center text-danger mt-4'>No records found for Block $block_no.</p>";
        }
    }
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
