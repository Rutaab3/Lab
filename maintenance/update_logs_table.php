<?php
include '../config/db.php';

// Add mail_sent column to logs table
$query = "ALTER TABLE logs ADD COLUMN mail_sent TINYINT(1) DEFAULT 0";
if ($conn->query($query)) {
    echo "Column mail_sent added successfully.";
} else {
    // Check if duplicate column error (1060)
    if ($conn->errno == 1060) {
        echo "Column mail_sent already exists.";
    } else {
        echo "Error adding column: " . $conn->error;
    }
}
?>
