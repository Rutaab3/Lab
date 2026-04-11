<?php
/**
 * Email Configuration Diagnostic Tool
 * This script checks why welcome emails are not being sent to new users
 */

include '../config/db.php';
include '../config/settings.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Configuration Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h2 { color: #667eea; margin-top: 30px; }
        .status-box { padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #ccc; }
        .success { background: #d4edda; border-left-color: #28a745; color: #155724; }
        .error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
        .warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; }
        .info { background: #d1ecf1; border-left-color: #17a2b8; color: #0c5460; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #667eea; color: white; }
        table tr:nth-child(even) { background: #f9f9f9; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #5568d3; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📧 Email Configuration Diagnostic</h1>";

// 1. Check if email_logs table exists
echo "<h2>1. Email Logs Table Status</h2>";
$tableCheck = $conn->query("SHOW TABLES LIKE 'email_logs'");
if ($tableCheck->num_rows > 0) {
    echo "<div class='status-box success'>✓ Email logs table exists</div>";
    
    // Get recent email logs
    $logs = $conn->query("SELECT * FROM email_logs ORDER BY sent_at DESC LIMIT 10");
    if ($logs && $logs->num_rows > 0) {
        echo "<p><strong>Recent Email Logs:</strong></p>";
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Error</th>
                    <th>Sent At</th>
                </tr>";
        while ($log = $logs->fetch_assoc()) {
            $statusClass = $log['status'] == 'success' ? 'success' : 'error';
            echo "<tr>
                    <td>{$log['id']}</td>
                    <td>{$log['email_type']}</td>
                    <td>{$log['recipient']}</td>
                    <td><span style='color: " . ($log['status'] == 'success' ? 'green' : 'red') . "'>{$log['status']}</span></td>
                    <td>" . ($log['error_message'] ?? 'N/A') . "</td>
                    <td>{$log['sent_at']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='status-box warning'>⚠ No email logs found - no emails have been sent yet</div>";
    }
} else {
    echo "<div class='status-box error'>✗ Email logs table does not exist</div>";
}

// 2. Check mail configuration
echo "<h2>2. Mail Configuration</h2>";
$mailConfig = get_system_settings('mail');
if (!empty($mailConfig)) {
    echo "<div class='status-box success'>✓ Mail configuration found</div>";
    echo "<table>
            <tr><th>Setting</th><th>Value</th></tr>
            <tr><td>Host</td><td>" . ($mailConfig['host'] ?? '<span style=\"color:red\">NOT SET</span>') . "</td></tr>
            <tr><td>Username</td><td>" . ($mailConfig['username'] ?? '<span style=\"color:red\">NOT SET</span>') . "</td></tr>
            <tr><td>Password</td><td>" . (!empty($mailConfig['password']) ? '••••••••' : '<span style=\"color:red\">NOT SET</span>') . "</td></tr>
            <tr><td>Port</td><td>" . ($mailConfig['port'] ?? '<span style=\"color:red\">NOT SET</span>') . "</td></tr>
            <tr><td>Encryption</td><td>" . ($mailConfig['encryption'] ?? '<span style=\"color:red\">NOT SET</span>') . "</td></tr>
          </table>";
    
    // Check if configuration is complete
    if (empty($mailConfig['host']) || empty($mailConfig['username']) || empty($mailConfig['password'])) {
        echo "<div class='status-box error'>✗ Mail configuration is INCOMPLETE! Missing required SMTP settings.</div>";
        echo "<div class='status-box info'>💡 <strong>Solution:</strong> Configure your SMTP settings in the <code>system_settings</code> table with category='mail'</div>";
    }
} else {
    echo "<div class='status-box error'>✗ No mail configuration found in database</div>";
    echo "<div class='status-box info'>💡 <strong>Solution:</strong> You need to add mail settings to the <code>system_settings</code> table</div>";
}

// 3. Check PHPMailer
echo "<h2>3. PHPMailer Status</h2>";
$phpMailerPath = '../users/PHPMailer/src/PHPMailer.php';
if (file_exists($phpMailerPath)) {
    echo "<div class='status-box success'>✓ PHPMailer library found</div>";
} else {
    echo "<div class='status-box error'>✗ PHPMailer library not found at <code>$phpMailerPath</code></div>";
}

// 4. Check users without greetings
echo "<h2>4. Users Without Welcome Emails</h2>";
$usersWithoutGreetings = $conn->query("SELECT id, username, email, created_at, greetings FROM users WHERE greetings = 0 OR greetings IS NULL ORDER BY created_at DESC LIMIT 10");

if ($usersWithoutGreetings && $usersWithoutGreetings->num_rows > 0) {
    echo "<div class='status-box warning'>⚠ Found {$usersWithoutGreetings->num_rows} user(s) who have NOT received welcome emails</div>";
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Greetings Sent</th>
            </tr>";
    while ($user = $usersWithoutGreetings->fetch_assoc()) {
        echo "<tr>
                <td>{$user['id']}</td>
                <td>{$user['username']}</td>
                <td>{$user['email']}</td>
                <td>{$user['created_at']}</td>
                <td style='color: red;'>" . ($user['greetings'] == 0 ? 'No' : 'NULL') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='status-box success'>✓ All users have received welcome emails</div>";
}

// 5. Check recent registrations
echo "<h2>5. Recent Registrations</h2>";
$recentUsers = $conn->query("SELECT id, username, email, created_at, greetings FROM users ORDER BY created_at DESC LIMIT 5");
if ($recentUsers && $recentUsers->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Welcome Email Sent</th>
            </tr>";
    while ($user = $recentUsers->fetch_assoc()) {
        $greetingStatus = $user['greetings'] == 1 ? "<span style='color:green'>Yes</span>" : "<span style='color:red'>No</span>";
        echo "<tr>
                <td>{$user['id']}</td>
                <td>{$user['username']}</td>
                <td>{$user['email']}</td>
                <td>{$user['created_at']}</td>
                <td>$greetingStatus</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='status-box info'>No users found in database</div>";
}

// 6. PHP Mail function test
echo "<h2>6. PHP Configuration</h2>";
echo "<table>
        <tr><th>Setting</th><th>Value</th></tr>
        <tr><td>SMTP (sendmail_from)</td><td>" . (ini_get('sendmail_from') ?: '<span style=\"color:orange\">Not configured</span>') . "</td></tr>
        <tr><td>SMTP (SMTP)</td><td>" . (ini_get('SMTP') ?: '<span style=\"color:orange\">localhost</span>') . "</td></tr>
        <tr><td>SMTP Port</td><td>" . (ini_get('smtp_port') ?: '<span style=\"color:orange\">25</span>') . "</td></tr>
      </table>";

// Summary and Recommendations
echo "<h2>📋 Summary & Recommendations</h2>";

$issues = [];
$mailConfigComplete = !empty($mailConfig['host']) && !empty($mailConfig['username']) && !empty($mailConfig['password']);

if (!$mailConfigComplete) {
    $issues[] = "<div class='status-box error'><strong>CRITICAL:</strong> SMTP mail configuration is missing or incomplete. Welcome emails cannot be sent until this is configured.</div>";
}

if (!file_exists($phpMailerPath)) {
    $issues[] = "<div class='status-box error'><strong>CRITICAL:</strong> PHPMailer library is missing. Install it in <code>users/PHPMailer/</code></div>";
}

$usersNeedingEmails = $conn->query("SELECT COUNT(*) as count FROM users WHERE greetings = 0 OR greetings IS NULL")->fetch_assoc()['count'];
if ($usersNeedingEmails > 0) {
    $issues[] = "<div class='status-box warning'><strong>ACTION NEEDED:</strong> $usersNeedingEmails user(s) have not received welcome emails. Once SMTP is configured, run <code>users/send greetings/send_greetings.php</code> to send pending emails.</div>";
}

if (empty($issues)) {
    echo "<div class='status-box success'><strong>✓ Everything looks good!</strong> The email system is properly configured.</div>";
} else {
    foreach ($issues as $issue) {
        echo $issue;
    }
}

// Action buttons
echo "<h2>🔧 Quick Actions</h2>";
echo "<a href='index.php' class='btn'>← Back to Maintenance</a>";
echo "<a href='email_logs.php' class='btn'>View Email Logs</a>";
if ($usersNeedingEmails > 0 && $mailConfigComplete) {
    echo "<a href='../users/send greetings/send_greetings.php' class='btn'>Send Pending Emails</a>";
}

echo "    </div>
</body>
</html>";

$conn->close();
?>
