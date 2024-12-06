<?php
ob_start();
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

header("X-Content-Type-Options: nosniff");

header("X-Frame-Options: DENY");

header("X-XSS-Protection: 1; mode=block");

header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

header("Referrer-Policy: no-referrer");

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

header('Pragma: no-cache');

header('Expires: 0');

header("Permissions-Policy: geolocation=(self), microphone=()");

header("X-Permitted-Cross-Domain-Policies: none");

header('Content-Type: text/html; charset=utf-8');

ini_set('session.cookie_secure', '1'); // Use HTTPS
ini_set('session.cookie_httponly', '1'); // HttpOnly
ini_set('session.cookie_samesite', 'Strict'); // SameSite policyy
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');
session_start();

require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/classes/DBConnection.php';
require_once __DIR__ . '/classes/SystemSettings.php';

$db = new DBConnection();
$conn = $db->conn;

function redirect($url=''){
    if(!empty($url)) {
        echo '<script>location.href="'. base_url . $url . '"</script>';
    }
}

function validate_image($file){
    if(!empty($file)){
        $ex = explode('?', $file);
        $file = $ex[0];
        $param = isset($ex[1]) ? '?' . $ex[1] : '';
        if(is_file(base_app . $file)){
            return base_url . $file . $param;
        } else {
            return base_url . 'dist/img/no-image-available.png';
        }
    } else {
        return base_url . 'dist/img/no-image-available.png';
    }
}

function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    return false;
}

ob_end_flush();
?>
