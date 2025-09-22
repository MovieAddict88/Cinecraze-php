<?php
// api/get_tmdb_key.php
session_start();
header('Content-Type: application/json');

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    error_response('Authentication required.');
}

require_once '../config.php';

// Check if the constant is defined
if (defined('TMDB_API_KEY')) {
    echo json_encode(['success' => true, 'api_key' => TMDB_API_KEY]);
} else {
    echo json_encode(['success' => true, 'api_key' => '']); // Return empty if not defined
}
