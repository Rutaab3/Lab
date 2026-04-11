<?php
include '../config/db.php';

// Add notify column to logs table
// 0 = needs email notification (or pending)
// 1 = notified
// (Using TINYINT(1) for boolean-like behavior)
$query = "ALTER TABLE logs ADD COLUMN notify TINYINT(1) DEFAULT 0";

if ($conn->query($query)) {
    echo "✅ Column 'notify' added successfully to 'logs' table.";
} else {
    if ($conn->errno == 1060) {
        echo "ℹ️ Column 'notify' already exists.";
    } else {
        echo "❌ Error adding column: " . $conn->error;
    }
}
?>
