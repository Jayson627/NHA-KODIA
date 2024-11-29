<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    $reset_code = random_int(100000, 999999);

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE `users` SET `code`=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $reset_code, $email);  // "i" for integer, "s" for string
    $query = $stmt->execute();

    if ($query) {
        // Set Params for email
        $mail->SetFrom("alcantarajayson118@gmail.com");
        $mail->AddAddress($email);
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: ".$reset_code."<br/>".
            "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";

        if (!$mail->Send()) {
            // Log the error and show a generic message
            error_log("Mailer Error: " . $mail->ErrorInfo);
            $_SESSION["notify"] = "error";
        } else {
            $_SESSION["notify"] = "success";
        }

        header("location: ../admin/forgot_password");
    } else {
        // Log the error in case of failure
        error_log("Error updating reset code for email: $email");
        $_SESSION["notify"] = "failed";
        header("location: ../admin/forgot_password");
    }
}

// New password update logic
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Use prepared statement for SELECT to prevent SQL injection
    $sql = "SELECT `code` FROM `users` WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // "s" for string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $res = $result->fetch_assoc();
        $get_code = $res["code"];

        if ($otp === $get_code) {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $reset = random_int(100000, 999999);

            // Use prepared statement for UPDATE to prevent SQL injection
            $sql = "UPDATE `users` SET `password`=?, `code`=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $hashed_password, $reset, $email);  // "s" for string, "i" for integer
            $stmt->execute();

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
}
?>
