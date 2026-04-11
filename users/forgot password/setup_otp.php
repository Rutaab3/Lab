<?php
include '../../config/db.php';

// Add otp_code column
$sql1 = "ALTER TABLE users ADD COLUMN otp_code VARCHAR(6) NULL";
if ($conn->query($sql1) === TRUE) {
    echo "Column 'otp_code' added successfully.<br>";
} else {
    echo "Error adding 'otp_code': " . $conn->error . "<br>";
}

// Add otp_expiry column
$sql2 = "ALTER TABLE users ADD COLUMN otp_expiry DATETIME NULL";
if ($conn->query($sql2) === TRUE) {
    echo "Column 'otp_expiry' added successfully.<br>";
} else {
    echo "Error adding 'otp_expiry': " . $conn->error . "<br>";
}

echo "Database update complete.";
?>
