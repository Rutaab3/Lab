<?php
include "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conversation_id = isset($_GET['conversation_id']) ? intval($_GET['conversation_id']) : 0;

if (!$conversation_id) {
    echo json_encode(['success' => false, 'error' => 'Conversation ID required']);
    exit();
}

// Verify user is participant
$verify_query = "SELECT 1 FROM conversation_participants 
                 WHERE conversation_id = ? AND user_id = ? AND is_deleted = 0";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, "ii", $conversation_id, $user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) === 0) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit();
}

// Get all members
$members_query = "SELECT u.id, u.username, u.email, u.profile_img
                  FROM users u
                  INNER JOIN conversation_participants cp ON u.id = cp.user_id
                  WHERE cp.conversation_id = ? AND cp.is_deleted = 0
                  ORDER BY u.username ASC";
$members_stmt = mysqli_prepare($conn, $members_query);
mysqli_stmt_bind_param($members_stmt, "i", $conversation_id);
mysqli_stmt_execute($members_stmt);
$members_result = mysqli_stmt_get_result($members_stmt);

$members = [];
while ($member = mysqli_fetch_assoc($members_result)) {
    $members[] = [
        'id' => $member['id'],
        'username' => $member['username'],
        'email' => $member['email'],
        'profile_img' => $member['profile_img'] ?? 'uploads/users/default.png'
    ];
}

echo json_encode([
    'success' => true,
    'members' => $members
]);
?>
