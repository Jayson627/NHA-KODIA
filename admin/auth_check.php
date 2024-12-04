<?php
session_start();

function getToken($email) {
    $tokenFile = "tokens/{$email}.token";
    return file_exists($tokenFile) ? file_get_contents($tokenFile) : null;
}

function isAuthenticated() {
    if (isset($_SESSION['email']) && isset($_SESSION['token'])) {
        $storedToken = getToken($_SESSION['email']);
        return $_SESSION['token'] === $storedToken;
    }
    return false;
}

if (!isAuthenticated()) {
    header('Location: login.php');
    exit();
}
?>
