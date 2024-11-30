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

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Email exists, generate OTP and send the reset email
        $reset_code = random_int(100000, 999999);

        // Use prepared statements to update the reset code
        $stmt = $conn->prepare("UPDATE `users` SET `code` = ? WHERE email = ?");
        $stmt->bind_param("is", $reset_code, $email);

        if ($stmt->execute() === TRUE) {
            if (sendResetEmail($email, $reset_code)) {
                $_SESSION["notify"] = "A reset link has been sent to your email.";
            } else {
                $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $_SESSION["notify"] = "Failed to update the reset code. Please try again.";
        }
    } else {
        // If the email does not exist in the database
        $_SESSION["notify"] = "No user found with this email. Please try again.";
    }
    header("Location: ../admin/forgot_password.php");
    exit();
}

// Handle new password submission (validate OTP and reset password)
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Use prepared statements to get the code from the database
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $get_code = $row['code'];

        // Validate OTP
        if ($get_code && $otp == $get_code) {
            $reset = random_int(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);

            // Use prepared statements to update the password and reset code
            $stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $stmt->bind_param("sis", $hashed_password, $reset, $email);

            if ($stmt->execute() === TRUE) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
            } else {
                $_SESSION["notify"] = "Failed to reset the password. Please try again.";
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
        }
    } else {
        $_SESSION["notify"] = "No user found with this email. Please try again.";
    }
    header("Location: ../admin/reset_password.php");
    exit();
}
?>
