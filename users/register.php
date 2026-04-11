<?php
session_start();
include "../config/db.php"; 
include "../config/settings.php"; 
include "../config/security_logger.php";
mysqli_report(MYSQLI_REPORT_OFF);

// Import PHPMailer for welcome emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = "";
$error = "";

// Function to send welcome email
function sendWelcomeEmail($conn, $email, $username, $registrationDate) {
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

        // Create inline email template (no external file needed)
        $dashboardUrl = 'http://localhost/lab/';
        
        $template = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Welcome to Lab Automation</title>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
            <table role='presentation' style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td align='center' style='padding: 40px 0;'>
                        <table role='presentation' style='width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                            <!-- Header -->
                            <tr>
                                <td style='padding: 40px 30px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px 8px 0 0;'>
                                    <h1 style='margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;'>Welcome to Lab Automation!</h1>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style='padding: 40px 30px;'>
                                    <h2 style='margin: 0 0 20px 0; color: #333333; font-size: 22px;'>Hello, " . htmlspecialchars($username) . "!</h2>
                                    
                                    <p style='margin: 0 0 15px 0; color: #666666; font-size: 16px; line-height: 1.6;'>
                                        Thank you for registering with Lab Automation. We're excited to have you on board!
                                    </p>
                                    
                                    <p style='margin: 0 0 15px 0; color: #666666; font-size: 16px; line-height: 1.6;'>
                                        Your account has been successfully created and you can now access all the features of our platform.
                                    </p>
                                    
                                    <table role='presentation' style='margin: 30px 0; border-collapse: collapse; background-color: #f8f9fa; border-radius: 6px; padding: 20px; width: 100%;'>
                                        <tr>
                                            <td style='padding: 10px;'>
                                                <p style='margin: 0; color: #666666; font-size: 14px;'><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                                                <p style='margin: 10px 0 0 0; color: #666666; font-size: 14px;'><strong>Registration Date:</strong> " . htmlspecialchars($registrationDate) . "</p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <table role='presentation' style='margin: 30px auto; border-collapse: collapse;'>
                                        <tr>
                                            <td style='border-radius: 6px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>
                                                <a href='" . htmlspecialchars($dashboardUrl) . "' style='display: inline-block; padding: 14px 40px; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 16px;'>
                                                    Get Started
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p style='margin: 20px 0 0 0; color: #666666; font-size: 14px; line-height: 1.6;'>
                                        If you have any questions or need assistance, feel free to reach out to our support team.
                                    </p>
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style='padding: 30px; text-align: center; background-color: #f8f9fa; border-radius: 0 0 8px 8px;'>
                                    <p style='margin: 0; color: #999999; font-size: 12px;'>
                                        © " . date('Y') . " Lab Automation. All rights reserved.
                                    </p>
                                    <p style='margin: 10px 0 0 0; color: #999999; font-size: 12px;'>
                                        This is an automated message, please do not reply to this email.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Lab Automation - Let\'s Get Started!';
        $mail->Body    = $template;
        $mail->AltBody = "Welcome to Lab Automation, $username! Thank you for registering. Your account has been created successfully. Login at: $dashboardUrl";

        $mail->send();
        
        // Log email to email_logs
        $subjectEscaped = $conn->real_escape_string($mail->Subject);
        $recipientEscaped = $conn->real_escape_string($email);
        $conn->query("INSERT INTO email_logs (email_type, recipient, subject, status) 
                      VALUES ('welcome', '$recipientEscaped', '$subjectEscaped', 'success')");
                      
        return true;
    } catch (PHPMailerException $e) {
        error_log("Welcome email error: {$mail->ErrorInfo}");
         // Log failure
         $recipientEscaped = $conn->real_escape_string($email);
         $conn->query("INSERT INTO email_logs (email_type, recipient, subject, status, error_message) 
                       VALUES ('welcome', '$recipientEscaped', 'Welcome Email', 'failed', '" . $conn->real_escape_string($mail->ErrorInfo) . "')");
        return false;
    }
}

$googleKeys = get_system_settings('google');
$cfKeys = get_system_settings('cloudflare');

// --- DYNAMIC REDIRECT URI ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$redirectUri = $protocol . $domainName . "/lab/users/register.php";

// --- GOOGLE LOGIN LOGIC ---
if (isset($_GET['code'])) {
    $tokenUrl = $googleKeys['token_uri'];
    $params = [
        'code' => $_GET['code'],
        'client_id' => $googleKeys['client_id'],
        'client_secret' => $googleKeys['client_secret'],
        'redirect_uri' => $redirectUri, 
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokenData = json_decode($response, true);
    
    if (isset($tokenData['access_token'])) {
        // Get User Info
        $userInfoUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $tokenData['access_token'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $userInfo = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (isset($userInfo['email'])) {
            $email = $userInfo['email'];
            $name = $userInfo['name'] ?? 'Google User';
            
            // Check if user exists
            $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
            if ($check->num_rows > 0) {
                // User exists, login
                $user = $check->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username']; // Add username to session too

                $role = $user['role'];
                if ($role === 'admin') header("Location: ../dashboard/admin.php");
                elseif ($role === 'tester') header("Location: ../dashboard/tester.php");
                elseif ($role === 'analyst') header("Location: ../dashboard/analyst.php");
                elseif ($role === 'supplier') header("Location: ../dashboard/supplier.php");
                elseif ($role === 'user') header("Location: ../index.php");
                else header("Location: ../404.php");
                exit;
            } else {
                // Register new user
                $password = md5(uniqid()); // Random password
                // $image = $userInfo['picture'] ?? 'uploads/users/default.png'; // User requested to ONLY use default
                $image = 'uploads/users/default.png';
                // Insert
                 $sql = "INSERT INTO users (username, email, password, profile_img, role) 
                        VALUES ('$name', '$email', '$password', '$image', 'user')";
                 if ($conn->query($sql)) {
                      $userId = $conn->insert_id;
                      $_SESSION['user_id'] = $userId;
                      $_SESSION['role'] = 'user';
                      $_SESSION['username'] = $name;
                      
                      // Send welcome email and update greetings only if successful
                      $registrationDate = date('F j, Y');
                      $emailSent = sendWelcomeEmail($conn, $email, $name, $registrationDate);
                      
                      if ($emailSent) {
                          // Update greetings column only if email sent successfully
                          $conn->query("UPDATE users SET greetings = 1 WHERE id = $userId");
                      }
                      // If email fails, greetings stays 0 and can be retried later
                      
                      // Log user registration via Google
                      log_security_event($conn, 'user_registration', $userId, $name, 'New user registered via Google');
                      
                      header("Location: ../index.php"); 
                      exit;
                 } else {
                     $error = "Google Sign-Up Failed: " . $conn->error;
                     // Log failed Google registration
                     log_security_event($conn, 'registration_failed', null, $name, 'Google registration failed: ' . $conn->error);
                 }
            }
        }
    }
}

// --- GOOGLE AUTH URL ---
$googleLoginUrl = $googleKeys['auth_uri'] . "?" . http_build_query([
    'client_id' => $googleKeys['client_id'],
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online'
]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- CLOUDFLARE CHECK ---
    $cfToken = $_POST['cf-turnstile-response'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // 1. Fail immediately if token is missing (User didn't click the box)
    if (empty($cfToken)) {
        // DEBUG MODE ACTIVE
        die("DEBUG: The server received an empty Cloudflare token. The checkbox was NOT recognized as checked.");
        $error = "Please verify you are human by checking the box.";
    } else {
        $verifyUrl = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
        $data = [
            'secret' => $cfKeys['secret_key'],
            'response' => $cfToken,
            'remoteip' => $ip
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $rawResponse = curl_exec($ch);
        curl_close($ch);

        $cfResponse = json_decode($rawResponse, true);

        if ($rawResponse === false || !isset($cfResponse['success']) || $cfResponse['success'] !== true) {
            $error = "Cloudflare verification failed. Please try again.";
        } else {
        // Proceed with normal registration
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $password = md5(trim($_POST['password']));
        // default profile image
        $imagePath = "uploads/users/default.png"; 

        // handle upload
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = "../uploads/users/";
            $fileName  = time() . "_" . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = "uploads/users/" . $fileName;
            }
        }

        // insert user
        $sql = "INSERT INTO users (username, email, password, profile_img, role) 
                VALUES ('$username', '$email', '$password', '$imagePath', 'user')";

        if (mysqli_query($conn, $sql)) {
            $userId = mysqli_insert_id($conn);
            
            // Send welcome email and update greetings only if successful
            $registrationDate = date('F j, Y');
            $emailSent = sendWelcomeEmail($conn, $email, $username, $registrationDate);
            
            if ($emailSent) {
                // Update greetings column only if email sent successfully
                mysqli_query($conn, "UPDATE users SET greetings = 1 WHERE id = $userId");
            }
            // If email fails, greetings stays 0 and can be retried later
            
            // Log user registration
            log_security_event($conn, 'user_registration', $userId, $username, 'New user registered');
            
            $message = "Account created successfully! <a href='login.php'>Login here</a>";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $error = "Username or Email already exists!";
                // Log failed registration - duplicate account
                log_security_event($conn, 'registration_failed', null, $username, 'Registration failed: Username or email already exists');
            } else {
                $error = "Something went wrong. Try again.";
                // Log failed registration - database error
                log_security_event($conn, 'registration_failed', null, $username, 'Registration failed: Database error');
            }
        }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../css/ui.css" class="css">
  <link rel="stylesheet" href="alert.css">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>

<div class="container-fluid">
  <div class="row g-0 box">
    
    <!-- Left Form Section -->
    <div class="col-12 col-md-6 main d-flex justify-content-center align-items-center">
      <form method="post" enctype="multipart/form-data" id="registerForm" class="w-100" autocomplete="off">
        <h1>Sign-Up</h1>
        
        <div class="mb-3 text-center">
            <a href="<?= htmlspecialchars($googleLoginUrl) ?>" class="btn w-100 btn-light border d-flex align-items-center justify-content-center gap-2 py-2" style="font-weight: 500;">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width: 20px; height: 20px;">
                Sign up with Google
            </a>
        </div>
        
        <div class="text-center mb-3 text-muted">OR</div>

        <?php if ($message): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              html: '<?= addslashes($message) ?>',
              customClass: {
                popup: 'swal2-success'
              }
            });
          });
        </script>
        <?php endif; ?>
        <?php if ($error): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: '<?= addslashes($error) ?>',
              customClass: {
                popup: 'swal2-error'
              }
            });
          });
        </script>
        <?php endif; ?>

        <!-- username -->
        <div class="input-container">
          <input type="text" class="form_control" name="username" required placeholder=" " autocomplete="username">
          <label><i class="bi bi-people"></i> Username</label>
        </div>

        <!-- email -->
        <div class="input-container">
          <input type="email" class="form_control" name="email" required placeholder=" " autocomplete="email">
          <label><i class="bi bi-envelope"></i> Email</label>
        </div>

        <!-- password -->
        <div class="input-container">
          <input type="password" class="form_control" name="password" required placeholder=" " autocomplete="new-password">
          <label><i class="bi bi-lock"></i> Password</label>
        </div>

        <!--confirm password -->
        <div class="input-container">
          <input type="password" class="form_control" name="confirmpassword" required placeholder=" " autocomplete="new-password">
          <label><i class="bi bi-lock"></i> Confirm Password</label>
        </div>

        <!-- profile image -->
        <div class="mb-4">
          <input type="file" class="form_control" name="image" accept=".png,.jpg,.jpeg,.webp">
        </div>

        <!-- Checkbox for Cloudflare -->
         <div class="mb-3 d-flex justify-content-center">
            <div class="cf-turnstile" data-sitekey="<?= $cfKeys['site_key'] ?>"></div>
         </div>

          <button type="submit" class="btn-custom w-100">Register <i class="bi bi-arrow-right-square"></i></button>

        <div class="mt-3 d-flex">
          <a href="login.php" class="links">Already have an account?</a>
        </div>

            
      </form>
    </div>

    <!-- Right Visual Section -->
    <div class="col-12 col-md-6 right-section d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h2>Don't have a account yet!</h2>
      <p>Register now to be a part of our community<p>
      <img src="../images/logo33.png" 
           alt="Welcome Image" class="product-img img-fluid rounded shadow-lg mt-3">
    </div>

  </div>
