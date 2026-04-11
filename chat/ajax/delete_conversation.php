<?php
include "../../config/db.php";

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
                 WHERE conversation_id = ? AND user_id = ?";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, "ii", $conversation_id, $user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) === 0) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit();
}

// Soft delete - set is_deleted = 1 for this user only
$delete_query = "UPDATE conversation_participants 
                 SET is_deleted = 1 
                 WHERE conversation_id = ? AND user_id = ?";
$delete_stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($delete_stmt, "ii", $conversation_id, $user_id);

if (mysqli_stmt_execute($delete_stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete conversation']);
}
?>
