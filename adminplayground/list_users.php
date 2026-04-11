<?php
include "../config/db.php";
include "../config/auth.php";
include "../config/auth_admin.php"; 
include "../config/action_logger.php";

// Role check - must be before any output
if ($role !== 'admin') {
    header("Location: ../403.php");
    exit();
}
mysqli_report(MYSQLI_REPORT_OFF);

// --- Handle actions ---
if (isset($_GET['action'], $_GET['id'])) {
  $targetId = intval($_GET['id']);
  $action   = $_GET['action'];

  // Fetch target user for logging
  $targetRes = mysqli_query($conn, "SELECT username FROM users WHERE id=$targetId");
  $targetUser = mysqli_fetch_assoc($targetRes);
  $targetUsername = $targetUser['username'] ?? 'Unknown';

  if ($action === 'ban') {
      mysqli_query($conn, "UPDATE users SET status='banned' WHERE id=$targetId");
      log_action($conn, "User Banned", ['target_user_id' => $targetId, 'target_username' => $targetUsername]);
      $msg = "User Banned";
  }
  elseif ($action === 'unban') {
      mysqli_query($conn, "UPDATE users SET status='active' WHERE id=$targetId");
      log_action($conn, "User Unbanned", ['target_user_id' => $targetId, 'target_username' => $targetUsername]);
      $msg = "User Unbanned";
  }
  elseif ($action === 'user') {
      mysqli_query($conn, "UPDATE users SET role='user' WHERE id=$targetId");
      log_action($conn, "User Role Updated", ['target_user_id' => $targetId, 'target_username' => $targetUsername, 'new_role' => 'user']);
      $msg = "User Role set to User";
  }
  elseif ($action === 'supplier') {
      mysqli_query($conn, "UPDATE users SET role='supplier' WHERE id=$targetId");
      log_action($conn, "User Role Updated", ['target_user_id' => $targetId, 'target_username' => $targetUsername, 'new_role' => 'supplier']);
      $msg = "User Role set to Supplier";
  }
  elseif ($action === 'tester') {
      mysqli_query($conn, "UPDATE users SET role='tester' WHERE id=$targetId");
      log_action($conn, "User Role Updated", ['target_user_id' => $targetId, 'target_username' => $targetUsername, 'new_role' => 'tester']);
      $msg = "User Role set to Tester";
  }
  elseif ($action === 'analyst') {
      mysqli_query($conn, "UPDATE users SET role='analyst' WHERE id=$targetId");
      log_action($conn, "User Role Updated", ['target_user_id' => $targetId, 'target_username' => $targetUsername, 'new_role' => 'analyst']);
      $msg = "User Role set to Analyst";
  }

  header("Location: list_users.php?msg=" . urlencode($msg));
  exit();
}

// --- Fetch users ---
$result = mysqli_query($conn, "SELECT id, username, email, role, profile_img, status, created_at 
                               FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../css/read.css">
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="alert.css">
</head>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
}
?>

<div class="main">

  <h1>Manage Users</h1>

  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Profile</th>
          <th>Role</th>
          <th>Status</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
        <?php foreach ($result as $row): ?>
        <tr>

          <td><i class="bi bi-hash text-primary"></i><?= $row['id']; ?></td>
          <td><i class="bi bi-person-circle text-primary"></i> <?= htmlspecialchars($row['username']); ?></td>
          <td><i class="bi bi-envelope text-primary"></i> <?= htmlspecialchars($row['email']); ?></td>

          <td class="rounded-circle">
            <img src="../<?= htmlspecialchars($row['profile_img']); ?>" width="80" alt="User profile image">
          </td>

          <td>
            <?php
              $role = strtolower($row['role']);
              $icon = '';
              switch ($role) {
                case 'user': $icon = '<i class="bi bi-person-fill text-success"></i>'; break;
                case 'tester': $icon = '<i class="bi bi-person-fill text-primary"></i>'; break;
                case 'admin': $icon = '<i class="bi bi-person-fill text-danger"></i>'; break;
                case 'supplier': $icon = '<i class="bi bi-person-fill text-warning"></i>'; break;
                case 'analyst': $icon = '<i class="bi bi-person-fill text-info"></i>'; break;
                default: $icon = '<i class="bi bi-question-circle text-secondary"></i>';
              }
            ?>
            <span class="badge-status badge-<?= $role; ?>">
              <?= $icon ?> <?= ucfirst($role); ?>
            </span>
          </td>

          <td>
            <?php if ($row['status'] === 'banned'): ?>
              <span class="badge-user badge-banned">
                <i class="bi bi-person-x-fill text-danger"></i> Banned
              </span>
            <?php else: ?>
              <span class="badge-user badge-active">
                <i class="bi bi-person-check-fill text-success"></i> Active
              </span>
            <?php endif; ?>
          </td>

          <td><i class="bi bi-clock-history text-primary"></i> <?= htmlspecialchars($row['created_at']); ?></td>

          <td>

            <?php if ($row['status'] === 'active' && $row['role'] != 'admin'): ?>
              <a href="#" 
                 onclick="confirmAction(event, '?action=ban&id=<?= $row['id']; ?>', 'ban this user')" 
                 class="btn-delete">
                 <i class="bi bi-slash-circle"></i> Ban
              </a>
            <?php elseif ($row['status'] === 'banned' && $row['role'] != 'admin'): ?>
              <a href="#" 
                 onclick="confirmAction(event, '?action=unban&id=<?= $row['id']; ?>', 'unban this user')" 
                 class="btn-update">
                 <i class="bi bi-check-circle"></i> Unban
              </a>
            <?php endif; ?>


            <?php if ($row['role'] != 'admin'): ?>
            <?php
              $all_roles = ['user', 'supplier', 'tester', 'analyst'];
              $current   = $row['role'];

              foreach ($all_roles as $r) {
                  if ($r !== $current) {
                      echo '
                        <a href="#" 
                           onclick="confirmAction(event, \'?action='.$r.'&id='.$row['id'].'\', \'change role to '.ucfirst($r).'\')" 
                           class="btn-update">
                           <i class="bi bi-arrow-repeat"></i> '.ucfirst($r).'
                        </a>
                      ';
                  }
              }
            ?>

          </td>

  <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">No users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  // Check for URL params for success/error alerts
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const error = urlParams.get('error');

    if (msg) {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: msg,
        customClass: {
          popup: 'swal2-success'
        }
      }).then(() => {
        // Optional: Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    }

    if (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error,
        customClass: {
          popup: 'swal2-error'
        }
      });
    }
  });

  function confirmAction(event, url, actionText) {
    event.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You are about to " + actionText,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, proceed!',
      customClass: {
        popup: 'swal2-confirm',
        confirmButton: 'swal2-confirm',
        cancelButton: 'swal2-cancel'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
  }
</script>

</body>
</html>
