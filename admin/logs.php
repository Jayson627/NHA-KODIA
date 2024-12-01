<?php

session_start();


function logAction($message) {
  
    $userIP = $_SERVER['REMOTE_ADDR'];

  
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $logFile = 'app.log';  // Path to your log file
    $currentDate = date('Y-m-d H:i:s');
    $logMessage = "[$currentDate] - IP: $userIP - $message\n";
    
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}


if (isset($_POST['create_account'])) {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

   

    logAction("Account created for email: $email");

   
    echo 'Account created successfully.';
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $isValid = false;
    logACtion ("Created login for email: $email");

    echo 'login succesfully.';

} else {
    logAction("Failed login attempt for email: $email");

    echo 'Invalid email or password.';

}

?>
