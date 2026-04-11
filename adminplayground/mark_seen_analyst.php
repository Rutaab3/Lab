<?php
include "../config/db.php";
mysqli_report(MYSQLI_REPORT_OFF);

// Mark all unseen reports as seen
mysqli_query($conn, "UPDATE test_reports SET seen_by_analyst = 1 WHERE seen_by_analyst = 0");

