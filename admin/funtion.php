<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");


function sendResetEmail($email, $reset_code, $timestamp) {
    global $mail;
    $mail->SetFrom("alcantarajayson118@gmail.com");
    $mail->AddAddress($email);
    $mail->Subject = "Reset Password OTP";
    $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>" . 
                  "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email&timestamp=$timestamp";

    return $mail->send();
}


if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    // Query the database to check if the email exists
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        
        $reset_code = random_int(100000, 999999);
        $timestamp = time(); 
        
     
        $_SESSION['reset_code'] = $reset_code;
        $_SESSION['reset_timestamp'] = $timestamp;
        $_SESSION['reset_email'] = $email;

        if (sendResetEmail($email, $reset_code, $timestamp)) {
            $_SESSION["notify"] = "A reset link has been sent to your email.";
        } else {
            $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
        }
        header("location: ../admin/forgot_password");
        exit();
    } else {
   
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("location: ../admin/forgot_password");
        exit();
    }
}


if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];
    $timestamp = $_POST["timestamp"];
    
 
    if (isset($_SESSION['reset_code'], $_SESSION['reset_timestamp'], $_SESSION['reset_email']) &&
        $_SESSION['reset_email'] === $email &&
        $_SESSION['reset_code'] == $otp &&
        (time() - $_SESSION['reset_timestamp']) <= 120) {
        
        $hashed_password = password_hash($password, PASSWORD_ARGON2I);
        
     
        $update_sql = "UPDATE `users` SET `password` = '$hashed_password' WHERE email = '$email'";
        
        if ($conn->query($update_sql) === TRUE) {
       
            unset($_SESSION['reset_code'], $_SESSION['reset_timestamp'], $_SESSION['reset_email']);
            
            $_SESSION["notify"] = "Your password has been reset successfully.";
            header("location: ../admin/reset_password?reset=true&email=$email&success=true");
            exit();
        } else {
            $_SESSION["notify"] = "Failed to update the password. Please try again.";
            header("location: ../admin/reset_password?reset=true&email=$email");
            exit();
        }
    } else {
        $_SESSION["notify"] = "Invalid or expired OTP. Please try again.";
        header("location: ../admin/reset_password?reset=true&email=$email");
        exit();
    }
}
?>
