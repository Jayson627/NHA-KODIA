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
                      "Click the link to reset password: https://nha-kodia.com/admin/reset_password?reset&email=$email";
        
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
if (isset($_POST["btn-new-password"])) {
    // New Password Reset Handler
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the OTP from the database
        $res = $result->fetch_assoc();
        $stored_otp = $res["code"]; // OTP from the database

        // Debugging: print both OTP values to compare
        error_log("Entered OTP: " . $otp);
        error_log("Stored OTP: " . $stored_otp);

        // Compare the OTP entered by the user with the stored OTP
        if (trim($otp) === trim($stored_otp)) {
            // OTP is correct, update the password
            $new_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
            $reset_code = random_int(100000, 999999); // New reset code for security

            // Update the user's password and reset code in the database
            $update_stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $update_stmt->bind_param("sis", $new_password, $reset_code, $email);
            
            if ($update_stmt->execute()) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
                // Redirect to login page
                header("Location: ../admin/login.php");
                exit();
            } else {
                $_SESSION["notify"] = "Failed to update the password. Please try again.";
                header("Location: ../admin/reset_password.php?email=$email");
                exit();
            }
        } else {
            // OTP is incorrect
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("Location: ../admin/reset_password.php?email=$email");
            exit();
        }
    } else {
        // Email not found
        $_SESSION["notify"] = "Email not found.";
        header("Location: ../admin/forgot_password.php");
        exit();
    }
}
?>