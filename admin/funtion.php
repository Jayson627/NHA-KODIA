<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

// Helper function to send reset email
function sendResetEmail($email, $reset_code) {
    global $mail;
    $mail->SetFrom("alcantarajayson118@gmail.com");
    $mail->AddAddress($email);
    $mail->Subject = "Reset Password OTP";
    $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>" . 
                  "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";

    return $mail->send();
}

// Function to show SweetAlert
function showSweetAlert($message, $type, $redirect) {
    echo "<script type='text/javascript'>
        Swal.fire({
            icon: '$type',
            title: '$message'
        }).then(function() {
            window.location = '$redirect';
        });
    </script>";
}

// Handle forgotten password (generate OTP)
if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    // Query the database to check if the email exists
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Email exists, generate OTP and send the reset email
        $reset_code = random_int(100000, 999999);
        
        // Direct SQL query to update the reset code
        $update_sql = "UPDATE `users` SET `code` = '$reset_code' WHERE email = '$email'";
        
        if ($conn->query($update_sql) === TRUE) {
            if (sendResetEmail($email, $reset_code)) {
                showSweetAlert("A reset link has been sent to your email.", "success", "../admin/forgot_password");
            } else {
                showSweetAlert("Mailer Error: " . $mail->ErrorInfo, "error", "../admin/forgot_password");
            }
            exit();
        } else {
            showSweetAlert("Failed to update the reset code. Please try again.", "error", "../admin/forgot_password");
            exit();
        }
    } else {
        // If the email does not exist in the database
        showSweetAlert("No user found with this email. Please try again.", "error", "../admin/forgot_password");
        exit();
    }
}

// Handle new password submission (validate OTP and reset password)
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Direct SQL query to get the code from the database
    $sql = "SELECT `code` FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $get_code = $row['code'];

        // Validate OTP
        if ($get_code && $otp === $get_code) {
            $reset = random_int(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);

            // Direct SQL query to update the password and reset code
            $update_sql = "UPDATE `users` SET `password` = '$hashed_password', `code` = '$reset' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                showSweetAlert("Your password has been reset successfully.", "success", "../admin/forgot_password");
                exit();
            }
        } else {
            showSweetAlert("Invalid OTP. Please try again.", "error", "../admin/reset_password");
            exit();
        }
    } else {
        showSweetAlert("No user found with this email. Please try again.", "error", "../admin/reset_password");
        exit();
    }
}
?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

