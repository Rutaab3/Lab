<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Utilities - Lab Automation</title>
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Source Sans Pro', 'Ubuntu', sans-serif;
            font-size: 14px;
            color: var(--pma-text);
            background: var(--pma-bg);
            padding: 30px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border: 1px solid var(--pma-border);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(to bottom, var(--pma-primary), var(--pma-primary-dark));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .utilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 30px;
        }
        
        .utility-card {
            background: white;
            border: 1px solid var(--pma-border);
            border-radius: 5px;
            padding: 20px;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            color: var(--pma-text);
            display: block;
        }
        
        .utility-card:hover {
            background: var(--pma-bg-hover);
            border-color: var(--pma-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .utility-card .icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        .utility-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--pma-primary-dark);
            margin-bottom: 8px;
        }
        
        .utility-card p {
            font-size: 13px;
            color: var(--pma-text-light);
            line-height: 1.5;
        }
        
        .priority-section {
            padding: 0 30px 20px;
        }
        
        .priority-section h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--pma-text);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--pma-border);
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 3px;
            text-transform: uppercase;
            margin-left: 8px;
        }
        
        .badge-high {
            background: #FFE5E5;
            color: #D9534F;
        }
        
        .badge-medium {
            background: #FFF3CD;
            color: #F0AD4E;
        }
        
        .badge-low {
            background: #E7F4FD;
            color: #3C8DBC;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Maintenance Utilities</h1>
            <p>Lab Automation System - Admin Tools & Dashboards</p>
        </header>
        
        <div class="utilities-grid">
            <a href="check_schema.php" class="utility-card">
                <div class="icon"></div>
                <h3>Database Schema Inspector</h3>
                <p>View complete database structure with tables, columns, indexes, and foreign keys.</p>
            </a>
            
            <!-- <a href="send_greetings.php" class="utility-card">
                <div class="icon"></div>
                <h3>Bulk Welcome Email Sender</h3>
                <p>Send welcome emails to all users who haven't received greetings yet.</p>
            </a> -->
            
            <a href="greetings_dashboard.php" class="utility-card">
                <div class="icon"></div>
                <h3>Welcome Email Dashboard</h3>
                <p>Track which users have received welcome emails with filter and search.</p>
            </a>
     
            <a href="database_backup.php" class="utility-card">
                <div class="icon"></div>
                <h3>Database Backup Manager</h3>
                <p>Create, download, and manage database backups for data safety.</p>
            </a>
            
            <a href="user_roles_manager.php" class="utility-card">
                <div class="icon"></div>
                <h3>User Role Manager</h3>
                <p>Manage user roles and permissions with bulk actions and filtering.</p>
            </a>
            
            <a href="security_audit.php" class="utility-card">
                <div class="icon"></div>
                <h3>Security Audit Log</h3>
                <p>Track failed logins, password resets, and admin actions for security.</p>
            </a>
       
            <a href="user_activity.php" class="utility-card">
                <div class="icon"></div>
                <h3>User Activity Monitor</h3>
                <p>Track user logins, activity patterns, and export data to CSV.</p>
            </a>
            
            <a href="email_logs.php" class="utility-card">
                <div class="icon"></div>
                <h3>Email Logs Viewer</h3>
                <p>View all emails sent from the system with success/failure tracking.</p>
            </a>
            
            <a href="system_health.php" class="utility-card">
                <div class="icon"></div>
                <h3>System Health Dashboard</h3>
                <p>Monitor PHP version, disk space, database connection, and permissions.</p>
            </a>
            
            <!-- <a href="product_stats.php" class="utility-card">
                <div class="icon"></div>
                <h3>Product Statistics</h3>
                <p>Overview of products by category and testing statistics.</p>
            </a> -->
       
            <a href="file_manager.php" class="utility-card">
                <div class="icon"></div>
                <h3>File Manager</h3>
                <p>Browse, view, and delete uploaded files in the uploads directory.</p>
            </a>
            
            <a href="database_cleaner.php" class="utility-card">
                <div class="icon"></div>
                <h3>Database Cleaner</h3>
                <p>Find and clean up duplicate data, orphaned records, and invalid entries.</p>
            </a>
       
            <!-- <a href="test_report_analytics.php" class="utility-card">
                <div class="icon"></div>
                <h3>Test Report Analytics</h3>
                <p>Analyze test results and performance metrics over time.</p>
            </a> -->
            
            <a href="api_endpoints.php" class="utility-card">
                <div class="icon"></div>
                <h3>API Endpoint Tester</h3>
                <p>Test internal API endpoints with sample data and view responses.</p>
            </a>
            
            <a href="cloudflare_test.php" class="utility-card">
                <div class="icon"></div>
                <h3>Cloudflare Turnstile Tester</h3>
                <p>Test Cloudflare turnstile integration and verify secret keys.</p>
            </a>
        </div>
    </div>
</body>
</html>
