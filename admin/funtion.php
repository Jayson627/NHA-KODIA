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
if (isset($_POST["btn-resetpass"])) {
    // Get the email, new password, and OTP code from the form
    $email = $_POST["email"];
    $new_password = $_POST["newPassword"];
    $confirm_password = $_POST["confirmPassword"];
    $otp = $_POST["otp"];  // Assuming you are also passing OTP to verify the reset

    // Check if the new password and confirm password match
    if ($new_password !== $confirm_password) {
        $_SESSION["notify"] = "password_mismatch";
        header("location: ../admin/reset_password.php?email=" . $email);
        exit;
    }

    // Validate OTP
    $sql = "SELECT `code` FROM `users` WHERE email=?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $stored_otp);
        
        if (mysqli_stmt_fetch($stmt)) {
            // Check if the OTP is correct
            if ($otp === $stored_otp) {
                // Hash the new password before storing it
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the password in the database and clear the OTP
                $sql = "UPDATE `users` SET `password` = ?, `code` = NULL WHERE email = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION["notify"] = "password_reset_success";
                        header("location: ../admin/login.php");  // Redirect to login page after successful reset
                        exit;
                    } else {
                        $_SESSION["notify"] = "reset_failed";
                        header("location: ../admin/reset_password.php?email=" . $email);
                        exit;
                    }
                }
            } else {
                $_SESSION["notify"] = "invalid_otp";
                header("location: ../admin/reset_password.php?email=" . $email);
                exit;
            }
        } else {
            $_SESSION["notify"] = "invalid_email";
            header("location: ../admin/reset_password.php?email=" . $email);
            exit;
        }
    }
}
?>