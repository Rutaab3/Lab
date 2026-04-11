<?php
// Auto-Emailer for Unsent Logs
// Run this script via cron job or manually

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// 1. Dependencies
require '../users/PHPMailer/src/Exception.php';
require '../users/PHPMailer/src/PHPMailer.php';
require '../users/PHPMailer/src/SMTP.php';
include '../config/db.php';
include '../config/settings.php'; // Contains get_system_settings
$mailConfig = get_system_settings('mail');

// 2. Fetch Unnotified Logs
$sql = "SELECT * FROM logs WHERE notify = 0 ORDER BY timestamp DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $logs = [];
    $logIds = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
        $logIds[] = $row['id'];
    }

    // 3. Prepare Email Content
    $rows_html = '';
    foreach ($logs as $log) {
        // Format Details JSON
        $details = json_decode($log['details'], true);
        $details_str = [];
        if (is_array($details)) {
            foreach ($details as $k => $v) {
                // Formatting key
                $keyDisplay = ucfirst(str_replace(['_', '-'], ' ', $k));
                $details_str[] = "<span style='color:#777;'>$keyDisplay:</span> $v";
            }
        } else {
            $details_str[] = $log['details'];
        }
        $details_html = implode('<br>', $details_str);

        // Badge Logic (Reuse form preview)
        $action = htmlspecialchars($log['action']);
        $badgeColor = '#e2e3e5'; $textColor = '#383d41'; // default grey
        
        if (stripos($action, 'banned') !== false || stripos($action, 'deleted') !== false || stripos($action, 'failed') !== false) {
            $badgeColor = '#f8d7da'; $textColor = '#721c24'; // red
        } elseif (stripos($action, 'added') !== false || stripos($action, 'created') !== false || stripos($action, 'success') !== false) {
            $badgeColor = '#d4edda'; $textColor = '#155724'; // green
        } elseif (stripos($action, 'updated') !== false || stripos($action, 'changed') !== false) {
            $badgeColor = '#cce5ff'; $textColor = '#004085'; // blue
        }

        $time = date('M j, H:i', strtotime($log['timestamp']));

        $rows_html .= "
        <tr style='border-bottom: 1px solid #eeeeee;'>
            <td style='padding: 12px; color: #666; font-size: 13px;'>$time</td>
            <td style='padding: 12px;'>
                <div style='font-weight: bold; color: #333;'>{$log['action']}</div>
                <div style='font-size: 11px; background-color: $badgeColor; color: $textColor; display:inline-block; padding:2px 6px; border-radius:4px; margin-top:4px;'>{$log['role']}</div>
            </td>
            <td style='padding: 12px; font-size: 13px; color: #444;'>
                $details_html
                <div style='margin-top:4px; font-size:11px; color:#999;'>By: <strong>{$log['username']}</strong></div>
            </td>
        </tr>";
    }

    // Load Template
    $template = file_get_contents(__DIR__ . '/log_email_template.html');
    $template = str_replace('{{DATE}}', date('F j, Y'), $template);
    $template = str_replace('{{LOG_ROWS}}', $rows_html, $template);
    $template = str_replace('{{DASHBOARD_URL}}', 'http://localhost/lab/maintenance/user_activity.php', $template);

    // 4. Fetch Admins
    $admins = $conn->query("SELECT email, username FROM users WHERE role = 'admin'");
    
    if ($admins->num_rows > 0) {
        
        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            
            $mail->setFrom($mailConfig['username'], 'Lab Automation System');
            
            // Add Admins
            while ($admin = $admins->fetch_assoc()) {
                $mail->addAddress($admin['email'], $admin['username']);
            }
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Activity Notification - ' . date('H:i');
            
            // Fix images for email
            if (file_exists('../images/logo22.png')) {
                $mail->addEmbeddedImage('../images/logo22.png', 'hero_image');
                $template = str_replace('../images/logo22.png', 'cid:hero_image', $template);
            }
            if (file_exists('../images/logo.png')) {
                $mail->addEmbeddedImage('../images/logo.png', 'logo_image');
                $template = str_replace('../images/logo.png', 'cid:logo_image', $template);
            }
            
            $mail->Body = $template;
            
            $mail->send();
            echo "Email sent to admins with " . count($logs) . " logs.<br>";
            
            // Log this email to email_logs table
            $subjectEscaped = $conn->real_escape_string($mail->Subject);
            $conn->query("INSERT INTO email_logs (email_type, recipient, subject, status) 
                          VALUES ('notification', 'Admins', '$subjectEscaped', 'success')");

            // 5. Update Logs as Notified
            $ids = implode(',', $logIds);
            $conn->query("UPDATE logs SET notify = 1 WHERE id IN ($ids)");
            echo "Database updated. notify set to 1.";
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No admins found to send email.";
    }

} else {
    echo "No unnotified logs found.";
}
?>
