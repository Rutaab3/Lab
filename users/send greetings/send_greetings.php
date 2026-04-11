<?php
/**
 * Send Welcome Greetings to New Users
 * This script sends welcome emails to all users who haven't received them yet
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include '../../config/db.php';
include '../../config/settings.php';

// Function to send welcome email
function sendWelcomeEmail($mail, $email, $username, $registrationDate) {
    try {
        // Clear previous recipients
        $mail->clearAddresses();
        $mail->clearAttachments();
        
        // Add recipient
        $mail->addAddress($email);

        // Load email template
        $template = file_get_contents(__DIR__ . '/welcome_template.html');
        
        // Dashboard URL - adjust this to your actual dashboard URL
        $dashboardUrl = 'http://localhost/lab/dashboard/';
        
        // Replace placeholders
        $template = str_replace('{{USERNAME}}', htmlspecialchars($username), $template);
        $template = str_replace('{{EMAIL}}', htmlspecialchars($email), $template);
        $template = str_replace('{{REGISTRATION_DATE}}', htmlspecialchars($registrationDate), $template);
        $template = str_replace('{{DASHBOARD_URL}}', htmlspecialchars($dashboardUrl), $template);

        // Content
        $mail->Subject = 'Welcome to Lab Automation - Let\'s Get Started!';
        $mail->Body    = $template;
        $mail->AltBody = "Welcome to Lab Automation, $username! Thank you for registering. Your account has been created successfully. Login at: $dashboardUrl";

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log("Welcome email error for $email: {$mail->ErrorInfo}");
        return false;
    }
}

// Get users who haven't received greeting emails
$stmt = $conn->prepare("SELECT id, email, username, created_at FROM users WHERE greetings = 0");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No users found who need greeting emails.<br>";
    exit;
}

echo "<h2>Sending Welcome Emails</h2>";
echo "<p>Found " . $result->num_rows . " user(s) to send greetings to...</p><hr>";

// Setup PHPMailer once
$mailConfig = get_system_settings('mail');
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();
$mail->Host       = $mailConfig['host'];
$mail->SMTPAuth   = true;
$mail->Username   = $mailConfig['username'];
$mail->Password   = $mailConfig['password'];
$mail->SMTPSecure = $mailConfig['encryption'];
$mail->Port       = $mailConfig['port'];
$mail->setFrom($mailConfig['username'], 'Lab Automation');

// Embed images once (they'll be reused for all emails)
$heroPath = '../../images/hero.png';
$logoPath = '../../images/log0.png';

if (file_exists($heroPath)) {
    $mail->addEmbeddedImage($heroPath, 'hero_image');
}
if (file_exists($logoPath)) {
    $mail->addEmbeddedImage($logoPath, 'logo_image');
}

$mail->isHTML(true);

// Track statistics
$successCount = 0;
$failCount = 0;
$failedUsers = [];

// Process each user
while ($user = $result->fetch_assoc()) {
    $userId = $user['id'];
    $email = $user['email'];
    $username = $user['username'];
    
    // Format registration date
    $registrationDate = date('F j, Y', strtotime($user['created_at'] ?? 'now'));
    
    echo "<div style='margin: 10px 0; padding: 10px; background: #f5f5f5; border-left: 3px solid #000;'>";
    echo "<strong>Processing:</strong> $username ($email)<br>";
    
    // Send email
    if (sendWelcomeEmail($mail, $email, $username, $registrationDate)) {
        // Mark as sent in database
        $updateStmt = $conn->prepare("UPDATE users SET greetings = 1 WHERE id = ?");
        $updateStmt->bind_param("i", $userId);
        
        if ($updateStmt->execute()) {
            echo "<span style='color: green;'>✓ Email sent and marked as complete</span>";
            $successCount++;
        } else {
            echo "<span style='color: orange;'>⚠ Email sent but failed to update database</span>";
            $successCount++;
        }
        $updateStmt->close();
    } else {
        echo "<span style='color: red;'>✗ Failed to send email</span>";
        $failCount++;
        $failedUsers[] = $email;
    }
    
    echo "</div>";
    
    // Small delay to avoid overwhelming mail server
    usleep(500000); // 0.5 second delay
}

// Summary
echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p><strong>Total Processed:</strong> " . ($successCount + $failCount) . "</p>";
echo "<p style='color: green;'><strong>Successfully Sent:</strong> $successCount</p>";
echo "<p style='color: red;'><strong>Failed:</strong> $failCount</p>";

if (!empty($failedUsers)) {
    echo "<p><strong>Failed Recipients:</strong><br>" . implode(', ', $failedUsers) . "</p>";
}

echo "<hr>";
echo "<p><a href='send_greetings.php'>Run Again</a> | <a href='../../dashboard/'>Go to Dashboard</a></p>";

$conn->close();
?>
