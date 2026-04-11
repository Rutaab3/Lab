<?php
include '../config/db.php';
include '../config/action_logger.php';

// Start session if not existing (needed for action_logger)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mock some session data
$_SESSION['user_id'] = null;
$_SESSION['username'] = 'TestSystem';
$_SESSION['role'] = 'admin';

echo "Logging a test action...<br>";

// Log an action with notify=0 (default)
log_action($conn, "Test Notification Trigger", ["status" => "testing flow", "version" => "2.0"]);

echo "Action logged. Now run email_unsent_logs.php manually to see if it picks it up.";
?>
