<?php
include "../../config/db.php";

// Check if user is logged in
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

// Verify user is participant in this conversation
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

// Get messages
$query = "SELECT m.*, u.username as sender_name, u.profile_img as sender_avatar
          FROM messages m
          INNER JOIN users u ON m.sender_id = u.id
          WHERE m.conversation_id = ?
          ORDER BY m.sent_at ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $conversation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($msg = mysqli_fetch_assoc($result)) {
    $messages[] = $msg;
}

// Mark messages as read
$mark_read_query = "UPDATE messages 
                    SET is_read = 1 
                    WHERE conversation_id = ? 
                    AND sender_id != ? 
                    AND is_read = 0";
$mark_stmt = mysqli_prepare($conn, $mark_read_query);
mysqli_stmt_bind_param($mark_stmt, "ii", $conversation_id, $user_id);
mysqli_stmt_execute($mark_stmt);

echo json_encode([
    'success' => true,
    'messages' => $messages
]);
?>
