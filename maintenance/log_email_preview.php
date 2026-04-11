<?php
// Mock Data for Preview
$logs = [
    [
        'time' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
        'username' => 'admin',
        'role' => 'admin',
        'action' => 'User Banned',
        'details' => '{"target_user_id": 45, "target_username": "bot_account"}'
    ],
    [
        'time' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
        'username' => 'supplier_jane',
        'role' => 'supplier',
        'action' => 'Product Added',
        'details' => '{"product_id": "PRD-9922-1100", "name": "Precision Scale"}'
    ],
    [
        'time' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'username' => 'tester_bob',
        'role' => 'tester',
        'action' => 'Report Created',
        'details' => '{"test_id": "REP-8833-2211", "result": "passed"}'
    ],
    [
        'time' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        'username' => 'system',
        'role' => 'system',
        'action' => 'Backup Created',
        'details' => '{"file": "backup_2025_12_26.sql"}'
    ]
];

// Generate Rows
$rows_html = '';
foreach ($logs as $log) {
    // Format Details
    $details = json_decode($log['details'], true);
    $details_str = [];
    if (is_array($details)) {
        foreach ($details as $k => $v) {
            $key = ucfirst(str_replace('_', ' ', $k));
            $details_str[] = "<span style='color:#777;'>$key:</span> $v";
        }
    } else {
        $details_str[] = $log['details'];
    }
    $details_html = implode('<br>', $details_str);
    
    // Style Action Badge
    $action = $log['action'];
    $badgeColor = '#e2e3e5'; // default grey
    $textColor = '#383d41';
    
    if (stripos($action, 'banned') !== false || stripos($action, 'deleted') !== false) {
        $badgeColor = '#f8d7da'; $textColor = '#721c24'; // red
    } elseif (stripos($action, 'added') !== false || stripos($action, 'created') !== false) {
        $badgeColor = '#d4edda'; $textColor = '#155724'; // green
    } elseif (stripos($action, 'updated') !== false) {
        $badgeColor = '#cce5ff'; $textColor = '#004085'; // blue
    }

    $rows_html .= "
    <tr style='border-bottom: 1px solid #eeeeee;'>
        <td style='padding: 12px; color: #666666; font-size: 13px;'>{$log['time']}</td>
        <td style='padding: 12px;'>
            <div style='font-weight: bold; color: #333;'>{$log['username']}</div>
            <div style='font-size: 11px; color: #999; text-transform: uppercase;'>{$log['role']}</div>
        </td>
        <td style='padding: 12px;'>
            <span style='background-color: $badgeColor; color: $textColor; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; white-space: nowrap;'>
                $action
            </span>
        </td>
        <td style='padding: 12px; color: #444; font-size: 13px; line-height: 1.4;'>
            $details_html
        </td>
    </tr>";
}

// Load Template
$template = file_get_contents('log_email_template.html');

// Replace Placeholders
$template = str_replace('{{DATE}}', date('F j, Y'), $template);
$template = str_replace('{{LOG_ROWS}}', $rows_html, $template);
$template = str_replace('{{DASHBOARD_URL}}', 'http://localhost/lab/maintenance/user_activity.php', $template); // Adjust log URL as needed

echo $template;
?>
