<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hcaptcha_response = $_POST['h-captcha-response'];
    $secret_key = 'f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88';

    // Verify the hCaptcha response
    $verify_url = 'https://hcaptcha.com/siteverify';
    $data = [
        'secret' => $secret_key,
        'response' => $hcaptcha_response
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($verify_url, false, $context);
    $response_data = json_decode($result);

    if ($response_data->success) {
        // hCaptcha was successfully solved
        echo "hCaptcha verification successful.";
        // Proceed with form processing
    } else {
        // hCaptcha verification failed
        echo "hCaptcha verification failed. Please try again.";
    }
}
?>
