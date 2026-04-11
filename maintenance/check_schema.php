<?php
include '../config/db.php';

// Get database name
$dbName = $conn->query("SELECT DATABASE()")->fetch_row()[0];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Schema Inspector</title>
  <style>
    /* phpMyAdmin Inspired UI - Database Schema Inspector */
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

/* Header Section */
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
    content: "🗄️";
    font-size: 22px;
}

h2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--pma-text);
    background: var(--pma-bg-header);
    padding: 12px 20px;
    margin: 0;
    border-top: 1px solid var(--pma-border);
    border-bottom: 1px solid var(--pma-border);
    display: flex;
    align-items: center;
    gap: 8px;
}

h2:before {
    content: "📋";
    font-size: 16px;
}

h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--pma-text);
    background: linear-gradient(to right, #F9F9F9, #FFF);
    padding: 10px 20px;
    margin: 0;
    border-bottom: 1px solid var(--pma-border);
    border-left: 3px solid var(--pma-primary);
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

/* Tables - phpMyAdmin Style */
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
    height: 32px;
    vertical-align: middle;
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
    padding: 8px 12px;
    vertical-align: top;
    border-right: 1px solid var(--pma-border);
    height: 32px;
    vertical-align: middle;
}

table td:last-child {
    border-right: none;
}

/* Table-specific widths for better alignment */
table:first-of-type th:nth-child(1) { width: 180px; } /* Field */
table:first-of-type th:nth-child(2) { width: 150px; } /* Type */
table:first-of-type th:nth-child(3) { width: 80px; }  /* Null */
table:first-of-type th:nth-child(4) { width: 100px; } /* Key */
table:first-of-type th:nth-child(5) { width: 120px; } /* Default */
table:first-of-type th:nth-child(6) { width: 100px; } /* Extra */
table:first-of-type th:nth-child(7) { width: 120px; } /* Collation */
table:first-of-type th:nth-child(8) { width: auto; }  /* Comment */

