<?php
include_once('connection.php'); 

// Initialize error message variable
$error_message = "";

// Pagination setup
$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; 
$start = ($page - 1) * $limit;

// Handle form submission for adding a new block
if (isset($_POST['add_block'])) {
    $block_no = $_POST['block_no'];
    $lot_start = $_POST['lot_start'];
    $lot_end = $_POST['lot_end'];

    if (!filter_var($block_no, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ||
        !filter_var($lot_start, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ||
        !filter_var($lot_end, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ||
        $lot_start > $lot_end) {
        $error_message = "Error: Invalid input!";
    } else {
        // Check if block number already exists
        $stmt = $conn->prepare("SELECT block_no FROM blocks WHERE block_no = ?");
        $stmt->bind_param("s", $block_no);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Error: Block number already exists!";
        } else {
            // Insert new block number
            $stmt = $conn->prepare("INSERT INTO blocks (block_no) VALUES (?)");
            $stmt->bind_param("s", $block_no);
            $stmt->execute();
            
            // Insert lot numbers
            for ($lot = $lot_start; $lot <= $lot_end; $lot++) {
                $stmt = $conn->prepare("INSERT INTO lot_numbers (block_no, lot_number) VALUES (?, ?)");
                $stmt->bind_param("si", $block_no, $lot);
                $stmt->execute();
            }

            // Redirect to avoid resubmission
            header("Location: block");
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
    header("Location: block");
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

// Fetch lot numbers for each block
$block_lots = [];
foreach ($blocks as $block_no) {
    $stmt = $conn->prepare("SELECT MIN(lot_number) AS min_lot, MAX(lot_number) AS max_lot FROM lot_numbers WHERE block_no = ?");
    $stmt->bind_param("s", $block_no);
    $stmt->execute();
    $stmt->bind_result($min_lot, $max_lot);
    $stmt->fetch();
    $block_lots[$block_no] = ['min' => $min_lot, 'max' => $max_lot];
    $stmt->close();
}

// Count total number of records
$total_records = $conn->query("SELECT COUNT(*) AS total FROM blocks")->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Block and Lot Management</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Form to add a new block -->
    <form method="POST" action="block" class="form-inline mb-3 justify-content-center flex-wrap">
        <div class="form-group mb-2">
            <label for="block_no" class="sr-only">Block No</label>
            <input type="number" class="form-control" id="block_no" name="block_no" placeholder="Enter block no" min="1" required>
        </div>
        <div class="form-group mb-2">
            <label for="lot_start" class="sr-only">Lot Start</label>
            <input type="number" class="form-control" id="lot_start" name="lot_start" placeholder="Lot Start" min="1" required>
        </div>
        <div class="form-group mb-2">
            <label for="lot_end" class="sr-only">Lot End</label>
            <input type="number" class="form-control" id="lot_end" name="lot_end" placeholder="Lot End" min="1" required>
        </div>
        <button type="submit" name="add_block" class="btn btn-success mb-2">Add Block</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-gray">
            <tr>
                <th>Block No</th>
                <th>Available Lots</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($blocks)): ?>
                <?php foreach ($blocks as $block_no): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($block_no); ?></td>
                        <td>
                            <?php
                            if (isset($block_lots[$block_no])) {
                                echo $block_lots[$block_no]['min'] . '-' . $block_lots[$block_no]['max'];
                            } else {
                                echo "No lots available";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit_block?block_no=<?php echo urlencode($block_no); ?>" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No blocks found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination buttons -->
    <div class="text-center">H
        <?php if ($page > 1): ?>
            <a href="block?page=<?php echo $page - 1; ?>" class="btn btn-secondary mr-2">&laquo; Previous</a>
        <?php endif; ?>
        
        <?php if ($total_records > $start + $limit): ?>
            <a href="block?page=<?php echo $page + 1; ?>" class="btn btn-secondary">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
