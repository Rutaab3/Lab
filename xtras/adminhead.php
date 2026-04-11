<?php
include "../config/db.php";
include "../config/auth.php";
mysqli_report(MYSQLI_REPORT_OFF);

// Fetch unseen reports
$unseen_res = mysqli_query($conn, "SELECT * FROM test_reports WHERE seen_by_admin = 0 ORDER BY id DESC");
$unseen_count = mysqli_num_rows($unseen_res);

// Fetch current user from DB
$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id) {
    $res = mysqli_query($conn, "SELECT username, profile_img, email FROM users WHERE id = $user_id LIMIT 1");
    $user = mysqli_fetch_assoc($res);
}
?>

<link rel="stylesheet" href="../css/board.css">
<?php include 'translate_init.php'; ?>


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

            <li><a href="../dashboard/admin.php" title="Dashboard"><i class="bi bi-motherboard me-2"></i>Dashboard</a></li>

            <!-- Products -->
            <li><a href="../products/list_products.php" title="Products List"><i class="bi bi-box-seam me-2"></i>Products List</a></li>


            <!-- Reports -->
            <li><a href="../reports/list_reports.php" title="Reports List"><i class="bi bi-file-earmark-text me-2"></i>Reports List</a></li>

            <!-- Users -->
            <li>
                <a href="#productssubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" title="Manage Users">
                    <i class="bi bi-person me-2"></i>Manage Users
                </a>
                <ul class="collapse list-unstyled" id="productssubmenu">
                  <li><a href="../adminplayground/list_users.php" title="Users"><i class="bi bi-people"></i> Users</a></li>
                  <li><a href="../adminplayground/manage_testers.php" title="Tester"><i class="bi bi-flask"></i> Tester</a></li>
                  <li><a href="../adminplayground/manage_analysts.php" title="Analysts"><i class="bi bi-bar-chart"></i> Analysts</a></li>
                  <li><a href="../adminplayground/manage_suppliers.php" title="Suppliers"><i class="bi bi-box"></i> Suppliers</a></li>
                </ul>
            </li>

            <li><a href="../users/settings.php" title="Profile"><i class="bi bi-gear me-2"></i>Profile</a></li>
            <li><a href="../users/settings.php" title="Settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><a href="../users/logout.php" title="Logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            <li><a id="sidebarCollapsex" href="#" title="Close"><i class="bi bi-x-square me-2"></i>Close</a></li>
        </ul>


    </nav>
    
    
    <div id="content">
           <nav class="navbar navbar-expand-lg align-items-center">
            <div class="container-fluid">
                <button type="button" title="Sidebar Collapse" id="sidebarCollapse" class="btn btn-toggle"  aria-expanded="false">
                <i class="bi bi-list"></i>
                </button>
                <button class="navbar-toggler sidebarCollapse btn btn-toggle" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
                </button>
            </div>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center align-items-center">
        <li class="nav-item">
<button id="notifBell" class="btn position-relative" title="notification" data-bs-toggle="modal" data-bs-target="#notifModal">
    <i class="bi bi-bell-fill fs-4"></i>
    <?php if ($unseen_count > 0): ?>
        <span id="notifBadge">
            <?= $unseen_count ?>
        </span>
    <?php endif; ?>
</button>
        </li>
        
         <!-- Admin Badge -->
        <li class="nav-item">       

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


<?php include 'modaladmin.php'?>
