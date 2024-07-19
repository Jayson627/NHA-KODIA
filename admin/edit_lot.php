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

// Get the lot number from the URL
$lot_no = isset($_GET['lot_no']) ? $_GET['lot_no'] : null;

if (!$lot_no) {
    echo "No lot number specified.";
    exit();
}

// Handle form submission for editing the lot
if (isset($_POST['edit_lot'])) {
    $new_lot_no = $_POST['new_lot_no'];
    $stmt = $conn->prepare("UPDATE lots SET lot_no = ? WHERE lot_no = ?");
    $stmt->bind_param("ss", $new_lot_no, $lot_no);
    if ($stmt->execute()) {
        // Redirect to avoid resubmission
        header("Location: lot.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Retrieve the current lot number from the database
$stmt = $conn->prepare("SELECT lot_no FROM lots WHERE lot_no = ?");
$stmt->bind_param("s", $lot_no);
$stmt->execute();
$stmt->bind_result($current_lot_no);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Lot</h2>
    <form method="POST" action="edit_lot.php?lot_no=<?php echo urlencode($lot_no); ?>">
        <div class="form-group">
            <label for="new_lot_no">New Lot No</label>
            <input type="text" class="form-control" id="new_lot_no" name="new_lot_no" value="<?php echo htmlspecialchars($current_lot_no); ?>" required>
        </div>
        <button type="submit" name="edit_lot" class="btn btn-primary">Save Changes</button>
        <a href="lot.php" class="btn btn-secondary">Cancel</a>
        
    </form>
</div>
</body>
</html>
