<?php
/**
 * Action Logger Helper
 * Logs user actions to the logs table (Business Logic / Activity Logs)
 */

// Ensure logs table exists
$createLogsTable = "CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    username VARCHAR(100),
    role VARCHAR(50),
    action VARCHAR(255),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// We assume $conn is available when this is included, or passed to the function. 
// But we can't run query on include if $conn isn't global. Best to leave table creation to a setup script or check inside function?
// For now, let's just define the function.

/**
 * Log a user action
 * 
 * @param mysqli $conn Database connection
 * @param string $action Short description of the action (e.g. "Product Added")
 * @param array|string $details Array of details or string description
 * @param int $notify Whether to notify admins immediately (0=no, 1=yes)
 */
function log_action($conn, $action, $details = [], $notify = 0) {
    // Check session for user info
    $userId = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $username = $_SESSION['username'] ?? 'System';
    $role = $_SESSION['role'] ?? 'guest';

    // Format details
    if (is_array($details)) {
        $details = json_encode($details);
    }

    // Insert log
    // user_id can be NULL if the logs table allows it.
    
    $stmt = $conn->prepare("INSERT INTO logs (user_id, username, role, action, details, notify) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("issssi", $userId, $username, $role, $action, $details, $notify);
        $stmt->execute();
        $stmt->close();

        // INSTANT NOTIFICATION TRIGGER
        // We run the emailer in the background so the user doesn't wait for SMTP
        $scriptPath = dirname(__DIR__) . '/maintenance/email_unsent_logs.php';
        // Windows specific background execution
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B php \"$scriptPath\" > NUL 2>&1", "r"));
        } else {
            // Linux/Unix background execution
            exec("php \"$scriptPath\" > /dev/null 2>&1 &");
        }
    }
}
