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

// Handle forgotten password (generate OTP)
if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    // Query the database to check if the email exists
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Email exists, generate OTP and send the reset email
        $reset_code = random_int(100000, 999999);
        
        // Store OTP and generation time in session
        $_SESSION["reset_code"] = $reset_code;
        $_SESSION["reset_code_time"] = time();

        if (sendResetEmail($email, $reset_code)) {
            $_SESSION["notify"] = "A reset link has been sent to your email.";
        } else {
            $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
        }
        header("location: ../admin/forgot_password");
        exit();
    } else {
        // If the email does not exist in the database
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("location: ../admin/forgot_password");
        exit();
    }
}

// Handle new password submission (validate OTP and reset password)
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Get OTP and generation time from session
    $stored_otp = $_SESSION["reset_code"];
    $stored_otp_time = $_SESSION["reset_code_time"];

    // Check if OTP has expired (2 minutes = 120 seconds)
    if (time() - $stored_otp_time > 120) {
        $_SESSION["alert_message"] = "The OTP has expired. Please request a new one.";
        header("location: ../admin/reset_password?reset=true&email=$email");
        exit();
    }

    // Direct SQL query to get the code from the database
    $sql = "SELECT `code` FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $get_code = $row['code'];

        // Validate OTP
        if ($otp === $stored_otp) {
            $reset = random_int(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);

            // Direct SQL query to update the password and reset code
            $update_sql = "UPDATE `users` SET `password` = '$hashed_password', `code` = '$reset' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
                header("location: ../admin/reset_password?reset=true&email=$email&success=true");
                exit();
            } else {
                $_SESSION["notify"] = "Failed to update the password. Please try again.";
                header("location: ../admin/reset_password?reset=true&email=$email");
                exit();
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("location: ../admin/reset_password?reset=true&email=$email");
            exit();
        }
    } else {
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("location: ../admin/reset_password?reset=true&email=$email");
        exit();
    }
}
?>
