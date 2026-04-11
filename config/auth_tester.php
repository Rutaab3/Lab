<?php
include ('chache.php');

// Not logged in →go to 401
if (!isset($_SESSION['user_id'])) {
    header("Location: ../401.php");
    exit();
}
// Not tester → go to 401
if ($_SESSION['role'] !== 'tester') {
    header("Location: ../403.php");
    exit();
}
