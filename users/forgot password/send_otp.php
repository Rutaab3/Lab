<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// require '../vendor/autoload.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include '../../config/db.php';
include '../../config/settings.php';
include '../../config/security_logger.php';

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        // $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes")); // Removed PHP time calculation

        // Update DB with OTP using MySQL time
        $updateStmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $updateStmt->bind_param("ss", $otp, $email);
        
        if ($updateStmt->execute()) {
            // Send Email
            $mailConfig = get_system_settings('mail');
            $mail = new PHPMailer(true);

            try {
                // Get user data for personalization
                $userStmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
                $userStmt->bind_param("s", $email);
                $userStmt->execute();
                $userData = $userStmt->get_result()->fetch_assoc();
                $username = $userData['username'] ?? 'User';
                
                // Generate reference ID
                $refId = 'OTP-' . strtoupper(substr(md5(time() . $email), 0, 8));
                
                // Load email template
                $template = file_get_contents(__DIR__ . '/email_template.html');
                
                // Replace placeholders
                $template = str_replace('{{USERNAME}}', htmlspecialchars($username), $template);
                $template = str_replace('{{OTP_CODE}}', $otp, $template);
                $template = str_replace('{{REFERENCE_ID}}', $refId, $template);
                
                $mail->isSMTP();
                $mail->Host       = $mailConfig['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $mailConfig['username'];
                $mail->Password   = $mailConfig['password'];
                $mail->SMTPSecure = $mailConfig['encryption'];
                $mail->Port       = $mailConfig['port'];

                $mail->setFrom($mailConfig['username'], 'Lab Automation');
                $mail->addAddress($email);

                // Embed images
                $heroPath = '../../images/hero.png';
                $logoPath = '../../images/log0.png';
                
                // Check if images exist, if not use placeholder
                if (file_exists($heroPath)) {
                    $mail->addEmbeddedImage($heroPath, 'hero_image');
                }
                if (file_exists($logoPath)) {
                    $mail->addEmbeddedImage($logoPath, 'logo_image');
                }

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP - Lab Automation';
                $mail->Body    = $template;
                $mail->AltBody = "Your OTP for password reset is: $otp. It expires in 15 minutes. Reference: $refId";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                
                // Log email to email_logs
                $subjectEscaped = $conn->real_escape_string($mail->Subject);
                $recipientEscaped = $conn->real_escape_string($email);
                $conn->query("INSERT INTO email_logs (email_type, recipient, subject, status) 
                              VALUES ('password_reset', '$recipientEscaped', '$subjectEscaped', 'success')");
                
                // Log password reset request
                $userId = $result->fetch_assoc()['id'] ?? null;
                log_security_event($conn, 'password_reset', $userId, $username, 'OTP sent for password reset');
                
                header("Location: ../verify_otp.php");
                exit();
            } catch (PHPMailerException $e) {
                 // Log failure
                 $recipientEscaped = $conn->real_escape_string($email);
                 $conn->query("INSERT INTO email_logs (email_type, recipient, subject, status, error_message) 
                               VALUES ('password_reset', '$recipientEscaped', 'Password Reset OTP', 'failed', '" . $conn->real_escape_string($mail->ErrorInfo) . "')");
                header("Location: ../forgot.php?error=Mailer Error: {$mail->ErrorInfo}");
            }
        } else {
            header("Location: ../forgot.php?error=Database Error");
        }
    } else {
        header("Location: ../forgot.php?error=Email not found");
    }
} else {
    header("Location: ../forgot.php");
}
?>
