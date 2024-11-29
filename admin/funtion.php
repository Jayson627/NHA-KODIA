<?php
session_start();
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');
require_once('PHPMailer/src/Exception.php');
require_once('../admin/connection.php');

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST["btn-forgotpass"])) {
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $_SESSION["notify"] = "invalid";
        header("location: forgot_password");
        exit();
    }

    $reset_code = random_int(100000, 999999);

    $stmt = $conn->prepare("UPDATE `users` SET `code`=? WHERE email=?");
    $stmt->bind_param('is', $reset_code, $email);
    $query = $stmt->execute();

    if ($query) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPDebug = 0; 
        $mail->Port = 465; 
        $mail->SMTPSecure = 'ssl';  
        $mail->SMTPAuth = true; 
        $mail->IsHTML(true);
        $mail->Username = "alcantarajayson118@gmail.com";
        $mail->Password = "xbdldpzpvsdhicxd";
        $mail->SetFrom("alcantarajayson118@gmail.com");
        $mail->AddAddress($email);
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: $reset_code<br/>" .
                      "Click the link to reset password: http://nha-kodia.com/admin/reset_password.php?reset&email=$email";

        if (!$mail->Send()) {
            $_SESSION["notify"] = "failed";
        } else {
            $_SESSION["notify"] = "success";
        }
    } else {
        $_SESSION["notify"] = "failed";
    }

    header("location: forgot_password");
    exit();
}
?>
