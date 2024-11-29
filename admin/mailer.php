<?php
// DO NOT TOUCH THIS SECTION ~ 
// These must be at the top of your script, not inside a function
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);  

$mail->CharSet = "UTF-8";
$mail->IsSMTP();  
$mail->Host = "smtp.gmail.com"; 
$mail->Port = 587;  
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
$mail->SMTPAuth = true; 

$mail->Username = "alcantarajayson118@gmail.com";  
$mail->Password = "xbdldpzpvsdhicxd"; 


$mail->IsHTML(true);