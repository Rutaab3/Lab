<?php
// Simple test to see if page loads
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Not logged in. <a href='../users/login.php'>Login here</a>");
}

echo "<h1>Chat Test Page</h1>";
echo "<p>User ID: " . $_SESSION['user_id'] . "</p>";
echo "<p>Role: " . ($_SESSION['role'] ?? 'unknown') . "</p>";

// Check if header file exists
$role_lower = strtolower($_SESSION['role'] ?? 'user');
$header_file = "../xtras/{$role_lower}head.php";
echo "<p>Header file: " . $header_file . " - " . (file_exists($header_file) ? "EXISTS" : "NOT FOUND") . "</p>";

// Test includes
echo "<p>Testing CSS and JS files:</p>";
echo "<ul>";
echo "<li>board.css: " . (file_exists("../css/board.css") ? "EXISTS" : "NOT FOUND") . "</li>";
echo "<li>chat.css: " . (file_exists("chat.css") ? "EXISTS" : "NOT FOUND") . "</li>";
echo "<li>chat.js: " . (file_exists("chat.js") ? "EXISTS" : "NOT FOUND") . "</li>";
echo "</ul>";

echo "<p><a href='index.php'>Go to Chat Page</a></p>";
?>
