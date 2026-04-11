<?php
include '../config/db.php';
$res = $conn->query("DESCRIBE logs");
if ($res) {
    echo "Columns in 'logs' table:\n";
    while ($row = $res->fetch_assoc()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error describing logs table: " . $conn->error;
}
?>
