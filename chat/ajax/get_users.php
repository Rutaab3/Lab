<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all active users except current user
$query = "SELECT id, username, email, profile_img, role 
          FROM users 
          WHERE id != ? AND status = 'active'
          ORDER BY username ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$users = [];
while ($user = mysqli_fetch_assoc($result)) {
    $users[] = $user;
}

echo json_encode([
    'success' => true,
    'users' => $users
]);
?>
