<?php
include "../config/db.php";
include "../config/auth.php";
include "../config/action_logger.php";
mysqli_report(MYSQLI_REPORT_OFF);
require_once __DIR__ . '/../vendor/fpdf/fpdf.php';
?>