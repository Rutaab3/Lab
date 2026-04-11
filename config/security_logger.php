<?php
/**
 * Security Logger Helper
 * Logs security events to the security_logs table
 */

/**
 * Log a security event
 * 
 * @param mysqli $conn Database connection
 * @param string $eventType Type of event (failed_login, password_reset, admin_action, suspicious, etc.)
 * @param int|null $userId User ID if applicable
 * @param string|null $username Username if applicable
 * @param string|null $details Additional details about the event
 */
function log_security_event($conn, $eventType, $userId = null, $username = null, $details = null) {
    // Get IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    
    // If behind proxy, try to get real IP
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    
    // Insert log entry
    $stmt = $conn->prepare("INSERT INTO security_logs (event_type, user_id, username, ip_address, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $eventType, $userId, $username, $ipAddress, $details);
    $stmt->execute();
    $stmt->close();
}
