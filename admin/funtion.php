<?php
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    $reset_code = random_int(100000, 999999);

    // Using prepared statement to avoid SQL injection
    $sql = "UPDATE `users` SET `code`=? WHERE email=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "is", $reset_code, $email);
        if (mysqli_stmt_execute($stmt)) {
            // Prepare and send OTP email
            $mail->SetFrom("alcantarajayson118@gmail.com");
            $mail->AddAddress("$email");
            $mail->Subject = "Reset Password OTP";
            $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>" .
                          "Click the link to reset password: http://nha-kodia.com/admin/reset_password.php?reset&email=$email";

            if (!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message has been sent";
            }

            $_SESSION["notify"] = "success";
            header("location: ../admin/forgot_password.php");
        } else {
            $_SESSION["notify"] = "failed";
            header("location: ../admin/forgot_password.php");
        }
    }
}

// New password reset
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Validate OTP
    $sql = "SELECT `code` FROM `users` WHERE email=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $get_code);
        if (mysqli_stmt_fetch($stmt)) {
            if ($otp === $get_code) {
                // Hash new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Update the password
                $sql = "UPDATE `users` SET `password` = ?, `code` = NULL WHERE email = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION["notify"] = "success";
                        header("location: ../admin/login.php");
                    }
                }
            } else {
                $_SESSION["notify"] = "invalid";
                header("location: ../admin/block.php");
            }
        } else {
            $_SESSION["notify"] = "invalid";
            header("location: ../admin/lot.php");
        }
    }
}
?>
