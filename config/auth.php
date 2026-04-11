<?php
include ('chache.php');

// Not logged in → go login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../401.php");
    exit();
}

// --- NEW SECURITY CHECK ---
// Ensure we have a database connection
if (!isset($conn)) {
    include __DIR__ . '/db.php';
}

$uid_check = $_SESSION['user_id'];
$security_query = "SELECT id FROM users WHERE id = '$uid_check' LIMIT 1";
$security_result = mysqli_query($conn, $security_query);

if (!$security_result || mysqli_num_rows($security_result) == 0) {
    // User no longer exists in DB! Kill session.
    session_unset();
    session_destroy();
    header("Location: ../users/login.php?error=AccountDeleted");
    exit();
}

// Safely assign role
$role = $_SESSION['role'];
?>
