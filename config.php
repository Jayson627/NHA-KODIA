<?php
ob_start();
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');
session_start();

require_once __DIR__ . '/initialize';
require_once __DIR__ . '/classes/DBConnection';
require_once __DIR__ . '/classes/SystemSettings';

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
