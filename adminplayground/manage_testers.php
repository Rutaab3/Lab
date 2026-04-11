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

// --- Handle admin actions (ban/unban/demote) ---
if (isset($_GET['action'], $_GET['id'])) {
  $testerId = intval($_GET['id']);
  $action   = $_GET['action'];

  // Fetch tester info for log
  $res = mysqli_query($conn, "SELECT username FROM users WHERE id=$testerId AND role='tester'");
  $tester = mysqli_fetch_assoc($res);

  if ($tester) {
    if ($action === 'ban') {
      mysqli_query($conn, "UPDATE users SET status='banned' WHERE id=$testerId");
      log_action($conn, "Tester Banned", ['target_user_id' => $testerId, 'target_username' => $tester['username']]);
      $msg = "Tester Banned";
    } elseif ($action === 'unban') {
      mysqli_query($conn, "UPDATE users SET status='active' WHERE id=$testerId");
      log_action($conn, "Tester Unbanned", ['target_user_id' => $testerId, 'target_username' => $tester['username']]);
      $msg = "Tester Unbanned";
    } elseif ($action === 'demote') {
      mysqli_query($conn, "UPDATE users SET role='user' WHERE id=$testerId");
      log_action($conn, "Tester Demoted", ['target_user_id' => $testerId, 'target_username' => $tester['username']]);
      $msg = "Tester Demoted";
    }
  }

  header("Location: manage_testers.php?msg=" . urlencode($msg));
  exit();
}


// --- Fetch testers ---
$testers = mysqli_query($conn, "SELECT id, username, email, profile_img, status, created_at FROM users WHERE role='tester' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Testers</title>
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

 <h1>Manage Testers</h1>
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Profile</th>
          <th>Status</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
   <?php if(mysqli_num_rows($testers) > 0): ?>
        <?php foreach ($testers as $t): ?>
          <tr>
            <td><i class="bi bi-hash text-primary"></i><?= $t['id']; ?></td>
          <td><i class="bi bi-person-circle text-primary"></i> <?= htmlspecialchars($t['username']); ?></td>
          <td><i class="bi bi-envelope text-primary"></i> <?= htmlspecialchars($t['email']); ?></td>
          <td class="rounded-circle">
            <img src="../<?= htmlspecialchars($t['profile_img']); ?>" width="80"  alt="User profile image">
          </td>
<td>
            <?php if ($t['status'] === 'banned'): ?>
              <span class="badge-user badge-banned"><i class="bi bi-person-x text-danger"></i> Banned</span>
            <?php else: ?>
              <span class="badge-user badge-active"><i class="bi bi-person-check text-success"></i> Active</span>
            <?php endif; ?>
          </td>
            <td><i class="bi bi-clock-history text-primary"></i> <?= htmlspecialchars($t['created_at']); ?></td>
            <td>
              <?php if ($t['status'] === 'active'): ?>
                <a href="#" onclick="confirmAction(event, '?action=ban&id=<?= $t['id'] ?>', 'ban this tester')" class="btn-delete">
                  <i class="bi bi-slash-circle"></i> Ban
                </a>
              <?php else: ?>
                <a href="#" onclick="confirmAction(event, '?action=unban&id=<?= $t['id'] ?>', 'unban this tester')" class="btn-update">
                  <i class="bi bi-check-circle"></i> Unban
                </a>
              <?php endif; ?>

              <a href="#" onclick="confirmAction(event, '?action=demote&id=<?= $t['id'] ?>', 'demote this tester to user')" class="btn-delete">
                <i class="bi bi-arrow-down-circle"></i> Demote
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php else:?>
          <tr>
            <td colspan="8" class="text-center">No testers found.</td>
          </tr>
          <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const error = urlParams.get('error');

    if (msg) {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: msg,
        customClass: { popup: 'swal2-success' }
      }).then(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    }
    if (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error,
        customClass: { popup: 'swal2-error' }
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
