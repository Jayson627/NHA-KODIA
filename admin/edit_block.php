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

// Get the block number from the URL
$block_no = isset($_GET['block_no']) ? $_GET['block_no'] : null;

if (!$block_no) {
    echo "No block number specified.";
    exit();
}

// Handle form submission for editing the block
if (isset($_POST['edit_block'])) {
    $new_block_no = $_POST['new_block_no'];
    $stmt = $conn->prepare("UPDATE blocks SET block_no = ? WHERE block_no = ?");
    $stmt->bind_param("ss", $new_block_no, $block_no);
    if ($stmt->execute()) {
        // Redirect to avoid resubmission
        header("Location: block");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Retrieve the current block number from the database
$stmt = $conn->prepare("SELECT block_no FROM blocks WHERE block_no = ?");
$stmt->bind_param("s", $block_no);
$stmt->execute();
$stmt->bind_result($current_block_no);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Block</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Block</h2>
    <form method="POST" action="edit_block?block_no=<?php echo urlencode($block_no); ?>">
        <div class="form-group">
            <label for="new_block_no">New Block No</label>
            <input type="text" class="form-control" id="new_block_no" name="new_block_no" value="<?php echo htmlspecialchars($current_block_no); ?>" required>
        </div>
        <button type="submit" name="edit_block" class="btn btn-primary">Save Changes</button>
        <a href="block" class="btn btn-secondary">Cancel</a>
    
    </form>
</div>
</body>
</html>
