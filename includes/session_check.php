<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not, send an unauthorized error
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401); // Unauthorized
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in to perform this action.']);
    exit;
}
?>
