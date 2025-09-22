<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/database.php';

$response = array('success' => false, 'message' => 'Invalid request');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content_id = isset($_POST['content_id']) ? intval($_POST['content_id']) : 0;
    $content_type = isset($_POST['content_type']) ? $_POST['content_type'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($content_id > 0 && in_array($content_type, ['movie', 'tv_series']) && in_array($action, ['like', 'dislike'])) {
        // Check if a record already exists for this content
        $check_sql = "SELECT id FROM likes WHERE content_id = ? AND content_type = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("is", $content_id, $content_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $update_sql = "UPDATE likes SET {$action}s = {$action}s + 1 WHERE content_id = ? AND content_type = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("is", $content_id, $content_type);
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO likes (content_id, content_type, {$action}s) VALUES (?, ?, 1)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("is", $content_id, $content_type);
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Action recorded successfully';
        } else {
            $response['message'] = 'Error recording action: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid parameters';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : 0;
    $content_type = isset($_GET['content_type']) ? $_GET['content_type'] : '';

    if ($content_id > 0 && in_array($content_type, ['movie', 'tv_series'])) {
        $sql = "SELECT likes, dislikes FROM likes WHERE content_id = ? AND content_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $content_id, $content_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response['success'] = true;
            $response['likes'] = $row['likes'];
            $response['dislikes'] = $row['dislikes'];
        } else {
            $response['success'] = true;
            $response['likes'] = 0;
            $response['dislikes'] = 0;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid parameters';
    }
}


echo json_encode($response);

$conn->close();
?>
