<?php
// DO NOT TOUCH THIS SECTION ~ 
//These must be at the top of your script, not inside a function
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); 

$mail->CharSet="UTF-8";
$mail->Host = "smtp.gmail.com";
$mail->SMTPDebug = 1; 
$mail->Port = 465 ; //465 or 587

$mail->SMTPSecure = 'ssl';  
$mail->SMTPAuth = true; 
$mail->IsHTML(true);

$mail->Username = "alcantarajayson118@gmail.com";
$mail->Password = "xbdldpzpvsdhicxd";


?>