<link rel="stylesheet" href="../css/board.css">
<?php include 'translate_init.php'; ?>

<?php
include "../config/db.php";
include "../config/auth.php";
mysqli_report(MYSQLI_REPORT_OFF);

// Fetch unseen reports
$unseen_res = mysqli_query($conn, "SELECT * FROM test_reports WHERE seen_by_analyst = 0 ORDER BY id DESC");
$unseen_count = mysqli_num_rows($unseen_res);

// Fetch current user from DB
$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id) {
    $res = mysqli_query($conn, "SELECT username, profile_img, email FROM users WHERE id = $user_id LIMIT 1");
    $user = mysqli_fetch_assoc($res);
}
?>


<style>
.logo{
    height: 60px;
}

#notifBadge {
    position: absolute;
    top: -1px;
    right: 7px;
    background-color: #dc3545; /* Red color for notifications */
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.75rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white; /* Creates a nice border effect */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); /* Subtle shadow */
    line-height: 1;
}
.bi-bell-fill{
    color: #6366f1 !important; 
}
</style>


<nav id="sidebar">

        <div class="sidebar-header">
        <h3></i><img class="logo" src="../images/logo22.png" alt=""></h3>
        </div>

        <ul class="list-unstyled components">

        <li>
            <a href="../dashboard/analyst.php" title="Dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        </li>

            <!-- Products -->
            <li>
            <a href="../products/list_products.php" title="Products"><i class="bi bi-box-seam me-2"></i>Products</a>
            </li>

            <!-- Reports -->

            <li>
                <a href="../reports/list_reports.php" title="Reports"><i class="bi bi-file-earmark-text me-2"></i>Reports</a>
            </li>


            <li><a href="../users/profile.php" title="Profile"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
            <li><a href="../users/settings.php" title="Settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><a href="../users/logout.php" title="Logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            <li><a id="sidebarCollapsex" href="#" title="Close"><i class="bi bi-x-square me-2"></i>Close</a></li>
           </ul>


    </nav>
    
    <div id="content">
           <nav class="navbar navbar-expand-lg align-items-center">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" title="Collapse Sidebar" class="btn btn-toggle"  aria-expanded="false">
                <i class="bi bi-list"></i>
                </button>
                <button class="navbar-toggler sidebarCollapse btn btn-toggle" title="links" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
                </button>
            </div>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center align-items-center">
        <li class="nav-item">
<button id="notifBellanalyst" class="btn position-relative" title="notification" data-bs-toggle="modal" data-bs-target="#notifModal">
    <i class="bi bi-bell-fill fs-4"></i>
    <?php if ($unseen_count > 0): ?>
        <span id="notifBadge">
            <?= $unseen_count ?>
        </span>
    <?php endif; ?>
</button>
        </li>

        <!-- Admin Badge -->
        <li>        
  <div class="admin-badge">
    <img src="../<?= htmlspecialchars($user['profile_img'] ?? 'adm.png'); ?>" 
         alt="<?= htmlspecialchars($user['username'] ?? 'User'); ?> " 
         class="admin-avatar"
         title="<?= htmlspecialchars(($user['username'] ?? 'User') . "\n" . ($user['email'] ?? 'User@gmail.com')); ?>">
  </div>
</li>
    </ul>
</div>
        </nav>



        <style>
  .modal-body .list-group li a {
    background-color: #a2a4ffff;
  }

  #notifModal .modal-dialog-end {
    margin-right: 0;
    margin-top: 0;
    height: 100%;
}
</style>
<!-- Modal -->
<div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notifModalLabel">New Test Reports</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($unseen_count > 0): ?>
            <ul class="list-group">
                <?php while($report = mysqli_fetch_assoc($unseen_res)): ?>
                    <a href="../reports/list_reports.php">
                      <li class="list-group-item">
                        <span class="fw-bold">Report ID:</span> <?= htmlspecialchars($report['test_id']) ?> | <span class="fw-bold">Result:</span> <?= $report['results'] ?>
                    </li>
                    </a>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-muted">No new reports</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include 'modalanalyst.php'?>

<script>
// When modal opens, mark reports as seen via AJAX
document.getElementById('notifModal').addEventListener('show.bs.modal', function () {
    fetch('../adminplayground/mark_seen_analyst.php', { method: 'POST' })
        .then(() => {
            const badge = document.getElementById('notifBadge');
            if(badge) badge.remove(); // remove the badge instantly
        });
});
</script>