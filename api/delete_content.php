<?php
header('Content-Type: application/json');
require_once '../includes/session_check.php';
require_once '../includes/database.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? 0;
$type = $data['type'] ?? '';

if (empty($id) || empty($type)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid ID or content type.']);
    exit;
}

$table = '';
switch ($type) {
    case 'Movie':
        $table = 'movies';
        break;
    case 'TV Series':
        $table = 'tv_series';
        break;
    case 'Live TV':
        $table = 'live_tv';
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid content type specified.']);
        exit;
}

$conn->begin_transaction();

try {
    // Note: For movies and tv_series, we rely on the ON DELETE CASCADE
    // constraint in the database to delete associated seasons, episodes, and servers.
    // For live_tv, there are no dependencies.

    $sql = "DELETE FROM {$table} WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Content deleted successfully.']);
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Content not found or already deleted.']);
        }
    } else {
        throw new Exception('Failed to execute delete statement.');
    }

    $stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A database error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>
