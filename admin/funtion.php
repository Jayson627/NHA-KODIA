<?php
session_start();
require_once("mailer");
require_once('../admin/connection');
require_once("../initialize");

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
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    
    // Query the database to check if the email exists
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Email exists, generate OTP and send the reset email
        $reset_code = random_int(100000, 999999);
        
        // Direct SQL query to update the reset code
        $update_stmt = $conn->prepare("UPDATE `users` SET `code` = ? WHERE email = ?");
        $update_stmt->bind_param("is", $reset_code, $email);
        
        if ($update_stmt->execute()) {
            if (sendResetEmail($email, $reset_code)) {
                $_SESSION["notify"] = "A reset link has been sent to your email.";
            } else {
                $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
            }
            header("Location: ../admin/forgot_password");
            exit();
        } else {
            $_SESSION["notify"] = "Failed to update the reset code. Please try again.";
            header("Location: ../admin/forgot_password");
            exit();
        }
    } else {
        // If the email does not exist in the database
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("Location: ../admin/forgot_password");
        exit();
    }
}

// Handle new password submission (validate OTP and reset password)
if (isset($_POST["btn-new-password"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Direct SQL query to get the code from the database
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $get_code = $row['code'];

        // Validate OTP
        if ($get_code && $otp === $get_code) {
            $reset_code = random_int(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);

            // Direct SQL query to update the password and reset code
            $update_stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $update_stmt->bind_param("sis", $hashed_password, $reset_code, $email);

            if ($update_stmt->execute()) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
                header("Location: ../admin/forgot_password");
                exit();
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("Location: ../admin/reset_password");
            exit();
        }
    } else {
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("Location: ../admin/reset_password");
        exit();
    }
}
?>
