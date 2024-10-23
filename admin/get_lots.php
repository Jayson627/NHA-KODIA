<?php
include_once('connection.php');

if (isset($_POST['block_no'])) {
    $block_no = $_POST['block_no'];
    
    // Fetch already taken lots for the given block
    $takenLots = [];
    $result = $conn->query("SELECT lot_no FROM student_list WHERE block_no = '$block_no'");
    while ($row = $result->fetch_assoc()) {
        $takenLots[] = $row['lot_no'];
    }
    
    // Fetch all lots for the given block
    $lots = [];
    $lot_result = $conn->query("SELECT lot_number FROM lot_numbers WHERE block_no = '$block_no'");
    while ($row = $lot_result->fetch_assoc()) {
        if (!in_array($row['lot_number'], $takenLots)) {
            $lots[] = $row['lot_number'];
        }
    }
    
    echo json_encode(['status' => 'success', 'lots' => $lots]);
    exit;
}
?>
