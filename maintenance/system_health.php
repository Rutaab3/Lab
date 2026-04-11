<?php
// System Health Dashboard
include '../config/db.php';

// Test database connection
$dbStatus = $conn->ping() ? 'Connected' : 'Failed';

// Get PHP version and extensions
$phpVersion = phpversion();
$loadedExtensions = get_loaded_extensions();

// Check disk space
$diskFreeSpace = disk_free_space('.');
$diskTotalSpace = disk_total_space('.');
$diskUsedPercent = (($diskTotalSpace - $diskFreeSpace) / $diskTotalSpace) * 100;

// Check directory permissions
$directories = [
    '../uploads' => is_writable('../uploads'),
    '../uploads/users' => is_writable('../uploads/users'),
    '../uploads/products' => is_writable('../uploads/products'),
    '../config' => is_readable('../config'),
];

// Check mail configuration
$mailConfig = @file_exists('../config/settings.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Health Dashboard</title>
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
        h1:before {content: "💊"; font-size: 22px;}
        h2 {font-size: 16px; padding: 12px 20px; background: #F9F9F9; border-bottom: 1px solid var(--pma-border); margin-top: 20px;}
        .status-grid {display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; padding: 20px;}
        .status-card {border: 1px solid var(--pma-border); border-radius: 4px; padding: 15px; background: #FAFAFA;}
        .status-card h3 {font-size: 14px; margin-bottom: 10px; color: #555;}
        .status-value {font-size: 18px; font-weight: 700; margin-top: 5px;}
        .status-ok {color: var(--pma-success);}
        .status-error {color: var(--pma-danger);}
        table {width: calc(100% - 40px); margin: 10px 20px 20px; border-collapse: collapse; border: 1px solid var(--pma-border); font-size: 13px;}
        table th {background: linear-gradient(to bottom, #F5F5F5, #E8E8E8); font-weight: 600; padding: 10px 12px; border-bottom: 1px solid var(--pma-border); text-align: left;}
        table tr {border-bottom: 1px solid var(--pma-border);}
        table tr:hover {background-color: var(--pma-bg-hover);}
        table td {padding: 10px 12px;}
        .badge {display: inline-block; padding: 3px 8px; font-size: 11px; font-weight: 600; border-radius: 3px;}
        .badge-ok {background: #D4EDDA; color: #155724;}
        .badge-error {background: #F8D7DA; color: #721C24;}
    </style>
</head>
<body>
    <div class="container">
        <h1>System Health Dashboard <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <div class="status-grid">
            <div class="status-card">
                <h3>Database Connection</h3>
                <div class="status-value <?= $dbStatus == 'Connected' ? 'status-ok' : 'status-error' ?>">
                    <?= $dbStatus == 'Connected' ? '✓' : '✗' ?> <?= $dbStatus ?>
                </div>
            </div>
            
            <div class="status-card">
                <h3>PHP Version</h3>
                <div class="status-value"><?= $phpVersion ?></div>
            </div>
            
            <div class="status-card">
                <h3>Disk Usage</h3>
                <div class="status-value">
                    <?= round($diskUsedPercent, 1) ?>% Used
                    <div style="font-size: 11px; color: #666; margin-top: 5px;">
                        <?= round($diskFreeSpace / 1024 / 1024 / 1024, 2) ?> GB Free
                    </div>
                </div>
            </div>
            
            <div class="status-card">
                <h3>Mail Configuration</h3>
                <div class="status-value <?= $mailConfig ? 'status-ok' : 'status-error' ?>">
                    <?= $mailConfig ? '✓ Configured' : '✗ Not Found' ?>
                </div>
            </div>
        </div>
        
        <h2>Directory Permissions</h2>
        <table>
            <thead>
                <tr>
                    <th>Directory</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($directories as $dir => $writable): ?>
                <tr>
                    <td><?= htmlspecialchars($dir) ?></td>
                    <td>
                        <?php if ($writable): ?>
                            <span class="badge badge-ok">✓ Writable</span>
                        <?php else: ?>
                            <span class="badge badge-error">✗ Not Writable</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Loaded PHP Extensions (Top 20)</h2>
        <table>
            <thead>
                <tr>
                    <th>Extension Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($loadedExtensions, 0, 20) as $ext): ?>
                <tr>
                    <td><?= htmlspecialchars($ext) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
