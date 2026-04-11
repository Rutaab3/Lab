<?php
include '../config/db.php';
// session_start();
include '../config/security_logger.php';
include '../config/action_logger.php';

// Security Check: Only Admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Log unauthorized attempt
    log_security_event($conn, 'suspicious', null, null, 'Unauthorized access attempt to User Roles Manager');
    die("<div style='color:red; text-align:center; padding:50px; font-family:sans-serif;'>
            <h1>🚫 Access Denied</h1>
            <p>You do not have permission to view this page.</p>
            <a href='../index.php'>Go Home</a>
         </div>");
}

// Handle role change
$message = '';
if (isset($_POST['change_role'])) {
    $userId = (int)$_POST['user_id'];
    $newRole = $_POST['new_role'];
    
    if (in_array($newRole, ['admin', 'user', 'tester', 'supplier', 'analyst'])) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        
        if ($stmt->execute()) {
            // Log admin action (Security)
            $adminUsername = $_SESSION['username'] ?? 'admin';
            log_security_event($conn, 'admin_action', $userId, $adminUsername, "Role changed to $newRole for user ID $userId");
            
            // Log action (Activity)
            log_action($conn, "User Role Updated", [
                'target_user_id' => $userId,
                'new_role'       => $newRole
            ]);
            
            $message = "<div class='alert alert-success'>Role updated successfully!</div>";
        } else {
            $message = "<div class='alert alert-error'>Failed to update role.</div>";
        }
    }
}



// Get filter
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$whereClause = '';
if ($filter != 'all') {
    $whereClause = " WHERE role = '" . $conn->real_escape_string($filter) . "'";
}

if (!empty($search)) {
    $searchTerm = $conn->real_escape_string($search);
    $whereClause .= ($whereClause ? " AND" : " WHERE") . " (username LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%')";
}

$users = $conn->query("SELECT id, username, email, role, created_at FROM users $whereClause ORDER BY created_at DESC");

// Get statistics
$stats = [
    'total' => $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    'admin' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch_assoc()['count'],
    'user' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc()['count'],
    'tester' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'tester'")->fetch_assoc()['count'],
    'supplier' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'supplier'")->fetch_assoc()['count'],
    'analyst' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'analyst'")->fetch_assoc()['count'],
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Role Manager</title>
    <style>
        /* phpMyAdmin Inspired UI */
:root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f8fafc;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.2s ease-out;
            --pma-bg: #f0f4f8;
            --pma-bg-hover: #e6f2f9;
            --pma-border: #e2e8f0;
            --pma-success: #10b981;
            --pma-warning: #f59e0b;
            --pma-danger: #ef4444;
            --pma-primary: #6366f1;
            --pma-primary-dark: #4f46e5;
            --pma-text: #1e293b;
            --pma-text-light: #64748b;
        }

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', 'Source Sans Pro', 'Ubuntu', 'DejaVu Sans', sans-serif;
    font-size: 13px;
    line-height: 1.4;
    color: var(--pma-text);
    background: var(--pma-bg);
    padding: 20px;
}