</div>

<script>
  
  const validation = new JustValidate('#registerForm');

  validation
   .addField('input[name="username"]', [
  {
    rule: 'required',
    errorMessage: '<i class="bi bi-exclamation-circle"></i> Username is required',
  },
  {
    validator: (value) => /^[A-Za-z]{3,}$/.test(value),
    errorMessage: '<i class="bi bi-exclamation-circle"></i> Letters only, min 3 characters'
  }
], {
  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
})

    .addField('input[name="email"]', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Email is required'
      },
      {
        rule: 'email',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Enter a valid email address'
      }
    ],{  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
    })


    .addField('input[name="password"]', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Password is required'
      },
      {
        validator: (value) => /^(?=.*[A-Za-z])(?=.*\d).{6,}$/.test(value),
        errorMessage: '<i class="bi bi-exclamation-circle"></i> 6+ characters, letters & numbers'
      }
    ],{
  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
})

       
  .addField('input[name="confirmpassword"]', [
    {
      rule: 'required', 
      errorMessage: '<i class="bi bi-exclamation-circle"></i> Confirm Password is required',
    },
    {
      validator: (value, fields) => {
        if (
          fields['input[name="password"]'] &&
          fields['input[name="password"]'].elem
        ) {
          const repeatPasswordValue =
            fields['input[name="password"]'].elem.value;

          return value === repeatPasswordValue;
        }

        return true;
      },
      errorMessage: '<i class="bi bi-exclamation-circle"></i> Passwords should be the same',
    },
  ],{
  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
})


    .onSuccess((event) => {
      // submit the form normally
    document.getElementById('registerForm').submit();
    });
</script>

</body>
</html>
