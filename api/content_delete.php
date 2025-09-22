<?php
// api/content_delete.php
// Deletes a piece of content from the database.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // It's better to get ID from a POST body for delete operations
    $data = json_decode(file_get_contents('php://input'), true);
    $content_id = $data['id'] ?? null;

    if (empty($content_id) || !is_numeric($content_id)) {
        $response['message'] = 'Invalid or missing content ID.';
    } else {
        $conn = get_db_connection();
        if ($conn) {
            // The `ON DELETE CASCADE` in the schema will handle deleting related
            // seasons, episodes, servers, genres, and interactions.
            $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
            $stmt->bind_param("i", $content_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Content deleted successfully.';
                } else {
                    $response['message'] = 'Content not found or already deleted.';
                }
            } else {
                $response['message'] = 'Error executing delete statement: ' . $stmt->error;
            }
            $stmt->close();
            $conn->close();
        } else {
            $response['message'] = 'Database connection failed.';
        }
    }
}

echo json_encode($response);
?>
