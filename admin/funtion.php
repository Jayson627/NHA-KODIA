<?php

session_start();
include "mailer.php";
require_once('../admin/connection.php');
require_once("../initialize.php");

// Handle OTP Generation and Email Sending
if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    $allowed_gmail = "alcantarajayson118@gmail.com";
    if ($email !== $allowed_gmail) {
        $_SESSION["notify"] = "Email not found! Please contact the administrator to reset a password.";
        header("Location: ../admin/login.php");
        exit();
    }

    $reset_code = random_int(100000, 999999);
    
    $sql = "UPDATE `users` SET `code`='$reset_code' WHERE email='$email'";
 
    $query = mysqli_query($conn, $sql);
 
    if ($query) {
        // Set email parameters
        $mail->SetFrom("sscvoting@do-not.reply");
        $mail->AddAddress($email);
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>".
                      "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";
        
        if (!$mail->send()) {
            $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $_SESSION["notify"] = "A reset link has been sent to your email.";
        }

        header("Location: ../admin/login.php");
        exit();
    } else {
        $_SESSION["notify"] = "Failed to update the reset code. Please try again.";
        header("Location: ../admin/login.php");
        exit();
    }
}

// Handle Password Reset
// Handle password reset
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Check the OTP against the stored code in the database
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $res = $result->fetch_assoc();
        $get_code = $res["code"];

        if ($otp === $get_code) {
            // If OTP matches, hash the new password
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            $reset_code = random_int(100000, 999999);

            // Update the user's password and reset code in the database
            $update_stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $update_stmt->bind_param("sis", $new_password, $reset_code, $email);

            if ($update_stmt->execute()) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
                header("Location: ../admin/login.php");  // Redirect to login page after success
                exit();
            } else {
                $_SESSION["notify"] = "Failed to update password. Please try again.";
                header("Location: ../admin/about.php");  // Redirect back if failed
                exit();
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("Location: ../admin/block.php");  // Redirect back if OTP doesn't match
            exit();
        }
    } else {
        $_SESSION["notify"] = "Email not found.";
        header("Location: ../admin/forgot_password.php");  // Redirect to the forgot password page
        exit();
    }
}
?>