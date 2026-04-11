<?php
include '../config/db.php';

// Get user activity statistics
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Active users (logged in last 30 days)
$activeUsers = $conn->query("SELECT COUNT(*) as count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['count'];

// Inactive users (not logged in last 30 days or never logged in)
$inactiveUsers = $totalUsers - $activeUsers;

// Get role distribution
$roleDistribution = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");

// Get recent users
$recentUsers = $conn->query("SELECT id, username, email, role, created_at, last_login FROM users ORDER BY created_at DESC LIMIT 50");

//CSV Export
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="user_activity_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Username', 'Email', 'Role', 'Registered', 'Last Login']);
    
    $allUsers = $conn->query("SELECT id, username, email, role, created_at, last_login FROM users");
    while ($row = $allUsers->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['username'],
            $row['email'],
            $row['role'],
            $row['created_at'],
            $row['last_login'] ?? 'Never'
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Activity Monitor</title>
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

* {margin: 0; padding: 0; box-sizing: border-box;}

body {
    font-family: 'Segoe UI', sans-serif;
    font-size: 13px;
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
    display: flex;
    align-items: center;
    gap: 10px;
}

h1:before {content: "📊"; font-size: 22px;}

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    padding: 20px;
    border-bottom: 1px solid var(--pma-border);
    background: var(--pma-bg-header);
}

.stat-card {
    background: white;
    border: 1px solid var(--pma-border);
    border-radius: 4px;
    padding: 15px;
    text-align: center;
}

.stat-card h3 {
    font-size: 12px;
    color: var(--pma-text-light);
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.stat-card .number {
    font-size: 28px;
    font-weight: 700;
    color: var(--pma-primary-dark);
}

.action-bar {
    padding: 15px 20px;
    border-bottom: 1px solid var(--pma-border);
    background: var(--pma-bg-header);
}

.btn {
    display: inline-block;
    padding: 8px 20px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    background: var(--pma-success);
    color: white;
    border-radius: 3px;
}

table {
    width: calc(100% - 40px);
    margin: 10px 20px 20px;
    border-collapse: collapse;
    border: 1px solid var(--pma-border);
    font-size: 13px;
}

table th {
    background: linear-gradient(to bottom, #F5F5F5, #E8E8E8);
    font-weight: 600;
    padding: 10px 12px;
    border-bottom: 1px solid var(--pma-border);
    text-align: left;
}

table tr {border-bottom: 1px solid var(--pma-border);}
table tr:nth-child(even) {background-color: var(--pma-bg-stripe);}
table tr:hover {background-color: var(--pma-bg-hover);}
table td {padding: 10px 12px;}

.badge {
    display: inline-block;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 3px;
    text-transform: uppercase;
}

.badge-active {background: #D4EDDA; color: #155724;}
.badge-inactive {background: #F8D7DA; color: #721C24;}
    </style>
</head>
<body>
    <div class="container">
        <h1>User Activity Monitor <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?= $totalUsers ?></div>
            </div>
            <div class="stat-card">
                <h3>Active (30 days)</h3>
                <div class="number"><?= $activeUsers ?></div>
            </div>
            <div class="stat-card">
                <h3>Inactive</h3>
                <div class="number"><?= $inactiveUsers ?></div>
            </div>
        </div>
        
        <div class="action-bar">
            <a href="?export=1" class="btn">📥 Export to CSV</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Last Login</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $recentUsers->fetch_assoc()): ?>
                <?php
                    $isActive = false;
                    if ($user['last_login']) {
                        $lastLogin = strtotime($user['last_login']);
                        $thirtyDaysAgo = strtotime('-30 days');
                        $isActive = $lastLogin > $thirtyDaysAgo;
                    }
                ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= strtoupper($user['role']) ?></td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                    <td><?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?></td>
                    <td>
                        <?php if ($isActive): ?>
                            <span class="badge badge-active">✓ Active</span>
                        <?php else: ?>
                            <span class="badge badge-inactive">○ Inactive</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
