<?php
require_once('../config.php');

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => 'Invalid request'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $query->bind_param('ss', $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $response = [
            'status' => 'success',
            'message' => 'Login successful'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Incorrect username or password username or password'
        ];
    }
}

echo json_encode($response);
?>
