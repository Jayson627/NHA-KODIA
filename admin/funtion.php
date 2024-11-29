<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

// CSRF Protection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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

    if ($stmt->execute()) {
        // Set Params 
        $mail->SetFrom("alcantarajayson118@gmail.com");
        $mail->AddAddress($email);
        $mail->Subject = "Reset Password OTP";
        $mail->isHTML(true);
        $mail->Body = "Use this OTP Code to reset your password: " . htmlspecialchars($reset_code) . "<br/>" .
                      "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=" . urlencode($email);

        if (!$mail->Send()) {
            echo "Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
        } else {
            echo "Message has been sent";
        }

        $_SESSION["notify"] = "success";
    } else {
        $_SESSION["notify"] = "failed";
    }
    
    $stmt->close();
    header("location: ../admin/forgot_password");
    exit();
}

// New password
if (isset($_POST["btn-new-password"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }

    $password = $_POST["password"];
    $otp = $_POST["otp"];

    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($get_code);
    $stmt->fetch();
    $stmt->close();

    if ($otp === $get_code) {
        $reset = random_int(100000, 999999);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE `users` SET `password`=?, `code`=? WHERE email=?");
        $stmt->bind_param("sis", $hashed_password, $reset, $email);

        if ($stmt->execute()) {
            $_SESSION["notify"] = "success";
        } else {
            $_SESSION["notify"] = "failed";
        }

        $stmt->close();
        header("location: ../admin/forgot_password");
        exit();
    } else {
        $_SESSION["notify"] = "invalid";
        header("location: ../admin/forgot_password");
        exit();
    }
}
?>
