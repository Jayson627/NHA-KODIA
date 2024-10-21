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

// Initialize error message variable
$error_message = "";

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number

$start = ($page - 1) * $limit;

// Handle form submission for adding a new block
if (isset($_POST['add_block'])) {
    $block_no = $_POST['block_no'];

    // Check if block number is a positive integer
    if (!filter_var($block_no, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $error_message = "Error: Block number must be a positive integer!";
    } else {
        // Check if block number already exists
        $stmt = $conn->prepare("SELECT block_no FROM blocks WHERE block_no = ?");
        $stmt->bind_param("s", $block_no);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Block number already exists
            $error_message = "Error: Block number already exists!";
        } else {
            // Insert new block number
            $stmt = $conn->prepare("INSERT INTO blocks (block_no) VALUES (?)");
            $stmt->bind_param("s", $block_no);
            $stmt->execute();
            // Redirect to avoid resubmission
            header("Location: block.php");
            exit();
        }
        $stmt->close();
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $block_no_to_delete = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM blocks WHERE block_no = ?");
    $stmt->bind_param("s", $block_no_to_delete);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission
    header("Location: block.php");
    exit();
}

// Retrieve blocks for current page
$blocks = [];
$result = $conn->query("SELECT block_no FROM blocks LIMIT $start, $limit");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blocks[] = $row['block_no'];
    }
}

// Count total number of records
$total_records = $conn->query("SELECT COUNT(*) AS total FROM blocks")->fetch_assoc()['total'];

$conn->close();
?>
<a href="index.php" class="btn btn-secondary">Back</a>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Block Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 50px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
        }
        .pagination a {
            color: #007bff;
        }
        .pagination .active a {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .form-inline {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-inline .form-group {
            margin-right: 10px;
        }
        .form-inline .btn {
            margin-left: 10px;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
   
    <h2 class="text-center mb-4">Block Management</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Form to add a new block -->
    <form method="POST" action="block.php" class="form-inline mb-3 justify-content-center">
        <div class="form-group">
            <label for="block_no" class="sr-only">Block No</label>
            <input type="number" class="form-control" id="block_no" name="block_no" placeholder="Enter block no" min="1" required>
        </div>
        <button type="submit" name="add_block" class="btn btn-success">Add Block</button>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
        <tr>
            <th>Block No</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($blocks)): ?>
            <?php foreach ($blocks as $block_no): ?>
                <tr>
                    <td><?php echo htmlspecialchars($block_no); ?></td>
                    <td>
                        <a href="view_block.php?block_no=<?php echo urlencode($block_no); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_block.php?block_no=<?php echo urlencode($block_no); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="block.php?delete=<?php echo urlencode($block_no); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this block?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" class="text-center">No blocks found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination buttons -->
    <div class="text-center">
        <?php if ($page > 1): ?>
            <a href="block.php?page=<?php echo $page - 1; ?>" class="btn btn-secondary mr-2">&laquo; Previous</a>
        <?php endif; ?>
        
        <?php if ($total_records > $start + $limit): ?>
            <a href="block.php?page=<?php echo $page + 1; ?>" class="btn btn-secondary">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
