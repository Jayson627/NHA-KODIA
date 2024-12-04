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
        $otp_expiry = time() + 60; // Set expiration time to 1 minute from now

        // Store OTP and expiry time in the session
        $_SESSION['otp'] = $reset_code;
        $_SESSION['otp_expiry'] = $otp_expiry;
        $_SESSION['email'] = $email;

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

    // Check if session OTP and expiry exist
    if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiry']) && isset($_SESSION['email'])) {
        $stored_otp = $_SESSION['otp'];
        $otp_expiry = $_SESSION['otp_expiry'];

        // Validate OTP and check expiration
        if ($otp === $stored_otp) {
            // Check if the OTP has expired
            if (time() <= $otp_expiry) {
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
                // OTP has expired
                $_SESSION["notify"] = "Your OTP has expired. Please request a new one.";
                header("location: ../admin/reset_password?reset=true&email=$email");
                exit();
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("location: ../admin/reset_password?reset=true&email=$email");
            exit();
        }
    } else {
        $_SESSION["notify"] = "No OTP found or OTP expired. Please request a new OTP.";
        header("location: ../admin/reset_password?reset=true&email=$email");
        exit();
    }
}
?>
