<?php
include '../config/db.php';

// Get database name
$dbName = $conn->query("SELECT DATABASE()")->fetch_row()[0];

// Get all tables in the database
$tablesResult = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $tablesResult->fetch_array()) {
    $tables[] = $row[0];
}

// Handle backup creation
$message = '';
if (isset($_POST['create_backup'])) {
    $backupDir = '../uploads/backups/';
    
    // Create backup directory if it doesn't exist
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0777, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $selectedTable = $_POST['backup_table'] ?? 'full';
    
    // Determine backup filename and command
    if ($selectedTable === 'full') {
        $backupFile = $backupDir . $dbName . '_full_backup_' . $timestamp . '.sql';
        $backupType = 'Full Database';
    } else {
        $backupFile = $backupDir . $dbName . '_' . $selectedTable . '_' . $timestamp . '.sql';
        $backupType = "Table: $selectedTable";
    }
    
    // Get database credentials from connection
    $host = 'localhost'; // Adjust if needed
    $username = 'root';  // Adjust if needed
    $password = '';      // Adjust if needed
    
    // Full path to mysqldump for XAMPP on Windows
    $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
    
    // Fallback to just 'mysqldump' if not on Windows/XAMPP
    if (!file_exists($mysqldumpPath)) {
        $mysqldumpPath = 'mysqldump';
    }
    
    // Create mysqldump command
    if ($selectedTable === 'full') {
        $command = escapeshellarg($mysqldumpPath) . " --user=$username --password=$password --host=$host $dbName > " . escapeshellarg($backupFile) . " 2>&1";
    } else {
        $command = escapeshellarg($mysqldumpPath) . " --user=$username --password=$password --host=$host $dbName " . escapeshellarg($selectedTable) . " > " . escapeshellarg($backupFile) . " 2>&1";
    }
    
    exec($command, $output, $returnVar);
    
    if ($returnVar === 0 && file_exists($backupFile)) {
        $message = "<div class='alert alert-success'>✅ Backup created successfully! ($backupType)</div>";
    } else {
        $message = "<div class='alert alert-error'>❌ Failed to create backup. Error: " . implode("\n", $output) . "</div>";
    }
}

// Handle backup deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $fileToDelete = '../uploads/backups/' . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        if (unlink($fileToDelete)) {
            $message = "<div class='alert alert-success'>✅ Backup deleted successfully!</div>";
        } else {
            $message = "<div class='alert alert-error'>❌ Failed to delete backup.</div>";
        }
    }
}

// Get existing backups
$backupDir = '../uploads/backups/';
$backups = [];
if (file_exists($backupDir)) {
    $files = glob($backupDir . '*.sql');
    foreach ($files as $file) {
        $backups[] = [
            'name' => basename($file),
            'path' => $file,
            'size' => filesize($file),
            'date' => filemtime($file)
        ];
    }
    // Sort by date descending
    usort($backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Backup Manager</title>
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
    min-width: 1200px;
}

.container {
    max-width: 1600px;
    margin: 0 auto;
    background: white;
    border: 1px solid var(--pma-border);
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    overflow: hidden;
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
    content: "💾";
    font-size: 22px;
}

.info {
    background: #E7F4FD;
    border: 1px solid #B8D6F1;
    border-radius: 3px;
    padding: 12px 20px;
    margin: 15px 20px;
    color: var(--pma-text);
    font-size: 13px;
}

.info strong {
    color: var(--pma-primary-dark);
    font-weight: 600;
    margin-right: 5px;
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

.action-bar {
    padding: 20px;
    border-bottom: 1px solid var(--pma-border);
    background: var(--pma-bg-header);
}

.btn {
    display: inline-block;
    padding: 8px 20px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: linear-gradient(to bottom, var(--pma-primary), var(--pma-primary-dark));
    color: white;
    border-color: var(--pma-primary-dark);
}

.btn-primary:hover {
    background: linear-gradient(to bottom, var(--pma-primary-dark), #1F4A6B);
    text-decoration: none;
}

.btn-danger {
    background: linear-gradient(to bottom, var(--pma-danger), #C82333);
    color: white;
    border-color: #BD2130;
    padding: 5px 12px;
    font-size: 12px;
}

.btn-danger:hover {
    background: linear-gradient(to bottom, #C82333, #A71D2A);
    text-decoration: none;
}

.btn-download {
    background: linear-gradient(to bottom, var(--pma-success), #449D44);
    color: white;
    border-color: #419641;
    padding: 5px 12px;
    font-size: 12px;
}

.btn-download:hover {
    background: linear-gradient(to bottom, #449D44, #398439);
    text-decoration: none;
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
    padding: 8px 12px;
    border-bottom: 1px solid var(--pma-border);
    text-align: left;
    white-space: nowrap;
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
    border-right: 1px solid var(--pma-border);
}

table td:last-child {
    border-right: none;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--pma-text-light);
    font-size: 14px;
}

.empty-state .icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Backup Manager <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <div class="info">
            <strong>Database:</strong> <?= $dbName ?>
        </div>
        
        <?= $message ?>
        
        <div class="action-bar">
            <form method="post" style="display: flex; align-items: center; gap: 15px;">
                <div style="flex: 1; max-width: 400px;">
                    <label for="backup_table" style="display: block; margin-bottom: 5px; font-weight: 600; color: var(--pma-text);">
                        Select Backup Type:
                    </label>
                    <select name="backup_table" id="backup_table" style="width: 100%; padding: 8px 12px; border: 1px solid var(--pma-border); border-radius: 3px; font-size: 13px; background: white; color: var(--pma-text);">
                        <option value="full">🗄️ Full Database Backup</option>
                        <optgroup label="Individual Tables">
                            <?php foreach ($tables as $table): ?>
                                <option value="<?= htmlspecialchars($table) ?>">📋 <?= htmlspecialchars($table) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                <button type="submit" name="create_backup" class="btn btn-primary" onclick="return confirm('Create a new backup?')" style="margin-top: 20px;">
                    💾 Create Backup
                </button>
            </form>
        </div>
        
        <?php if (count($backups) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 400px;">Backup File</th>
                    <th style="width: 150px;">Size</th>
                    <th style="width: 200px;">Created</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($backup['name']) ?></strong></td>
                    <td><?= number_format($backup['size'] / 1024, 2) ?> KB</td>
                    <td><?= date('F j, Y g:i A', $backup['date']) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars($backup['path']) ?>" download class="btn btn-download">
                            ⬇ Download
                        </a>
                        <a href="?delete=<?= urlencode($backup['name']) ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this backup?')">
                            🗑 Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon">💾</div>
            <p><strong>No backups found</strong></p>
            <p>Click "Create New Backup" to create your first database backup.</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
