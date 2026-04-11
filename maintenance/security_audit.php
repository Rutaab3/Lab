<?php
include '../config/db.php';

// Create security_logs table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS security_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    user_id INT DEFAULT NULL,
    username VARCHAR(100) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    details TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($createTableSQL);

// Get filters
$eventType = $_GET['event_type'] ?? 'all';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$whereClause = " WHERE 1=1";
if ($eventType != 'all') {
    $whereClause .= " AND event_type = '" . $conn->real_escape_string($eventType) . "'";
}
if (!empty($dateFrom)) {
    $whereClause .= " AND DATE(created_at) >= '" . $conn->real_escape_string($dateFrom) . "'";
}
if (!empty($dateTo)) {
    $whereClause .= " AND DATE(created_at) <= '" . $conn->real_escape_string($dateTo) . "'";
}
if (!empty($search)) {
    $searchTerm = $conn->real_escape_string($search);
    $whereClause .= " AND (username LIKE '%$searchTerm%' OR ip_address LIKE '%$searchTerm%' OR details LIKE '%$searchTerm%')";
}

$logs = $conn->query("SELECT * FROM security_logs $whereClause ORDER BY created_at DESC LIMIT 100");

// Get statistics
$stats = [
    'total' => $conn->query("SELECT COUNT(*) as count FROM security_logs")->fetch_assoc()['count'],
    'login_success' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'login_success'")->fetch_assoc()['count'],
    'failed_login' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'failed_login'")->fetch_assoc()['count'],
    'user_registration' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'user_registration'")->fetch_assoc()['count'],
    'registration_failed' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'registration_failed'")->fetch_assoc()['count'],
    'password_reset' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'password_reset'")->fetch_assoc()['count'],
    'admin_action' => $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE event_type = 'admin_action'")->fetch_assoc()['count'],
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Security Audit Log</title>
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
    /* content: "🔒"; */
    font-size: 22px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
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
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.stat-card h3 {
    font-size: 12px;
    color: var(--pma-text-light);
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.stat-card .number {
    font-size: 26px;
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

.filter-group {
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: var(--pma-text-light);
}

.filter-group select,
.filter-group input {
    padding: 6px 10px;
    border: 1px solid var(--pma-border);
    border-radius: 3px;
    font-size: 13px;
}

.btn-filter {
    padding: 6px 16px;
    background: var(--pma-primary);
    border: 1px solid var(--pma-primary-dark);
    border-radius: 3px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
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

.badge-failed-login {
    background-color: #F8D7DA;
    color: #721C24;
    border-color: #F5C6CB;
}

.badge-registration-failed {
    background-color: #F8D7DA;
    color: #721C24;
    border-color: #F5C6CB;
}

.badge-password-reset {
    background-color: #FFF3CD;
    color: #856404;
    border-color: #FFEEBA;
}

.badge-admin-action {
    background-color: #D1ECF1;
    color: #0C5460;
    border-color: #BEE5EB;
}

.badge-login-success {
    background-color: #D4EDDA;
    color: #155724;
    border-color: #C3E6CB;
}

.badge-user-registration {
    background-color: #CCE5FF;
    color: #004085;
    border-color: #B8DAFF;
}

.badge-suspicious {
    background-color: #F8D7DA;
    color: #721C24;
    border-color: #F5C6CB;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--pma-text-light);
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Security Audit Log <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Events</h3>
                <div class="number"><?= number_format($stats['total']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Successful Logins</h3>
                <div class="number"><?= number_format($stats['login_success']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Failed Logins</h3>
                <div class="number"><?= number_format($stats['failed_login']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Registrations</h3>
                <div class="number"><?= number_format($stats['user_registration']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Failed Registrations</h3>
                <div class="number"><?= number_format($stats['registration_failed']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Password Resets</h3>
                <div class="number"><?= number_format($stats['password_reset']) ?></div>
            </div>
            <div class="stat-card">
                <h3>Admin Actions</h3>
                <div class="number"><?= number_format($stats['admin_action']) ?></div>
            </div>
        </div>
        
        <!-- Filters -->
        <form class="filters" method="get">
            <div class="filter-group">
                <label>Event:</label>
                <select name="event_type">
                    <option value="all" <?= $eventType == 'all' ? 'selected' : '' ?>>All Events</option>
                    <option value="login_success" <?= $eventType == 'login_success' ? 'selected' : '' ?>>Successful Login</option>
                    <option value="failed_login" <?= $eventType == 'failed_login' ? 'selected' : '' ?>>Failed Login</option>
                    <option value="user_registration" <?= $eventType == 'user_registration' ? 'selected' : '' ?>>User Registration</option>
                    <option value="registration_failed" <?= $eventType == 'registration_failed' ? 'selected' : '' ?>>Failed Registration</option>
                    <option value="password_reset" <?= $eventType == 'password_reset' ? 'selected' : '' ?>>Password Reset</option>
                    <option value="admin_action" <?= $eventType == 'admin_action' ? 'selected' : '' ?>>Admin Action</option>
                    <option value="suspicious" <?= $eventType == 'suspicious' ? 'selected' : '' ?>>Suspicious Activity</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>From:</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>">
            </div>
            
            <div class="filter-group">
                <label>To:</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>">
            </div>
            
            <div class="filter-group">
                <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" style="width: 200px;">
            </div>
            
            <button type="submit" class="btn-filter">Apply Filters</button>
        </form>
        
        <!-- Logs Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Type</th>
                    <th>Username</th>
                    <th>IP Address</th>
                    <th>Details</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($logs && $logs->num_rows > 0): ?>
                    <?php while ($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $log['id'] ?></td>
                        <td><span class="badge badge-<?= $log['event_type'] ?>"><?= strtoupper(str_replace('_', ' ', $log['event_type'])) ?></span></td>
                        <td><?= htmlspecialchars($log['username'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($log['details']) ?></td>
                        <td><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <!-- <div style="font-size: 40px; margin-bottom: 10px;">🔒</div> -->
                            <strong>No security events found</strong>
                            <p style="margin-top: 5px; font-size: 12px;">Security events will appear here when they occur.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
