<?php
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

if (isset($_POST["btn-forgotpass"])) {

    $email = $_POST["email"];
    $reset_code = random_int(100000, 999999);
    
    $sql = "UPDATE `users` SET `code`='$reset_code' WHERE email='$email'";
 
    $query = mysqli_query($conn, $sql);
 
 
    if ($query) {
        
        //Set Params 
        $mail->SetFrom("alcantarajayson118@gmail.com");
        $mail->AddAddress("$email");
        $mail->Subject = "Reset Password OTP";
        $mail->Body = "Use this OTP Code to reset your password: ".$reset_code."<br/>".
        "Click the link to reset password: http://nha-kodia.com/sis/admin/reset_password.php?reset&email=$reset_code" 
        ;


        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }

        //OTP has been sent please check your email
        $_SESSION["notify"] = "success";
 
        header("location: ../admin/forgot_password.php");
 
    }else {
 
        $_SESSION["notify"] = "failed";
        
 
        header("location: ../admin/forgot_password.php");
 
 
    }
 
 }
 // new password 
 if (isset($_POST["btn-new-password"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];



    $sql = "SELECT `code` FROM `users` WHERE email='$email'";

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {

        while ($res = mysqli_fetch_assoc($query)) {

            $get_code = $res["code"];

        }

        if ($otp === $get_code) {

           

            $reset = random_int(100000, 999999);
             $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE `users` SET `password`='$password', `code`=$reset  WHERE email='$email'";

            $query = mysqli_query($conn, $sql);

            $_SESSION["notify"] = "success";

            header("location: ../admin/forgot_password.php");
 
 
        }else {

            $_SESSION["notify"] = "invalid";

            header("location: ../admin/forgot_password.php");
 

        }

    }else {

            $_SESSION["notify"] = "invalid";

            header("location: ../admin/forgot_password.php");
 
 

    }
}


?>