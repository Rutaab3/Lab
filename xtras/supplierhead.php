<?php 
include '../config/db.php';
include '../config/auth.php';
mysqli_report(MYSQLI_REPORT_OFF);

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

</style>


<nav id="sidebar">


        <div class="sidebar-header">
        <h3></i><img class="logo" src="../images/logo22.png" alt=""></h3>
        </div>

        <ul class="list-unstyled components">

        <li>
            <a href="../dashboard/supplier.php" title="Dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        </li>

            <!-- Products -->
   <li>
                <a href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" title="Products">
                    <i class="bi bi-box-seam me-2"></i>Products
                </a>
                <ul class="collapse list-unstyled" id="productsSubmenu">
                    <li><a href="../products/add_product.php" title="Add Product"><i class="bi bi-exposure"></i> Add</a></li>
                    <li><a href="../products/list_products.php" title="Products List"><i class="bi bi-file-earmark-break"></i> List</a></li>
                </ul>
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
                <button type="button" id="sidebarCollapse" class="btn btn-toggle"  aria-expanded="false">
                <i class="bi bi-list"></i>
                </button>
                          
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <!-- <li class="nav-item">
<button id="notifBell" class="btn position-relative" title="notification" data-bs-toggle="modal" data-bs-target="#notifModal">
    <i class="bi bi-bell-fill fs-4"></i>
    <?php if ($unseen_count > 0): ?>
        <span id="notifBadge">
            <?= $unseen_count ?>
        </span>
    <?php endif; ?>
</button>
        </li> -->
        <li class="mb-0">        <!-- Admin Badge -->
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
