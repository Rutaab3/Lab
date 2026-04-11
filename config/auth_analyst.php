<?php
include ('chache.php');

// Not logged in → go to 401
if (!isset($_SESSION['user_id'])) {
    header("Location: ../401.php");
    exit();
}
// Not analyst → go to 403
if ($_SESSION['role'] !== 'analyst') {
    header("Location: ../403.php");
    exit();
}
