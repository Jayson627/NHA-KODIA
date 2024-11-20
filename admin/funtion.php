<?php
session_start();
include "mailer.php";
include "includes/conn.php";
if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    $allowed_gmail = "villaceranjerson55@gmail.com";
    if ($email !== $allowed_gmail) {
        // If the email doesn't match, show a message or redirect
        $_SESSION["notify"] = "Email not found! Please contact the administrator to reset a password.";
        header("Location: ../admin/forgot_password.php");
        exit();
    }

    $reset_code = random_int(100000, 999999);
    
    $sql = "UPDATE `users` SET `code`='$reset_code' WHERE email='$email'";
 
    $query = mysqli_query($conn, $sql);
 
    if ($query) {
        
        //Set Params 
        $mail->SetFrom("sscvoting@do-not.reply");
        $mail->AddAddress("$email");
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: ".$reset_code."<br/>".
        "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email"  //pulihan $reset_coede
        ;


        if (!$mail->send()) {
            $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $_SESSION["notify"] = "A reset link has been sent to your email.";
        }

        // Redirect to the forgot password page
        header("Location: ../admin/forgot_password.php");
        exit();
    } else {
        $_SESSION["notify"] = "Failed to update the reset code. Please try again.";
        header("Location: ../admin/forgot_password.php");
        exit();
    }
}
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT `code` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $res = $result->fetch_assoc();
        $get_code = $res["code"];

        if ($otp === $get_code) {
            $reset = random_int(100000, 999999);
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the update statement to avoid SQL injection
            $update_stmt = $conn->prepare("UPDATE `users` SET `password` = ?, `code` = ? WHERE email = ?");
            $update_stmt->bind_param("sis", $password, $reset, $email);
            
            if ($update_stmt->execute()) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
            } else {
                $_SESSION["notify"] = "Failed to update password. Please try again.";
            }
            header("Location: ../admin/fotgot_password.php");
            exit();
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("Location: ../admin/forgot_password.php");
            exit();
        }
    } else {
        $_SESSION["notify"] = "Email not found.";
        header("Location: ../admin/forgot_password.php");
        exit();
    }
}
?>
