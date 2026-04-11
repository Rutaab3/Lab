<?php
include '../../config/db.php';

// Add greetings column to users table
$sql = "ALTER TABLE users ADD COLUMN greetings TINYINT(1) DEFAULT 0 COMMENT '0 = not sent, 1 = sent'";

if ($conn->query($sql) === TRUE) {
    echo "Column 'greetings' added successfully.<br>";
    echo "Default value is 0 (greeting not sent).<br>";
} else {
    // Check if column already exists
    if (strpos($conn->error, 'Duplicate column name') !== false) {
        echo "Column 'greetings' already exists.<br>";
    } else {
        echo "Error adding 'greetings': " . $conn->error . "<br>";
    }
}

echo "Database update complete.";
?>
