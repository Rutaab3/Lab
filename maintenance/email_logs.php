<?php
include '../config/db.php';

// Create email_logs table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email_type VARCHAR(50) NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    error_message TEXT DEFAULT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_type (email_type),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($createTableSQL);

// Get filters
$emailType = $_GET['email_type'] ?? 'all';
$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$whereClause = " WHERE 1=1";
if ($emailType != 'all') {
    $whereClause .= " AND email_type = '" . $conn->real_escape_string($emailType) . "'";
}
if ($status != 'all') {
    $whereClause .= " AND status = '" . $conn->real_escape_string($status) . "'";
}
if (!empty($search)) {
    $searchTerm = $conn->real_escape_string($search);
    $whereClause .= " AND (recipient LIKE '%$searchTerm%' OR subject LIKE '%$searchTerm%')";
}

$logs = $conn->query("SELECT * FROM email_logs $whereClause ORDER BY sent_at DESC LIMIT 100");

// Get statistics
$stats = [
    'total' => $conn->query("SELECT COUNT(*) as count FROM email_logs")->fetch_assoc()['count'],
    'success' => $conn->query("SELECT COUNT(*) as count FROM email_logs WHERE status = 'success'")->fetch_assoc()['count'],
    'failed' => $conn->query("SELECT COUNT(*) as count FROM email_logs WHERE status = 'failed'")->fetch_assoc()['count'],
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Logs Viewer</title>
    <style>
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
body {font-family: 'Segoe UI', sans-serif; font-size: 13px; color: var(--pma-text); background: var(--pma-bg); padding: 20px;}
.container {max-width: 1600px; margin: 0 auto; background: white; border: 1px solid var(--pma-border); border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);}
h1 {font-size: 20px; font-weight: 600; color: white; background: linear-gradient(to bottom, var(--pma-primary), var(--pma-primary-dark)); padding: 15px 20px; display: flex; align-items: center; gap: 10px;}
/* h1:before {content: "📧"; font-size: 22px;} */
.stats {display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; padding: 20px; border-bottom: 1px solid var(--pma-border); background: var(--pma-bg-header);}
.stat-card {background: white; border: 1px solid var(--pma-border); border-radius: 4px; padding: 15px; text-align: center;}
.stat-card h3 {font-size: 12px; color: var(--pma-text-light); font-weight: 600; margin-bottom: 8px; text-transform: uppercase;}
.stat-card .number {font-size: 28px; font-weight: 700; color: var(--pma-primary-dark);}
.filters {display: flex; gap: 10px; padding: 15px 20px; background: var(--pma-bg-header); border-bottom: 1px solid var(--pma-border); flex-wrap: wrap;}
select, input {padding: 6px 10px; border: 1px solid var(--pma-border); border-radius: 3px; font-size: 13px;}
.btn {padding: 6px 16px; background: var(--pma-primary); border: none; color: white; border-radius: 3px; font-weight: 600; cursor: pointer;}
table {width: calc(100% - 40px); margin: 10px 20px 20px; border-collapse: collapse; border: 1px solid var(--pma-border); font-size: 13px;}
table th {background: linear-gradient(to bottom, #F5F5F5, #E8E8E8); font-weight: 600; padding: 10px 12px; border-bottom: 1px solid var(--pma-border); text-align: left;}
table tr {border-bottom: 1px solid var(--pma-border);}
table tr:nth-child(even) {background-color: var(--pma-bg-stripe);}
table tr:hover {background-color: var(--pma-bg-hover);}
table td {padding: 10px 12px;}
.badge {display: inline-block; padding: 3px 8px; font-size: 11px; font-weight: 600; border-radius: 3px; text-transform: uppercase;}
.badge-success {background: #D4EDDA; color: #155724;}
.badge-failed {background: #F8D7DA; color: #721C24;}
.empty-state {text-align: center; padding: 60px 20px; color: var(--pma-text-light);}
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Logs Viewer <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Emails</h3>
                <div class="number"><?= $stats['total'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Successful</h3>
                <div class="number"><?= $stats['success'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Failed</h3>
                <div class="number"><?= $stats['failed'] ?></div>
            </div>
        </div>
        
        <form class="filters" method="get">
            <select name="email_type">
                <option value="all">All Types</option>
                <option value="welcome" <?= $emailType == 'welcome' ? 'selected' : '' ?>>Welcome</option>
                <option value="password_reset" <?= $emailType == 'password_reset' ? 'selected' : '' ?>>Password Reset</option>
                <option value="notification" <?= $emailType == 'notification' ? 'selected' : '' ?>>Notification</option>
            </select>
            
            <select name="status">
                <option value="all">All Status</option>
                <option value="success" <?= $status == 'success' ? 'selected' : '' ?>>Success</option>
                <option value="failed" <?= $status == 'failed' ? 'selected' : '' ?>>Failed</option>
            </select>
            
            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" style="width: 250px;">
            <button type="submit" class="btn">Apply Filters</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Recipient</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($logs && $logs->num_rows > 0): ?>
                    <?php while ($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $log['id'] ?></td>
                        <td><?= strtoupper(str_replace('_', ' ', $log['email_type'])) ?></td>
                        <td><?= htmlspecialchars($log['recipient']) ?></td>
                        <td><?= htmlspecialchars($log['subject']) ?></td>
                        <td><span class="badge badge-<?= $log['status'] ?>"><?= strtoupper($log['status']) ?></span></td>
                        <td><?= date('M j, Y g:i A', strtotime($log['sent_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <!-- <div style="font-size: 40px; margin-bottom: 10px;">📭</div> -->
                            <strong>No email logs found</strong>
                            <p style="margin-top: 5px;">Email logs will appear here when emails are sent from the system.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
