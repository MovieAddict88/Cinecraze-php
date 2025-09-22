<?php
// api/change_password.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once '../config.php';
require_once '../includes/database.php';

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    error_response('You must be logged in to change your password.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Invalid request method.');
}

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    error_response('All password fields are required.');
}

if (strlen($new_password) < 6) {
    error_response('New password must be at least 6 characters long.');
}

if ($new_password !== $confirm_password) {
    error_response('New passwords do not match.');
}

try {
    // Get current user's hashed password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_response('User not found.');
    }

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        error_response('Incorrect current password.');
    }

    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->execute([$new_password_hash, $_SESSION['user_id']]);

    echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);

} catch (Exception $e) {
    error_response('Database error: ' . $e->getMessage());
}
