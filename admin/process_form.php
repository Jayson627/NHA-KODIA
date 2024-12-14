<?php
session_start();

include_once('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        $fullname = $conn->real_escape_string($_POST['fullname']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $lot_no = $conn->real_escape_string($_POST['lot_no']);
        $house_no = $conn->real_escape_string($_POST['house_no']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
        $role = $conn->real_escape_string($_POST['role']);

        // Check if the email already exists in the database
        $email_check_sql = "SELECT * FROM residents WHERE email = '$email'";
        $email_check_result = $conn->query($email_check_sql);

        if ($email_check_result->num_rows > 0) {
            // If email exists, show error message
            header("Location: resedent?message=" . urlencode("Error: Email already in use.") . "&type=error");
        } else {
            // If email does not exist, proceed with registration
            $sql = "INSERT INTO residents (fullname, dob, lot_no, house_no, email, password, role)
                    VALUES ('$fullname', '$dob', '$lot_no', '$house_no', '$email', '$password', '$role')";

            if ($conn->query($sql) === TRUE) {
                // Redirect with success message
                header("Location: resedent?message=" . urlencode("Registration successful! Wait for the approval of admin") . "&type=success");
            } else {
                // Redirect with error message in case of query failure
                header("Location: resedent?message=" . urlencode("Error: " . $conn->error) . "&type=error");
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        $email = $conn->real_escape_string($_POST['login_email']);
        $password = $conn->real_escape_string($_POST['login_password']);

        $sql = "SELECT * FROM residents WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] == 'resident') {
                    header("Location: people?message=" . urlencode("Login successful!") . "&type=success");
                } elseif ($row['role'] == 'president') {
                    header("Location: presedent?message=" . urlencode("Login successful!") . "&type=success");
                }
            } else {
                header("Location: resedent?message=" . urlencode("Invalid password") . "&type=error");
            }
        } else {
            header("Location: resedent?message=" . urlencode("No user found with this email") . "&type=error");
        }
    }
}

$conn->close();
?>
