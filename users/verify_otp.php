<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Verify OTP</title>
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
      <form action="reset_password.php" method="post" id="otpForm" class="w-100">
       <div class="text-center">
          <h1>Verify OTP</h1>
        <p class="text-muted mb-4">We have sent a code to <br><strong><?= htmlspecialchars($_SESSION['reset_email'] ?? '') ?></strong></p>
        </div>

        <?php if (isset($_GET['error'])): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: '<?= addslashes(htmlspecialchars($_GET['error'])) ?>',
              customClass: {
                popup: 'swal2-error'
              }
            });
          });
        </script>
        <?php endif; ?>

        <!-- OTP code -->
        <div class="input-container">
          <input type="text" class="form_control" id="otpInput" name="otp" required pattern="[0-9]{6}" maxlength="6" placeholder=" ">
          <label for="otpInput"><i class="bi bi-key"></i> 6-Digit Code</label>
        </div>

        <button type="submit" name="verify_otp" class="btn-custom w-100">Verify Code <i class="bi bi-check2-square"></i></button>

        <div class="mt-3 text-center">
            <a href="forgot.php" class="links">Resend Code</a>
        </div>
      </form>
    </div>

    <!-- Right Section -->
    <div class="col-12 col-md-6 right-section d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h1>Security Check</h1>
      <p>Please verify your identity to proceed.</p>
      <img src="../images/logo33.png" 
           alt="Security Image" class="product-img img-fluid rounded shadow-lg mt-3">
    </div>

  </div>
</div>

<script>
  const validation = new JustValidate('#otpForm');

  validation
    .addField('#otpInput', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> OTP is required'
      },
      {
        rule: 'minLength',
        value: 6,
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Must be 6 digits'
      },
      {
        rule: 'maxLength',
        value: 6,
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Must be 6 digits'
      }
    ],{
      successMessage: '<i class="bi bi-check-circle text-success"></i> Valid format!',
      successFieldCssClass: 'success'  
    })
    .onSuccess((event) => {
      document.getElementById('otpForm').submit();
    });
</script>

</body>
</html>
