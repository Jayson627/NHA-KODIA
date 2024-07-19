<?php
// manage_children.php

// Assuming you have a database connection established ($conn)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['studentId'];
    $childName = $_POST['childName'];
    $childGender = $_POST['childGender'];
    $childDOB = $_POST['childDOB'];

    // Perform data validation if necessary

    // Insert data into the database
    $insertQuery = "INSERT INTO children (student_id, name, gender, dob) 
                    VALUES ('$studentId', '$childName', '$childGender', '$childDOB')";
    
    if ($conn->query($insertQuery) === TRUE) {
        $response = ['status' => 'success', 'message' => 'Child added successfully'];
        echo json_encode($response);
    } else {
        $response = ['status' => 'error', 'message' => 'Error adding child: ' . $conn->error];
        echo json_encode($response);
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method'];
    echo json_encode($response);
}
?>
