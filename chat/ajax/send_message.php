<?php
include "../../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conversation_id = isset($_POST['conversation_id']) ? intval($_POST['conversation_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

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

// Handle file upload
$file_path = null;
$file_name = null;
$file_type = null;
$file_size = null;

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../uploads/chat/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $unique_name = uniqid() . '_' . time() . '.' . $file_ext;
    $file_path = 'uploads/chat/' . $unique_name;
    
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $unique_name)) {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
        exit();
    }
}

// Validate that we have either a message or a file
if (empty($message) && !$file_path) {
    echo json_encode(['success' => false, 'error' => 'Message or file required']);
    exit();
}

// Insert message
$insert_query = "INSERT INTO messages (conversation_id, sender_id, message, file_path, file_name, file_type, file_size, sent_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$insert_stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iissssi", 
    $conversation_id, 
    $user_id, 
    $message, 
    $file_path, 
    $file_name, 
    $file_type, 
    $file_size
);

if (mysqli_stmt_execute($insert_stmt)) {
    // Update conversation timestamp
    $update_conv = "UPDATE conversations SET updated_at = NOW() WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_conv);
    mysqli_stmt_bind_param($update_stmt, "i", $conversation_id);
    mysqli_stmt_execute($update_stmt);
    
    echo json_encode([
        'success' => true,
        'message_id' => mysqli_insert_id($conn)
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}
?>
