<?php
include '../config/db.php';

// Simple database cleaner - finds potential issues
$issues = [];

// Find users with invalid roles (if your system uses specific roles)
$invalidRoles = $conn->query("SELECT id, username, role FROM users WHERE role NOT IN ('admin', 'user', 'tester', 'viewer')");
if ($invalidRoles && $invalidRoles->num_rows > 0) {
    $issues[] = ['type' => 'Invalid Roles', 'count' => $invalidRoles->num_rows, 'query' => 'users with invalid roles'];
}

// Find duplicate emails (if any)
$duplicateEmails = $conn->query("SELECT email, COUNT(*) as count FROM users GROUP BY email HAVING count > 1");
if ($duplicateEmails && $duplicateEmails->num_rows > 0) {
    $issues[] = ['type' => 'Duplicate Emails', 'count' => $duplicateEmails->num_rows, 'query' => 'duplicate email addresses'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Cleaner</title>
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
        .container {max-width: 1400px; margin: 0 auto; background: white; border: 1px solid var(--pma-border); border-radius: 4px;}
        h1 {font-size: 20px; font-weight: 600; color: white; background: linear-gradient(to bottom, var(--pma-primary), #2B6A94); padding: 15px 20px; display: flex; align-items: center; gap: 10px;}
        h1:before {content: "🧹"; font-size: 22px;}
        .warning {background: #FFF3CD; border: 1px solid #FFEEBA; padding: 15px 20px; margin: 20px; border-radius: 4px; color: #856404;}
        table {width: calc(100% - 40px); margin: 10px 20px 20px; border-collapse: collapse; border: 1px solid var(--pma-border); font-size: 13px;}
        table th {background: linear-gradient(to bottom, #F5F5F5, #E8E8E8); font-weight: 600; padding: 10px 12px; border-bottom: 1px solid var(--pma-border); text-align: left;}
        table tr {border-bottom: 1px solid var(--pma-border);}
        table tr:hover {background-color: #E6F2F9;}
        table td {padding: 10px 12px;}
        .empty {text-align: center; padding: 40px; color: #999;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Cleaner <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <div class="warning">
            ⚠️ <strong>Warning:</strong> This tool identifies potential database cleanup issues. Always backup your database before performing cleanup operations.
        </div>
        
        <?php if (count($issues) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Issue Type</th>
                    <th>Count</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($issues as $issue): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($issue['type']) ?></strong></td>
                    <td><?= $issue['count'] ?></td>
                    <td><?= htmlspecialchars($issue['query']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty">
            <div style="font-size: 40px; margin-bottom: 10px;">✅</div>
            <strong>No issues found</strong>
            <p style="margin-top: 5px;">Your database appears to be clean!</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
