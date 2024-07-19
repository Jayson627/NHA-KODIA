<?php 
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https://nha-kodia.com/"; 
else
    $link = "http"; 
$link .= "://"; 
$link .= $_SERVER['127.0.0.1:3306']; 
$link .= $_SERVER['https://nha-kodia.com/'];
if(!strpos($link, 'login.php') && !strpos($link, 'register.php') && (!isset($_SESSION['userdata']) || (isset($_SESSION['userdata']['login_type']) && $_SESSION['userdata']['login_type'] != 2)) ){
	redirect('login.php');
}
if(strpos($link, 'login.php') && isset($_SESSION['userdata']['login_type']) && $_SESSION['userdata']['login_type'] == 2){
	redirect('index.php');
}
