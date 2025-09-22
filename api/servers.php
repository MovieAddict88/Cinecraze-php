<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'POST':
        requireLogin();
        if ($action === 'add' && isset($_GET['movie_id'])) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (addServer($_GET['movie_id'], $input)) {
                echo json_encode(['success' => true, 'message' => 'Server added successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add server']);
            }
        }
        break;
        
    case 'PUT':
        requireLogin();
        if ($action === 'update' && isset($_GET['id'])) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (updateServer($_GET['id'], $input)) {
                echo json_encode(['success' => true, 'message' => 'Server updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update server']);
            }
        }
        break;
        
    case 'DELETE':
        requireLogin();
        if ($action === 'delete' && isset($_GET['id'])) {
            if (deleteServer($_GET['id'])) {
                echo json_encode(['success' => true, 'message' => 'Server deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete server']);
            }
        }
        break;
}
?>