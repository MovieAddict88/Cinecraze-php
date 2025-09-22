<?php
// Main API Router

// Include database connection and utility functions
require_once '../config/db.php';
require_once 'functions.php';

// Get the requested endpoint from the URL.
// Example: /api/index.php?request=genres
$request = isset($_GET['request']) ? strtolower(trim($_GET['request'])) : '';

// A simple router.
// It checks for the requested resource and includes the corresponding handler file.

// Sanitize the request to prevent directory traversal attacks
$safe_request = basename($request);

// The path to the handler file
$handler_file = __DIR__ . '/handlers/' . $safe_request . '.php';

// Whitelist of safe handlers to prevent arbitrary file inclusion
$allowed_handlers = [
    'content',
    'genres',
    'servers',
    'tmdb',
    'tmdb_search',
    'interactions'
];

if (in_array($safe_request, $allowed_handlers) && file_exists($handler_file)) {
    // The handler file exists and is allowed, so include it.
    // The handler file is expected to handle the request and send a response.
    require_once $handler_file;
} else {
    // The requested endpoint does not have a handler or is not allowed.
    send_error_response(404, "Not Found: The requested endpoint '{$request}' does not exist or is not permitted.");
}

// Close the database connection if it's still open
if (isset($conn) && $conn) {
    $conn->close();
}
?>
