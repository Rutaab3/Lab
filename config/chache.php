<?php
// Only modify session settings if the session isn't started yet
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = 30 * 24 * 60 * 60; // 30 days
    ini_set('session.gc_maxlifetime', $lifetime);
    
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'secure' => false,   // true if using HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}
?>