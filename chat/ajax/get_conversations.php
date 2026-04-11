<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conversation_id = isset($_GET['conversation_id']) ? intval($_GET['conversation_id']) : null;

$conversations = [];

// Build query
if ($conversation_id) {
    // Get specific conversation
    $query = "SELECT c.*, cp.is_pinned 
              FROM conversations c
              INNER JOIN conversation_participants cp ON c.id = cp.conversation_id
              WHERE c.id = ? AND cp.user_id = ? AND cp.is_deleted = 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $conversation_id, $user_id);
} else {
    // Get all conversations for user
    $query = "SELECT c.*, cp.is_pinned 
              FROM conversations c
              INNER JOIN conversation_participants cp ON c.id = cp.conversation_id
              WHERE cp.user_id = ? AND cp.is_deleted = 0
              ORDER BY c.updated_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($conv = mysqli_fetch_assoc($result)) {
    $conv_id = $conv['id'];
    $conv_type = $conv['type'];
    
    // Get conversation name and avatar
    if ($conv_type === 'group') {
        $conv_name = $conv['name'];
        $conv_avatar = '/uploads/users/default.png'; // Default group avatar
        
        // Get participant count
        $count_query = "SELECT COUNT(*) as count FROM conversation_participants WHERE conversation_id = ? AND is_deleted = 0";
        $count_stmt = mysqli_prepare($conn, $count_query);
        mysqli_stmt_bind_param($count_stmt, "i", $conv_id);
        mysqli_stmt_execute($count_stmt);
        $count_result = mysqli_stmt_get_result($count_stmt);
        $count_data = mysqli_fetch_assoc($count_result);
        $conv['participant_count'] = $count_data['count'];
    } else {
        // Direct conversation - get other user's info
        $other_user_query = "SELECT u.username, u.profile_img, u.role, u.email 
                            FROM users u
                            INNER JOIN conversation_participants cp ON u.id = cp.user_id
                            WHERE cp.conversation_id = ? AND cp.user_id != ? AND cp.is_deleted = 0
                            LIMIT 1";
        $other_stmt = mysqli_prepare($conn, $other_user_query);
        mysqli_stmt_bind_param($other_stmt, "ii", $conv_id, $user_id);
        mysqli_stmt_execute($other_stmt);
        $other_result = mysqli_stmt_get_result($other_stmt);
        $other_user = mysqli_fetch_assoc($other_result);
        
        if ($other_user) {
            $conv_name = $other_user['username'];
            $conv_avatar = $other_user['profile_img'];
            $conv['user_role'] = $other_user['role'];
            $conv['user_email'] = $other_user['email'];
        } else {
            $conv_name = 'Unknown User';
            $conv_avatar = '../uploads/users/default.png';
        }
    }
    
    // Get last message
    $msg_query = "SELECT m.message, m.sent_at, u.username as sender_name
                  FROM messages m
                  INNER JOIN users u ON m.sender_id = u.id
                  WHERE m.conversation_id = ?
                  ORDER BY m.sent_at DESC
                  LIMIT 1";
    $msg_stmt = mysqli_prepare($conn, $msg_query);
    mysqli_stmt_bind_param($msg_stmt, "i", $conv_id);
    mysqli_stmt_execute($msg_stmt);
    $msg_result = mysqli_stmt_get_result($msg_stmt);
    $last_msg = mysqli_fetch_assoc($msg_result);
    
    if ($last_msg) {
        $preview = $last_msg['message'] ?? '[File]';
        if ($conv_type === 'group') {
            $preview = $last_msg['sender_name'] . ': ' . $preview;
        }
        $conv['last_message'] = strlen($preview) > 50 ? substr($preview, 0, 50) . '...' : $preview;
        $conv['last_message_time'] = $last_msg['sent_at'];
    } else {
        $conv['last_message'] = null;
        $conv['last_message_time'] = null;
    }
    
    // Get unread count
    $unread_query = "SELECT COUNT(*) as count 
                     FROM messages m
                     WHERE m.conversation_id = ? 
                     AND m.sender_id != ? 
                     AND m.is_read = 0";
    $unread_stmt = mysqli_prepare($conn, $unread_query);
    mysqli_stmt_bind_param($unread_stmt, "ii", $conv_id, $user_id);
    mysqli_stmt_execute($unread_stmt);
    $unread_result = mysqli_stmt_get_result($unread_stmt);
    $unread_data = mysqli_fetch_assoc($unread_result);
    $conv['unread_count'] = $unread_data['count'];
    
    $conv['name'] = $conv_name;
    $conv['avatar'] = $conv_avatar;
    
    $conversations[] = $conv;
}

echo json_encode([
    'success' => true,
    'conversations' => $conversations
]);
?>
