<?php
include '../config/db.php';

// Allow user_id to be NULL to prevent crashes if session is missing
$sql = "ALTER TABLE logs MODIFY user_id INT NULL";

if ($conn->query($sql)) {
    echo "✅ Success: `logs` table updated. `user_id` can now be NULL.";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
