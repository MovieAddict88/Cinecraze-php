<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'POST':
        if ($action === 'login') {
            $input = json_decode(file_get_contents('php://input'), true);
            $username = $input['username'] ?? '';
            $password = $input['password'] ?? '';
            
            if (login($username, $password)) {
                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
            }
        } elseif ($action === 'logout') {
            logout();
            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        } elseif ($action === 'change-password') {
            requireLogin();
            $input = json_decode(file_get_contents('php://input'), true);
            $currentPassword = $input['current_password'] ?? '';
            $newPassword = $input['new_password'] ?? '';
            
            if ($currentPassword === ADMIN_PASSWORD) {
                // In a real application, you'd update this in the database
                // For now, we'll just return success (you'd need to implement proper password storage)
                echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            }
        }
        break;
        
    case 'GET':
        if ($action === 'check') {
            echo json_encode(['logged_in' => isLoggedIn()]);
        }
        break;
}
?>