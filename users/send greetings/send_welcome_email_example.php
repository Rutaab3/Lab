<?php
/**
 * Example: How to send welcome email to new users
 * Include this code in your registration process after successful user creation
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include '../../config/settings.php';

function sendWelcomeEmail($email, $username, $registrationDate) {
    $mailConfig = get_system_settings('mail');
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

        // Recipients
        $mail->setFrom($mailConfig['username'], 'Lab Automation');
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

        // Embed images
        $heroPath = '../../images/hero.png';
        $logoPath = '../../images/log0.png';
        
        if (file_exists($heroPath)) {
            $mail->addEmbeddedImage($heroPath, 'hero_image');
        }
        if (file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logo_image');
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Lab Automation - Let\'s Get Started!';
        $mail->Body    = $template;
        $mail->AltBody = "Welcome to Lab Automation, $username! Thank you for registering. Your account has been created successfully. Login at: $dashboardUrl";

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log("Welcome email error: {$mail->ErrorInfo}");
        return false;
    }
}

// Example usage in your registration script:
// After successful user registration:
/*
$email = $userData['email'];
$username = $userData['username'];
$registrationDate = date('F j, Y'); // e.g., "December 25, 2025"

if (sendWelcomeEmail($email, $username, $registrationDate)) {
    // Email sent successfully
    error_log("Welcome email sent to: $email");
} else {
    // Email failed, but don't block registration
    error_log("Failed to send welcome email to: $email");
}
*/
?>
