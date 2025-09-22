<?php
// api/interactions.php
// Handles getting and updating like/dislike counts.

header('Content-Type: application/json');
require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];
$conn = get_db_connection();

if (!$conn) {
    $response['message'] = 'Database connection failed.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // --- Handle fetching interaction counts ---
    $content_id = $_GET['content_id'] ?? null;
    if (empty($content_id) || !is_numeric($content_id)) {
        $response['message'] = 'Invalid or missing content ID.';
    } else {
        // First, check if an interactions row exists. If not, create it.
        $check_stmt = $conn->prepare("SELECT id FROM interactions WHERE content_id = ?");
        $check_stmt->bind_param("i", $content_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows === 0) {
            $insert_stmt = $conn->prepare("INSERT INTO interactions (content_id, likes, dislikes, views) VALUES (?, 0, 0, 0)");
            $insert_stmt->bind_param("i", $content_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $check_stmt->close();

        // Now, fetch the counts
        $stmt = $conn->prepare("SELECT likes, dislikes, views FROM interactions WHERE content_id = ?");
        $stmt->bind_param("i", $content_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($data = $result->fetch_assoc()) {
            $response['success'] = true;
            $response['data'] = [
                'likes' => (int)$data['likes'],
                'dislikes' => (int)$data['dislikes'],
                'views' => (int)$data['views']
            ];
        } else {
            $response['message'] = 'Could not find interaction data for the given content.';
        }
        $stmt->close();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Handle updating like/dislike counts ---
    $data = json_decode(file_get_contents('php://input'), true);
    $content_id = $data['content_id'] ?? null;
    $action = $data['action'] ?? null; // 'like', 'dislike', 'view'

    if (empty($content_id) || !is_numeric($content_id) || !in_array($action, ['like', 'dislike', 'view'])) {
        $response['message'] = 'Invalid or missing parameters.';
    } else {
        // Ensure the interactions row exists before trying to update it
        $check_stmt = $conn->prepare("SELECT id FROM interactions WHERE content_id = ?");
        $check_stmt->bind_param("i", $content_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows === 0) {
            $insert_stmt = $conn->prepare("INSERT INTO interactions (content_id) VALUES (?)");
            $insert_stmt->bind_param("i", $content_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $check_stmt->close();

        // Use atomic updates to prevent race conditions
        $field_to_update = '';
        if ($action === 'like') $field_to_update = 'likes';
        if ($action === 'dislike') $field_to_update = 'dislikes';
        if ($action === 'view') $field_to_update = 'views';

        $sql = "UPDATE interactions SET {$field_to_update} = {$field_to_update} + 1 WHERE content_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $content_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "{$action} count updated successfully.";
        } else {
            $response['message'] = 'Failed to update interaction count.';
        }
        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
