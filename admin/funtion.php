<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function send_otp_email($email, $reset_code) {
    global $mail;

    $mail->SetFrom("alcantarajayson118@gmail.com");
    $mail->AddAddress($email);
    $mail->Subject = "Reset Password OTP";
    $mail->Body = "Use this OTP Code to reset your password: ".$reset_code."<br/>".
                  "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";

    if(!$mail->Send()) {
        return false;
    }
    return true;
}

// Forget password process
if (isset($_POST["btn-forgotpass"])) {
    $email = sanitize_input($_POST["email"]);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["notify"] = "invalid_email";
        header("location: ../admin/forgot_password");
        exit();
    }

    // Generate a random OTP
    $reset_code = random_int(100000, 999999);

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("UPDATE `users` SET `code` = ? WHERE `email` = ?");
    $stmt->bind_param('is', $reset_code, $email);

    if ($stmt->execute()) {
        // Send OTP via email
        if (!send_otp_email($email, $reset_code)) {
            $_SESSION["notify"] = "mailer_error";
            header("location: ../admin/forgot_password");
            exit();
        }

        $_SESSION["notify"] = "success";
        header("location: ../admin/forgot_password");
    } else {
        $_SESSION["notify"] = "failed";
        header("location: ../admin/forgot_password");
    }
}

// Reset password process
if (isset($_POST["btn-new-password"])) {
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["password"]);
    $otp = sanitize_input($_POST["otp"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password) || empty($otp)) {
        $_SESSION["notify"] = "invalid_input";
        header("location: ../admin/forgot_password");
        exit();
    }

    // Check if the OTP is valid
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE `email` = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($get_code);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && $otp === $get_code) {
        // Generate a new reset code and hash the new password
        $new_reset_code = random_int(100000, 999999);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Update the password and reset code in the database
        $stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE `email` = ?");
        $stmt->bind_param('sis', $hashed_password, $new_reset_code, $email);

        if ($stmt->execute()) {
            $_SESSION["notify"] = "password_updated";
            header("location: ../admin/forgot_password");
        } else {
            $_SESSION["notify"] = "update_failed";
            header("location: ../admin/forgot_password");
        }
    } else {
        $_SESSION["notify"] = "invalid_otp";
        header("location: ../admin/forgot_password");
    }
}
?>
