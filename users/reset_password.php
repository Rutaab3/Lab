<?php
session_start();
include "../config/db.php";

$error = "";
$message = "";

// 1. Handle OTP Verification (Redirected from verify_otp.php)
if (isset($_POST['otp'])) {
    $email = $_SESSION['reset_email'] ?? '';
    $otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expiry > NOW()");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['otp_verified'] = true; // Mark as verified
    } else {
        header("Location: verify_otp.php?error=Invalid or Expired OTP");
        exit();
    }
}

// 2. Handle Password Reset Submission
if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
    if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
        header("Location: verify_otp.php?error=Unauthorized");
        exit();
    }

    $email = $_SESSION['reset_email'];
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($pass === $confirm) {
        $newPass = md5($pass);
        
        $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
        $stmt->bind_param("ss", $newPass, $email);
        
        if ($stmt->execute()) {
            session_destroy(); // Logout / Clear reset session
            header("Location: login.php?message=Password Reset Successfully");
            exit();
        } else {
            $error = "Database Error";
        }
    } else {
        $error = "Passwords do not match";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>New Password</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/ui.css" class="css">
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="alert.css">
</head>
<body>

<div class="container-fluid">
  <div class="row g-0 box">
    
    <!-- Left Form Section -->
    <div class="col-12 col-md-6 main d-flex justify-content-center align-items-center">
      
      <?php if (!isset($_SESSION['otp_verified'])): ?>
         <!-- If accessed directly without verification -->
         <div class="text-center w-75">
             <script>
               document.addEventListener('DOMContentLoaded', function() {
                 Swal.fire({
                   icon: 'error',
                   title: 'Unauthorized Access',
                   text: 'You do not have permission to access this page.',
                   customClass: {
                     popup: 'swal2-error'
                   }
                 });
               });
             </script>
             <div class="alert alert-danger"><i class="bi bi-shield-lock"></i> Unauthorized Access</div>
             <a href="forgot.php" class="btn-custom w-100">Start Over</a>
         </div>
      <?php else: ?>

      <form method="post" id="resetForm" class="w-100">
      <div class="text-center">
          <h1>Set New Password</h1>
        <p class="text-muted mb-4">Create a strong password for your account.</p>
        </div>

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
        
        <!-- New Password -->
        <div class="input-container">
          <input type="password" class="form_control" id="newPass" name="password" required placeholder=" " autocomplete="new-password">
          <label for="newPass"><i class="bi bi-lock"></i> New Password</label>
        </div>

        <!-- Confirm Password -->
        <div class="input-container">
          <input type="password" class="form_control" id="confirmPass" name="confirm_password" required placeholder=" " autocomplete="new-password">
          <label for="confirmPass"><i class="bi bi-lock"></i> Confirm Password</label>
        </div>

        <button type="submit" name="reset_password" class="btn-custom w-100">Update Password <i class="bi bi-arrow-repeat"></i></button>
      </form>
      <?php endif; ?>

    </div>

    <!-- Right Section -->
    <div class="col-12 col-md-6 right-section d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h1>Fresh Start</h1>
      <p>Your security is our priority.</p>
      <img src="../images/logo33.png" 
           alt="Security Image" class="product-img img-fluid rounded shadow-lg mt-3">
    </div>

  </div>
</div>

<script>
  const validation = new JustValidate('#resetForm');

  validation
    .addField('#newPass', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Password is required'
      },
      {
        rule: 'minLength',
        value: 6,
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Min 6 chars'
      }
    ],{
      successMessage: '<i class="bi bi-check-circle text-success"></i> Strong!',
      successFieldCssClass: 'success'  
    })
    .addField('#confirmPass', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Confirm Password is required'
      },
      {
        validator: (value, fields) => {
          if (fields['#newPass'] && fields['#newPass'].elem) {
            const repeatPasswordValue = fields['#newPass'].elem.value;
            return value === repeatPasswordValue;
          }
          return true;
        },
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Passwords must match',
      }
    ],{
      successMessage: '<i class="bi bi-check-circle text-success"></i> Matching!',
      successFieldCssClass: 'success'  
    })
    .onSuccess((event) => {
      document.getElementById('resetForm').submit();
    });
</script>

</body>
</html>
