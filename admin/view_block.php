<?php
// Get the lots number from the URL
$block_no = isset($_GET['block_no']) ? $_GET['block_no'] : null;

if (!$block_no) {
    echo "No block number specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View block</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>View block</h2>
    <p><strong>block No:</strong> <?php echo htmlspecialchars($block_no); ?></p>
    <a href="block.php" class="btn btn-secondary">Back</a>
    
</div>
</body>
</html>