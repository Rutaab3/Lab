<?php
/**
 * Settings Helper
 * Fetches application configuration from system_settings table
 */

if (!function_exists('get_system_settings')) {
    function get_system_settings($category) {
        global $conn;
        
        // Ensure connection is established
        if (!$conn) {
            include __DIR__ . '/db.php';
        }

        $stmt = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $settings = [];
        while ($row = $result->fetch_assoc()) {
            // Attempt to decode JSON if it looks like a JSON array/object
            $value = $row['setting_value'];
            if (strpos($value, '[') === 0 || strpos($value, '{') === 0) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
            $settings[$row['setting_key']] = $value;
        }
        
        return $settings;
    }
}
?>
