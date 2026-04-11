<?php
/**
 * Save Language Preference
 * AJAX endpoint to save user's preferred language
 */

session_start();
include "../config/db.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

// Check if language code is provided
if (!isset($_POST['language']) || empty($_POST['language'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Language code is required'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$language = mysqli_real_escape_string($conn, $_POST['language']);

// Validate language code (allowed languages from Google Translate setup)
$allowed_languages = ['en', 'ur', 'hi', 'ar', 'fa', 'fr', 'de', 'es', 'zh-CN', 'pt', 'ru', 'ja'];

if (!in_array($language, $allowed_languages)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid language code'
    ]);
    exit;
}

// Update user's preferred language
$sql = "UPDATE users SET preferred_language = '$language' WHERE id = $user_id";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'success' => true,
        'message' => 'Language preference saved successfully',
        'language' => $language
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($conn)
    ]);
}
?>
