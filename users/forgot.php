<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
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
      <form action="forgot password/send_otp.php" method="post" id="forgotForm" class="w-100">
        <div class="text-center">
          <h1>Forgot Password</h1>
        <p class="text-muted p-2">Enter your email to receive a verification code.</p>
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

        <!-- email -->
        <div class="input-container">
          <input type="email" class="form_control" id="emailInput" name="email" required placeholder=" " autocomplete="email">
          <label for="emailInput"><i class="bi bi-envelope"></i> Email Address</label>
        </div>

        <button type="submit" class="btn-custom w-100">Send OTP <i class="bi bi-arrow-right-square"></i></button>

        <div class="mt-3 text-center">
            <a href="login.php" class="links">Back to Login</a>
        </div>
      </form>
    </div>

    <!-- Right Section -->
    <div class="col-12 col-md-6 right-section d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h1>Recovery</h1>
      <p>Don't worry, it happens to the best of us.</p>
      <img src="../images/logo33.png" 
           alt="Recovery Image" class="product-img img-fluid rounded shadow-lg mt-3">
    </div>

  </div>
</div>

<script>
  const validation = new JustValidate('#forgotForm');

  validation
    .addField('#emailInput', [
      {
        rule: 'required',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Email is required'
      },
      {
        rule: 'email',
        errorMessage: '<i class="bi bi-exclamation-circle"></i> Enter a valid email address'
      }
    ],{
      successMessage: '<i class="bi bi-check-circle text-success"></i> Parfectooo!',
      successFieldCssClass: 'success'  
    })
    .onSuccess((event) => {
      document.getElementById('forgotForm').submit();
    });
</script>

</body>
</html>
