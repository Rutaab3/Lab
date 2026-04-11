<?php
include ('chache.php');

// Not logged in → go login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../401.php");
    exit();
}
// Not supplier → forbidden
if ($_SESSION['role'] !== 'supplier') {
    header("Location: ../403.php");
    exit();
}
