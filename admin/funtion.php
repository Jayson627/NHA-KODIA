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
                $_SESSION["notify"] = "success";
                $_SESSION["message"] = "Password reset successful.";
                header("location: ../admin/reset_password");
                exit();
            } else {
                $_SESSION["notify"] = "error";
                $_SESSION["message"] = "Failed to update password.";
                header("location: ../admin/reset_password");
                exit();
            }
        } else {
            $_SESSION["notify"] = "error";
            $_SESSION["message"] = "Invalid OTP.";
            header("location: ../admin/reset_password");
            exit();
        }
    } else {
        $_SESSION["notify"] = "error";
        $_SESSION["message"] = "Email does not exist.";
        header("location: ../admin/reset_password");
        exit();
    }
}
?>
