<?php
// API Utility Functions

/**
 * Sends a JSON response.
 *
 * @param int $status_code The HTTP status code.
 * @param mixed $data The data to be encoded in JSON.
 */
function send_json_response($status_code, $data) {
    header_remove();
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode($data);
    exit();
}

/**
 * A simple error response helper.
 *
 * @param int $status_code The HTTP status code.
 * @param string $message The error message.
 */
function send_error_response($status_code, $message) {
    send_json_response($status_code, ['error' => $message]);
}

/**
 * A simple success response helper.
 *
 * @param mixed $data The data to be sent.
 */
function send_success_response($data) {
    send_json_response(200, ['success' => true, 'data' => $data]);
}

/**
 * Ensures the request method is one of the allowed methods.
 * If not, it sends a 405 Method Not Allowed error.
 *
 * @param array|string $allowed_methods An array of allowed methods (e.g., ['GET', 'POST']) or a single method as a string.
 */
function allow_methods($allowed_methods) {
    if (!is_array($allowed_methods)) {
        $allowed_methods = [$allowed_methods];
    }
    if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
        send_error_response(405, "Method Not Allowed. Only " . implode(', ', $allowed_methods) . " are accepted.");
    }
}

/**
 * Gets the JSON input from the request body.
 *
 * @return mixed The decoded JSON data as an associative array.
 */
function get_json_input() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        send_error_response(400, "Invalid JSON input: " . json_last_error_msg());
    }
    return $input;
}
?>