/* Badges - phpMyAdmin Style */
.badge {
    display: inline-block;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 3px;
    border: 1px solid transparent;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-primary {
    background-color: #D9EDF7;
    color: #31708F;
    border-color: #BCE8F1;
}

.badge-unique {
    background-color: #DFF0D8;
    color: #3C763D;
    border-color: #D6E9C6;
}

.badge-index {
    background-color: #FCF8E3;
    color: #8A6D3B;
    border-color: #FAEBCC;
}

.badge-null {
    background-color: #F2DEDE;
    color: #A94442;
    border-color: #EBCCD1;
}

.badge-foreign {
    background-color: #E8F4FD;
    color: #2B6A94;
    border-color: #D1E3F4;
}

/* Field highlighting */
td strong {
    color: var(--pma-primary-dark);
    font-weight: 600;
}

/* No data message */
p em {
    color: var(--pma-text-light);
    font-style: italic;
    padding: 20px;
    display: block;
    text-align: center;
}

/* Table status specific */
table tr:first-child td:first-child {
    border-top-left-radius: 3px;
}

table tr:first-child td:last-child {
    border-top-right-radius: 3px;
}

table tr:last-child td:first-child {
    border-bottom-left-radius: 3px;
}

table tr:last-child td:last-child {
    border-bottom-right-radius: 3px;
}

/* Horizontal Rule */
hr {
    margin: 25px 20px;
    border: none;
    border-top: 1px solid var(--pma-border);
    height: 0;
}

/* Data type coloring */
td:nth-child(2) {
    color: var(--pma-success);
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 12px;
}

/* Default value styling */
td:nth-child(5) {
    color: var(--pma-warning);
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
}

/* Extra column styling */
td:nth-child(6) {
    color: var(--pma-danger);
    font-weight: 600;
}

/* Cardinality meter */
td:nth-child(6):empty::before {
    content: "—";
    color: var(--pma-text-light);
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    body {
        min-width: 100%;
        overflow-x: auto;
    }
    
    .container {
        min-width: 1200px;
    }
}

/* Print styles */
@media print {
    body {
        background: white;
        padding: 0;
    }
    
    .container {
        border: none;
        box-shadow: none;
    }
    
    table tr {
        break-inside: avoid;
    }
}
  </style>
</head>
<body>
    <div class="container">
        <h1>Database Schema Inspector <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        <div class="info">
            <strong>Database:</strong> <?= $dbName ?>
        </div>

<?php

// Get all tables
$tables = $conn->query("SHOW TABLES");

while ($table = $tables->fetch_array()) {
    $tableName = $table[0];
    
    echo "<h2>Table: $tableName</h2>";
    
    // ========== COLUMNS ==========
    echo "<h3>Columns</h3>";
    $columns = $conn->query("SHOW FULL COLUMNS FROM `$tableName`");
    
    if ($columns) {
        echo "<table>";
        echo "<tr>
                <th>Field</th>
                <th>Type</th>
                <th>Null</th>
                <th>Key</th>
                <th>Default</th>
                <th>Extra</th>
                <th>Collation</th>
                <th>Comment</th>
              </tr>";
        
        while ($col = $columns->fetch_assoc()) {
            $keyBadge = '';
            if ($col['Key'] == 'PRI') $keyBadge = '<span class="badge badge-primary">PRIMARY</span>';
            if ($col['Key'] == 'UNI') $keyBadge = '<span class="badge badge-unique">UNIQUE</span>';
            if ($col['Key'] == 'MUL') $keyBadge = '<span class="badge badge-index">INDEX</span>';
            
            $nullBadge = $col['Null'] == 'NO' ? '<span class="badge badge-null">NOT NULL</span>' : 'YES';
            
            echo "<tr>
                    <td><strong>{$col['Field']}</strong></td>
                    <td>{$col['Type']}</td>
                    <td>$nullBadge</td>
                    <td>$keyBadge</td>
                    <td>" . ($col['Default'] ?? '<em>NULL</em>') . "</td>
                    <td>{$col['Extra']}</td>
                    <td>" . ($col['Collation'] ?? '-') . "</td>
                    <td>" . ($col['Comment'] ?? '-') . "</td>
                  </tr>";
        }
        echo "</table>";
    }
    
    // ========== INDEXES ==========
    echo "<h3>Indexes</h3>";
    $indexes = $conn->query("SHOW INDEX FROM `$tableName`");
    
    if ($indexes && $indexes->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Key Name</th>
                <th>Column</th>
                <th>Unique</th>
                <th>Type</th>
                <th>Seq</th>
                <th>Cardinality</th>
              </tr>";
        
        while ($idx = $indexes->fetch_assoc()) {
            $unique = $idx['Non_unique'] == 0 ? '<span class="badge badge-unique">UNIQUE</span>' : 'No';
            
            echo "<tr>
                    <td><strong>{$idx['Key_name']}</strong></td>
                    <td>{$idx['Column_name']}</td>
                    <td>$unique</td>
                    <td>{$idx['Index_type']}</td>
                    <td>{$idx['Seq_in_index']}</td>
                    <td>{$idx['Cardinality']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p><em>No indexes found</em></p>";
    }
    
    // ========== FOREIGN KEYS ==========
    echo "<h3>Foreign Keys</h3>";
    $foreignKeys = $conn->query("
        SELECT 
            kcu.CONSTRAINT_NAME,
            kcu.COLUMN_NAME,
            kcu.REFERENCED_TABLE_NAME,
            kcu.REFERENCED_COLUMN_NAME,
            rc.UPDATE_RULE,
            rc.DELETE_RULE
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
        LEFT JOIN
            INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
            ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
            AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
        WHERE 
            kcu.TABLE_SCHEMA = '$dbName' 
            AND kcu.TABLE_NAME = '$tableName'
            AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if ($foreignKeys && $foreignKeys->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Constraint</th>
                <th>Column</th>
                <th>References</th>
                <th>On Update</th>
                <th>On Delete</th>
              </tr>";
        
        while ($fk = $foreignKeys->fetch_assoc()) {
            echo "<tr>
                    <td><span class='badge badge-foreign'>{$fk['CONSTRAINT_NAME']}</span></td>
                    <td><strong>{$fk['COLUMN_NAME']}</strong></td>
                    <td>{$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}</td>
                    <td>{$fk['UPDATE_RULE']}</td>
                    <td>{$fk['DELETE_RULE']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p><em>No foreign keys found</em></p>";
    }
    
    // ========== TABLE STATUS ==========
    echo "<h3>Table Status</h3>";
    $status = $conn->query("SHOW TABLE STATUS LIKE '$tableName'")->fetch_assoc();
    
    if ($status) {
        echo "<table>";
        echo "<tr><th style='width: 200px;'>Property</th><th>Value</th></tr>";
        echo "<tr><td><strong>Engine</strong></td><td>{$status['Engine']}</td></tr>";
        echo "<tr><td><strong>Rows</strong></td><td>" . number_format($status['Rows']) . "</td></tr>";
        echo "<tr><td><strong>Avg Row Length</strong></td><td>" . number_format($status['Avg_row_length']) . " bytes</td></tr>";
        echo "<tr><td><strong>Data Length</strong></td><td>" . number_format($status['Data_length']) . " bytes</td></tr>";
        echo "<tr><td><strong>Auto Increment</strong></td><td>" . ($status['Auto_increment'] ?? 'N/A') . "</td></tr>";
        echo "<tr><td><strong>Collation</strong></td><td>{$status['Collation']}</td></tr>";
        echo "<tr><td><strong>Created</strong></td><td>{$status['Create_time']}</td></tr>";
        echo "<tr><td><strong>Updated</strong></td><td>" . ($status['Update_time'] ?? 'Never') . "</td></tr>";
        echo "<tr><td><strong>Comment</strong></td><td>" . ($status['Comment'] ?: '-') . "</td></tr>";
        echo "</table>";
    }
    
    echo "<hr style='margin: 40px 0; border: none; border-top: 2px solid #eee;'>";
}

?>
    </div>
</body>
</html>
