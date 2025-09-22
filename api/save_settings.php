<?php
header('Content-Type: application/json');
require_once '../includes/session_check.php'; // Ensures only logged-in admins can save

$settings_file = 'settings.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $decoded_data = json_decode($data);

    // Basic validation to ensure we're getting valid JSON
    if (json_last_error() === JSON_ERROR_NONE) {
        // Prettify the JSON before saving
        $pretty_data = json_encode($decoded_data, JSON_PRETTY_PRINT);
        if (file_put_contents($settings_file, $pretty_data)) {
            echo json_encode(['success' => true, 'message' => 'Settings saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write settings to file. Check file permissions.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
