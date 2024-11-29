<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

if (isset($_POST["btn-forgotpass"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }

    $reset_code = random_int(100000, 999999);

    $stmt = $conn->prepare("UPDATE `users` SET `code`=? WHERE email=?");
    $stmt->bind_param("is", $reset_code, $email);
    $query = $stmt->execute();

    if ($query) {
        // Set Params 
        $mail->setFrom("alcantarajayson118@gmail.com");
        $mail->addAddress("$email");
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: $reset_code<br/>".
                      "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";

        if(!$mail->send()) {
            $_SESSION["notify"] = "failed";
        } else {
            $_SESSION["notify"] = "success";
        }

        header("location: ../admin/forgot_password");
    } else {
        $_SESSION["notify"] = "failed";
        header("location: ../admin/forgot_password");
    }
}

if (isset($_POST["btn-new-password"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $otp = filter_var($_POST["otp"], FILTER_SANITIZE_NUMBER_INT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !ctype_digit($otp)) {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }

    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($get_code);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && $otp === $get_code) {
        $new_code = random_int(100000, 999999);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE `users` SET `password`=?, `code`=? WHERE email=?");
        $stmt->bind_param("sis", $hashed_password, $new_code, $email);
        $query = $stmt->execute();

        $_SESSION["notify"] = "success";
        header("location: ../admin/forgot_password");
    } else {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
    }
}
?>
