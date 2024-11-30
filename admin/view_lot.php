<?php
// Get the lots number from the URL
$lot_no = isset($_GET['lot_no']) ? $_GET['lot_no'] : null;

if (!$lot_no) {
    echo "No lot number specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Lot</title> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>View Lot</h2>
    <p><strong>Lot No:</strong> <?php echo htmlspecialchars($lot_no); ?></p>
    <a href="lot.php" class="btn btn-secondary">Back</a>
    
</div>
</body>
</html>