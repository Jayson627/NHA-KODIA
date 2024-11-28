
<?php
// Use PHPMailer's Autoloader
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP();

// Load environment variables
$mail->CharSet = "UTF-8";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // Use 465 for SSL
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->IsHTML(true);

// Fetch credentials from environment variables
$mail->Username = getenv('alcantarajayson118@gmail.com'); // Set this in your server's environment variables
$mail->Password = getenv('xbdldpzpvsdhicxd'); // Set this in your server's environment variables

// Disable debug in production
$mail->SMTPDebug = 0;

?>
