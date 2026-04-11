<?php
include '../config/db.php';

// Simple test report analytics
$totalReports = $conn->query("SELECT COUNT(*) as count FROM test_reports")->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Report Analytics</title>
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
        h1:before {content: "📊"; font-size: 22px;}
        .stats {padding: 40px 20px; text-align: center;}
        .stat-number {font-size: 48px; font-weight: 700; color: #2B6A94;}
        .stat-label {font-size: 16px; color: #666; margin-top: 10px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Report Analytics <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        <div class="stats">
            <div class="stat-number"><?= number_format($totalReports) ?></div>
            <div class="stat-label">Total Test Reports</div>
        </div>
    </div>
</body>
</html>
