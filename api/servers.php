<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/database.php';

$response = array('success' => false, 'servers' => array());

if (isset($_GET['content_id']) && isset($_GET['content_type'])) {
    $content_id = intval($_GET['content_id']);
    $content_type = $_GET['content_type'];

    if ($content_id > 0 && in_array($content_type, ['movie', 'episode'])) {
        $sql = "SELECT * FROM servers WHERE content_id = ? AND content_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $content_id, $content_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $servers = array();
            while ($row = $result->fetch_assoc()) {
                $servers[] = array(
                    'name' => $row['name'],
                    'url' => $row['url'],
                    'quality' => $row['quality'],
                    'is_embed' => (bool)$row['is_embed']
                );
            }
            $response['success'] = true;
            $response['servers'] = $servers;
        }
        $stmt->close();
    }
}

echo json_encode($response);

$conn->close();
?>
