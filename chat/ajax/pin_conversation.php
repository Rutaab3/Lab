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
$verify_query = "SELECT is_pinned FROM conversation_participants 
                 WHERE conversation_id = ? AND user_id = ? AND is_deleted = 0";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, "ii", $conversation_id, $user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) === 0) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit();
}

$row = mysqli_fetch_assoc($verify_result);
$current_pin_status = $row['is_pinned'];

// Toggle pin status
$new_pin_status = $current_pin_status == 1 ? 0 : 1;

$update_query = "UPDATE conversation_participants 
                 SET is_pinned = ? 
                 WHERE conversation_id = ? AND user_id = ?";
$update_stmt = mysqli_prepare($conn, $update_query);
mysqli_stmt_bind_param($update_stmt, "iii", $new_pin_status, $conversation_id, $user_id);

if (mysqli_stmt_execute($update_stmt)) {
    echo json_encode([
        'success' => true,
        'is_pinned' => $new_pin_status
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update pin status']);
}
?>
