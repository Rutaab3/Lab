<?php
include ('chache.php');

// Not logged in → go to 401
if (!isset($_SESSION['user_id'])) {
    header("Location: ../401.php");
    exit();
}
// Not admin → go to 401
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../403.php");
    exit();
}
