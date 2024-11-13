<?php
session_start();

// Your Google reCAPTCHA secret key
$secretKey = '6LeuGX0qAAAAABASLS-ICpRhebIUTbtaxA6d4r3U'; 
// Function to verify the CAPTCHA response with Google
function verifyCaptcha($response) {
    global $secretKey;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $response
    ];
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify);
    return $captchaSuccess->success;
}

// Account Creation Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_account'])) {
    // Verify CAPTCHA
    $captchaResponse = $_POST['g-recaptcha-response'];
    if (empty($captchaResponse) || !verifyCaptcha($captchaResponse)) {
        $_SESSION['message'] = "CAPTCHA verification failed. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Add your logic for account creation here (e.g., validate form fields, insert into DB, etc.)
    // E.g.:
    // $fullname = $_POST['fullname'];
    // $dob = $_POST['dob'];
    // $email = $_POST['email'];
    // $username = $_POST['username'];
    // $password = $_POST['password'];
    // etc.
    $_SESSION['message'] = "Account created successfully!";
    header("Location: login.php");
    exit();
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Verify CAPTCHA
    $captchaResponse = $_POST['g-recaptcha-response'];
    if (empty($captchaResponse) || !verifyCaptcha($captchaResponse)) {
        $_SESSION['message'] = "CAPTCHA verification failed. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Add your login logic here (validate user credentials, etc.)
    // E.g.:
    // $email = $_POST['email'];
    // $password = $_POST['password'];
    // Check credentials in DB
    $_SESSION['message'] = "Login successful!";
    header("Location: dashboard.php");
    exit();
}
?>
