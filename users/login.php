<?php
session_start();
include "../config/db.php";
include "../config/settings.php";
include "../config/security_logger.php";
mysqli_report(MYSQLI_REPORT_OFF);

$message = "";
$error = "";

// Check for account deleted error
if (isset($_GET['error']) && $_GET['error'] == 'AccountDeleted') {
    $error = "Your account has been deleted.";
}

$googleKeys = get_system_settings('google');
$cfKeys = get_system_settings('cloudflare');

// --- DYNAMIC REDIRECT URI ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$redirectUri = $protocol . $domainName . "/lab/users/login.php"; // Point to login.php

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
                $_SESSION['username'] = $user['username'];
                
                // Update last login
                $conn->query("UPDATE users SET last_login = NOW() WHERE id = " . $user['id']);
                
                // Log successful Google login
                log_security_event($conn, 'login_success', $user['id'], $user['username'], 'User logged in via Google');

                $role = $user['role'];
                if ($role === 'admin') header("Location: ../dashboard/admin.php");
                elseif ($role === 'tester') header("Location: ../dashboard/tester.php");
                elseif ($role === 'analyst') header("Location: ../dashboard/analyst.php");
                elseif ($role === 'supplier') header("Location: ../dashboard/supplier.php");
                elseif ($role === 'user') header("Location: ../index.php");
                else header("Location: ../dashboard/supplier.php");
                exit;
            } else {
                // Register new user (Auto-signup for login page too)
                $password = md5(uniqid()); // Random password
                 // $image = $userInfo['picture'] ?? 'uploads/users/default.png'; 
                $image = 'uploads/users/default.png';
                // Insert
                 $sql = "INSERT INTO users (username, email, password, profile_img, role) 
                        VALUES ('$name', '$email', '$password', '$image', 'user')";
                 if ($conn->query($sql)) {
                      $_SESSION['user_id'] = $conn->insert_id;
                      $_SESSION['role'] = 'user';
                      $_SESSION['username'] = $name;
                      header("Location: ../index.php"); 
                      exit;
                 } else {
                     $error = "Google Sign-In Failed: " . $conn->error;
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
        $curlError = curl_error($ch);
        curl_close($ch);

        $cfResponse = json_decode($rawResponse, true);

        // 2. Check if API call failed or verification failed
        if ($rawResponse === false || !isset($cfResponse['success']) || $cfResponse['success'] !== true) {
             // Log error if needed: echo "CF Error: " . $curlError;
            $error = "Cloudflare verification failed. Please try again.";
        } else {
            // Proceed with normal login
            $username = trim($_POST['username']);
            $password = md5(trim($_POST['password']));

            //  Normal users
            $query = "SELECT * FROM users 
                      WHERE (username='$username' OR email='$username') 
                      AND password='$password' 
                      AND status='active' 
                      LIMIT 1";

            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                // Login success
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id']    = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role']       = $row['role'];
        
        // Update last login
        $conn->query("UPDATE users SET last_login = NOW() WHERE id = " . $row['id']);

        // Log successful login
        log_security_event($conn, 'login_success', $row['id'], $row['username'], 'User logged in successfully');

        if ($row['role'] === 'admin') {
            header("Location: ../dashboard/admin.php");
        }
        if ($row['role'] === 'tester') {
            header("Location: ../dashboard/tester.php");
        }
        if ($row['role'] === 'analyst') {
            header("Location: ../dashboard/analyst.php");
        }
        if ($row['role'] === 'supplier') {
            header("Location: ../dashboard/supplier.php");
        }
        if ($row['role'] === 'user') {
            header("Location: ../index.php");
        }
        exit;
        } else {
        $error = "Invalid username or password!";
        
        // Log failed login attempt
        log_security_event($conn, 'failed_login', null, $username, 'Invalid credentials');
        }
    } // End else (Cloudflare success)
  }
}



?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../css/ui.css" class="css">
  <link rel="stylesheet" href="alert.css">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

</head>
<body>

<div class="container-fluid">
  <div class="row g-0 box">
    
    <!-- Left Login Form Section -->

    <div class="col-12 col-md-6 main d-flex justify-content-center align-items-center">
      <form id="loginForm" method="post" class="w-100">
        <h1>Sign-In</h1>

        <div class="mb-3 text-center">
            <a href="<?= htmlspecialchars($googleLoginUrl) ?>" class="btn w-100 btn-light border d-flex align-items-center justify-content-center gap-2 py-2" style="font-weight: 500;">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width: 20px; height: 20px;">
                Sign in with Google
            </a>
        </div>
        
        <div class="text-center mb-3 text-muted">OR</div>
        <?php if ($message): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: '<?= addslashes($message) ?>',
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

        <!-- username/email -->
        <div class="input-container">
          <input type="text" class="form_control" id="exampleInputUser" required name="username" placeholder=" " autocomplete="username">
          <label for="exampleInputUser"><i class="bi bi-people"></i> Username or Email</label>
        </div>

        <!-- password -->
        <div class="input-container">
          <input type="password" class="form_control" id="exampleInputPassword" required name="password" placeholder=" " autocomplete="current-password">
          <label for="exampleInputPassword"><i class="bi bi-lock"></i> Password</label>
        </div>

                  <!-- Checkbox for Cloudflare -->
         <div class="mb-3 d-flex justify-content-center">
            <div class="cf-turnstile" data-sitekey="<?= $cfKeys['site_key'] ?>"></div>
         </div>

         <button type="submit" class="btn-custom w-100">Login <i class="bi bi-arrow-right-square"></i></button>

        <!-- extra links -->
        <div class="mt-3 d-flex justify-content-between">
          <a href="register.php" class="links"><p>Create Account</p></a>
          <a href="forgot.php" class="links"><p>Forgot Password?</p></a>
        </div>
      </form>
    </div>

    <!-- Right Section -->
    <div class="col-12 col-md-6 right-section d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h1>Welcome Back!</h1>
      <p>Login to access your account and info.</p>
      <img src="../images/logo33.png" 
           alt="Welcome Image" class="product-img img-fluid rounded shadow-lg mt-3">
    </div>

  </div>
</div>
<script>
const validation = new JustValidate('#loginForm');

  validation
    .addField('input[name="username"]', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Username is required',
      }
    ],{
  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
})

    .addField('input[name="password"]', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Password is required'
      }
    ],{
  // This third parameter is where the magic happens
  successMessage: '<i class="bi bi-check-circle text-success"></i> Parrfectooo!',
  successFieldCssClass: 'success'  
})
    .onSuccess((event) => {
      // submit the form normally
    document.getElementById('loginForm').submit();
    });
</script>


</body>
</html>
