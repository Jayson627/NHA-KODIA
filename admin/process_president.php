<?php
session_start();
include_once('connection.php'); 

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Changed from 'name' to 'email'
    $password = $_POST['password'];

    // Prepare and execute SQL query to fetch user by email
    $stmt = $conn->prepare("SELECT name, password FROM officers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password against the hashed password in the database
        if (password_verify($password, $row['password'])) { // Assuming 'password' is the field in your DB
            // Login successful
            $_SESSION['president_name'] = $row['name']; // Store name in session
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome, " . htmlspecialchars($row['name']) . "!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'dashboard.php';
                        }
                    });
                };
            </script>";
        } else {
            // Incorrect password
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Invalid email or password. Please try again.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'president.php';
                        }
                    });
                };
            </script>";
        }
    } else {
        // Email not found
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: 'Invalid email or password. Please try again.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'president.php';
                    }
                });
            };
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Login...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
</head>
<body>
</body>
</html>