.container {
    max-width: 1600px;
    margin: 0 auto;
    background: white;
    border: 1px solid var(--pma-border);
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

h1 {
    font-size: 20px;
    font-weight: 600;
    color: white;
    background: linear-gradient(to bottom, var(--pma-primary), var(--pma-primary-dark));
    padding: 15px 20px;
    margin: 0;
    border-bottom: 1px solid var(--pma-primary-dark);
    text-shadow: 0 1px 1px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
gap: 10px;
}

h1:before {
    content: "👥";
    font-size: 22px;
}

.alert {
    padding: 12px 16px;
    margin: 15px 20px;
    border-radius: 3px;
    border: 1px solid;
    font-size: 13px;
}

.alert-success {
    background-color: #D4EDDA;
    border-color: #C3E6CB;
    color: #155724;
}

.alert-error {
    background-color: #F8D7DA;
    border-color: #F5C6CB;
    color: #721C24;
}

.stats {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
    padding: 20px;
    border-bottom: 1px solid var(--pma-border);
    background: var(--pma-bg-header);
}

.stat-card {
    background: white;
    border: 1px solid var(--pma-border);
    border-radius: 4px;
    padding: 12px;
    text-align: center;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    border-top: 3px solid var(--pma-primary);
}

.stat-card h3 {
    font-size: 11px;
    color: var(--pma-text-light);
    font-weight: 600;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.stat-card .number {
    font-size: 24px;
    font-weight: 700;
    color: var(--pma-primary-dark);
}

.filters {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: var(--pma-bg-header);
    border-bottom: 1px solid var(--pma-border);
    flex-wrap: wrap;
}

.filter-btn {
    padding: 6px 16px;
    background: white;
    border: 1px solid var(--pma-border);
    border-radius: 3px;
    color: var(--pma-text);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}

.filter-btn:hover {
    background: var(--pma-bg-hover);
}

.filter-btn.active {
    background: var(--pma-primary);
    color: white;
    border-color: var(--pma-primary);
    font-weight: 600;
}

.search-box {
    margin-left: auto;
    min-width: 300px;
}

.search-box input {
    width: 100%;
    padding: 6px 12px;
    border: 1px solid var(--pma-border);
    border-radius: 3px;
    font-size: 13px;
}

table {
    width: calc(100% - 40px);
    margin: 10px 20px 20px 20px;
    border-collapse: collapse;
    border: 1px solid var(--pma-border);
    border-radius: 3px;
    overflow: hidden;
    background: white;
    font-size: 13px;
}

table th {
    background: linear-gradient(to bottom, #F5F5F5, #E8E8E8);
    color: var(--pma-text);
    font-weight: 600;
    padding: 10px 12px;
    border-bottom: 1px solid var(--pma-border);
    text-align: left;
}

table tr {
    border-bottom: 1px solid var(--pma-border);
    transition: background-color 0.1s ease;
}

table tr:nth-child(even) {
    background-color: var(--pma-bg-stripe);
}

table tr:hover {
    background-color: var(--pma-bg-hover);
}

table td {
    padding: 10px 12px;
    vertical-align: middle;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    border-radius: 3px;
    border: 1px solid transparent;
    text-transform: uppercase;
}

.badge-admin {
    background-color: #F8D7DA;
    color: #721C24;
    border-color: #F5C6CB;
}

.badge-user {
    background-color: #D1ECF1;
    color: #0C5460;
    border-color: #BEE5EB;
}

.badge-tester {
    background-color: #FFF3CD;
    color: #856404;
    border-color: #FFEEBA;
}

.badge-supplier {
    background-color: #E2E3E5;
    color: #383d41;
    border-color: #d6d8db;
}

.badge-analyst {
    background-color: #c3e6cb;
    color: #155724;
    border-color: #b1dfbb;
}

.badge-banned {
    background-color: #F8D7DA;
    color: #721C24;
    border-color: #F5C6CB;
}

.badge-active {
    background-color: #D4EDDA;
    color: #155724;
    border-color: #C3E6CB;
}

select, .btn {
    padding: 5px 12px;
    font-size: 13px;
    border: 1px solid var(--pma-border);
    border-radius: 3px;
    cursor: pointer;
}

.btn-primary {
    background: var(--pma-primary);
    color: white;
    border-color: var(--pma-primary);
}

.btn-danger {
    background: var(--pma-danger);
    color: white;
    border-color: var(--pma-danger);
}

.btn-success {
    background: var(--pma-success);
    color: white;
    border-color: var(--pma-success);
}

.action-cell {
    display: flex;
    gap: 5px;
    align-items: center;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>User Role Manager <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <?= $message ?>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total</h3>
                <div class="number"><?= $stats['total'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Admin</h3>
                <div class="number"><?= $stats['admin'] ?></div>
            </div>
            <div class="stat-card">
                <h3>User</h3>
                <div class="number"><?= $stats['user'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Tester</h3>
                <div class="number"><?= $stats['tester'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Supplier</h3>
                <div class="number"><?= $stats['supplier'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Analyst</h3>
                <div class="number"><?= $stats['analyst'] ?></div>
            </div>

        </div>
        
        <!-- Filters -->
        <div class="filters">
            <a href="?filter=all" class="filter-btn <?= $filter == 'all' ? 'active' : '' ?>">All Users</a>
            <a href="?filter=admin" class="filter-btn <?= $filter == 'admin' ? 'active' : '' ?>">Admin</a>
            <a href="?filter=user" class="filter-btn <?= $filter == 'user' ? 'active' : '' ?>">User</a>
            <a href="?filter=tester" class="filter-btn <?= $filter == 'tester' ? 'active' : '' ?>">Tester</a>
            <a href="?filter=supplier" class="filter-btn <?= $filter == 'supplier' ? 'active' : '' ?>">Supplier</a>
            <a href="?filter=analyst" class="filter-btn <?= $filter == 'analyst' ? 'active' : '' ?>">Analyst</a>
            
            <form class="search-box" method="get">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <input type="text" name="search" placeholder="🔍 Search..." value="<?= htmlspecialchars($search) ?>">
            </form>
        </div>
        
        <!-- Users Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>

                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge badge-<?= $user['role'] ?>"><?= strtoupper($user['role']) ?></span></td>

                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <div class="action-cell">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <select name="new_role" onchange="this.form.submit()">
                                        <option value="">Change Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                        <option value="tester">Tester</option>
                                        <option value="supplier">Supplier</option>
                                        <option value="analyst">Analyst</option>
                                    </select>
                                    <input type="hidden" name="change_role" value="1">
                                </form>

                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
