<?php
include "../config/db.php"; 
include "../config/auth.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'tester' && $role !== 'supplier' && $role !== 'analyst' && $role !== 'user') {
    header("Location: ../401.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id LIMIT 1");
$user = mysqli_fetch_assoc($res);
if (!$user) die("User not found.");

$message = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $profile_img = $user['profile_img'];

    // upload
    if (!empty($_FILES['profile_img']['name'])) {
        $dir = "../uploads/users/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $file = time() . "_" . basename($_FILES["profile_img"]["name"]);
        $path = $dir . $file;

        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $path)) {
            $profile_img = "uploads/users/" . $file;
        } else {
            $error = "Image upload failed.";
        }
    }

    if (!$error) {
        $sql = "UPDATE users SET username='$username', email='$email', profile_img='$profile_img' WHERE id=$user_id";
        if (mysqli_query($conn, $sql)) {
            $message = "Profile updated!";
            $res = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id LIMIT 1");
            $user = mysqli_fetch_assoc($res);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Profile</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/create.css">
  <link rel="stylesheet" href="alert.css">
<style>
    .con {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 2rem auto;
    cursor: pointer;
    transition: all 0.3s ease;
 }

 .con:hover label {
    opacity: 1;
 } 

 .profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid var(--primary);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
 }

 .con label {
    position: absolute;
    top: 70%;
    left: 75%;
    background: var(--primary);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
 }

 .con label:hover {
    background: var(--accent) !important;
    transform: scale(1.1);
 }

 .con label i {
    font-size: 1.2rem;
 }

 .d-none {
    display: none !important;
 }

 @media (max-width: 768px) {
    .con {
        width: 120px;
        height: 120px;
        margin-bottom: 1.5rem;
    }
 }

 @media (max-width: 576px) {
    .con {
        width: 100px;
        height: 100px;
        margin-bottom: 1rem;
    }
    
    .con label {
        width: 35px;
        height: 35px;
    }
    
    .con label i {
        font-size: 1rem;
    }
 }
</style>
</head>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
} elseif ($role === 'supplier') {
    include '../xtras/supplierhead.php';
} elseif ($role === 'analyst') {
    include '../xtras/analysthead.php';
} elseif ($role === 'user') {
    include '../xtras/usershead.php';
}
?>

  <div class="main">
    <form method="post" enctype="multipart/form-data" id="profileForm">
      <h1>My Profile</h1>

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

      <!-- Profile Image -->
       <div class="con">
          <img src="../<?= htmlspecialchars($user['profile_img']) ?>" class="profile-img" alt="Profile">
          <label for="profile_img"><i class="bi bi-camera" title="upload image" ></i></label>
          <input type="file" id="profile_img" name="profile_img" class="form_control d-none" accept=".png,.jpg,.jpeg,.webp">
        </div>

      <!-- Username -->
      <div class="input-container">
        <input type="text" class="form_control" name="username" required value="<?= htmlspecialchars($user['username']) ?>" placeholder=" ">
        <label><i class="bi bi-people"></i> Username</label>
      </div>

      <!-- Email -->
      <div class="input-container">
        <input type="email" class="form_control" name="email" required value="<?= htmlspecialchars($user['email']) ?>" placeholder=" ">
        <label><i class="bi bi-envelope"></i> Email</label>
      </div>

      <button type="submit" class="btn-custom mb-2 w-100">Update Profile <i class="bi bi-arrow-right-square"></i></button>
    </form>
  </div>

  <script>
document.getElementById('profile_img').addEventListener('change', e => {
  const file = e.target.files[0];
  if (file) {
    document.querySelector('.profile-img').src = URL.createObjectURL(file);
  }
});
</script>

</body>
</html>
