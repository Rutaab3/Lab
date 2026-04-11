<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

// Verify user is admin
if ($user_role !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Only admins can create groups']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$group_name = isset($data['name']) ? trim($data['name']) : '';
$members = isset($data['members']) ? $data['members'] : [];

if (empty($group_name)) {
    echo json_encode(['success' => false, 'error' => 'Group name required']);
    exit();
}

if (empty($members) || !is_array($members)) {
    echo json_encode(['success' => false, 'error' => 'At least one member required']);
    exit();
}

// Validate all members exist and are active
$member_ids = array_map('intval', $members);
$placeholders = str_repeat('?,', count($member_ids) - 1) . '?';

$verify_query = "SELECT COUNT(*) as count FROM users 
                 WHERE id IN ($placeholders) AND status = 'active'";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, str_repeat('i', count($member_ids)), ...$member_ids);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);
$verify_data = mysqli_fetch_assoc($verify_result);

if ($verify_data['count'] != count($member_ids)) {
    echo json_encode(['success' => false, 'error' => 'Some members are invalid or inactive']);
    exit();
}

// Create group conversation
$insert_conv = "INSERT INTO conversations (type, name, creator_id, created_at, updated_at) 
                VALUES ('group', ?, ?, NOW(), NOW())";
$conv_stmt = mysqli_prepare($conn, $insert_conv);
mysqli_stmt_bind_param($conv_stmt, "si", $group_name, $user_id);

if (mysqli_stmt_execute($conv_stmt)) {
    $conversation_id = mysqli_insert_id($conn);
    
    // Add creator as participant
    $member_ids[] = $user_id;
    $member_ids = array_unique($member_ids);
    
    // Build insert query for all participants
    $values = [];
    $params = [];
    $types = '';
    
    foreach ($member_ids as $member_id) {
        $values[] = "(?, ?, 0)";
        $params[] = $conversation_id;
        $params[] = $member_id;
        $types .= 'ii';
    }
    
    $insert_part = "INSERT INTO conversation_participants (conversation_id, user_id, is_deleted) 
                    VALUES " . implode(', ', $values);
    $part_stmt = mysqli_prepare($conn, $insert_part);
    mysqli_stmt_bind_param($part_stmt, $types, ...$params);
    
    if (mysqli_stmt_execute($part_stmt)) {
        echo json_encode([
            'success' => true,
            'conversation_id' => $conversation_id
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add participants']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create group']);
}
?>
