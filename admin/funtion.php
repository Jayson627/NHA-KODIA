<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

if (isset($_POST["btn-forgotpass"])) {
    // Sanitize and validate email
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }

    $reset_code = random_int(100000, 999999);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE `users` SET `code` = ? WHERE email = ?");
    $stmt->bind_param("is", $reset_code, $email);
    $query = $stmt->execute();

    if ($query) {
        // Set mail parameters
        $mail->SetFrom("alcantarajayson118@gmail.com");
        $mail->AddAddress("$email");
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>" .
            "Click the link to reset password: https://nha-kodia.com/admin/reset_password?reset&email=$email";

        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }

        // OTP has been sent, please check your email
        $_SESSION["notify"] = "success";
        header("location: ../admin/forgot_password");
    } else {
        $_SESSION["notify"] = "failed";
        header("location: ../admin/forgot_password");
    }

    $stmt->close();
}

// New password
if (isset($_POST["btn-new-password"])) {
    // Sanitize inputs
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    if (!$email) {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $res = $result->fetch_assoc();
        $get_code = $res["code"];

        if ($otp === $get_code) {
            $reset = random_int(100000, 999999);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $stmt->bind_param("sis", $hashed_password, $reset, $email);
            $query = $stmt->execute();

            $_SESSION["notify"] = "success";
            header("location: ../admin/forgot_password");
        } else {
            $_SESSION["notify"] = "invalid";
            header("location: ../admin/forgot_password");
        }
    } else {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
    }

    $stmt->close();
}
?>
