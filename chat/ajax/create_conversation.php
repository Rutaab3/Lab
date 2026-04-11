<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$other_user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;

if (!$other_user_id) {
    echo json_encode(['success' => false, 'error' => 'User ID required']);
    exit();
}

// Verify other user exists and is active
$verify_query = "SELECT id FROM users WHERE id = ? AND status = 'active'";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, "i", $other_user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) === 0) {
    echo json_encode(['success' => false, 'error' => 'User not found or inactive']);
    exit();
}

// Check if conversation already exists between these two users
$check_query = "SELECT c.id 
                FROM conversations c
                WHERE c.type = 'direct'
                AND c.id IN (
                    SELECT cp1.conversation_id 
                    FROM conversation_participants cp1
                    WHERE cp1.user_id = ?
                )
                AND c.id IN (
                    SELECT cp2.conversation_id 
                    FROM conversation_participants cp2
                    WHERE cp2.user_id = ?
                )
                LIMIT 1";

$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $other_user_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if ($existing = mysqli_fetch_assoc($check_result)) {
    // Conversation exists, make sure is_deleted is 0 for both users
    $update_query = "UPDATE conversation_participants 
                     SET is_deleted = 0 
                     WHERE conversation_id = ? AND user_id IN (?, ?)";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "iii", $existing['id'], $user_id, $other_user_id);
    mysqli_stmt_execute($update_stmt);
    
    echo json_encode([
        'success' => true,
        'conversation_id' => $existing['id'],
        'existing' => true
    ]);
    exit();
}

// Create new conversation
$insert_conv = "INSERT INTO conversations (type, created_at, updated_at) 
                VALUES ('direct', NOW(), NOW())";
if (mysqli_query($conn, $insert_conv)) {
    $conversation_id = mysqli_insert_id($conn);
    
    // Add both participants
    $insert_part = "INSERT INTO conversation_participants (conversation_id, user_id, is_deleted) 
                    VALUES (?, ?, 0), (?, ?, 0)";
    $part_stmt = mysqli_prepare($conn, $insert_part);
    mysqli_stmt_bind_param($part_stmt, "iiii", 
        $conversation_id, $user_id,
        $conversation_id, $other_user_id
    );
    
    if (mysqli_stmt_execute($part_stmt)) {
        echo json_encode([
            'success' => true,
            'conversation_id' => $conversation_id,
            'existing' => false
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add participants']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create conversation']);
}
?>
