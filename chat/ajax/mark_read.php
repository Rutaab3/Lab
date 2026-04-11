<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conversation_id = isset($_POST['conversation_id']) ? intval($_POST['conversation_id']) : 0;

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

// Mark all messages in conversation as read
$update_query = "UPDATE messages 
                 SET is_read = 1 
                 WHERE conversation_id = ? 
                 AND sender_id != ? 
                 AND is_read = 0";
$update_stmt = mysqli_prepare($conn, $update_query);
mysqli_stmt_bind_param($update_stmt, "ii", $conversation_id, $user_id);

if (mysqli_stmt_execute($update_stmt)) {
    echo json_encode([
        'success' => true,
        'marked' => mysqli_affected_rows($conn)
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to mark messages as read']);
}
?>
