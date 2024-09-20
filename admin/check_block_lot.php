<?php
include_once('connection.php');

// Get POST data
$block_number = $_POST['block_number'];
$lot_number = $_POST['lot_number'];

// Prepare SQL query
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM student_list WHERE block = ? AND lot = ?");
$stmt->bind_param("ss", $block_number, $lot_number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if the block and lot number are valid
if ($row['count'] > 0) {
    echo json_encode(['valid' => true]);
} else {
    echo json_encode(['valid' => false]);
}

$stmt->close();
$conn->close();
?>
