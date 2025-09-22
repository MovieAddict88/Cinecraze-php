<?php
// api/content_update.php
// Updates content in the database.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $content_id = $data['id'] ?? null;

    if (empty($content_id) || !is_numeric($content_id)) {
        $response['message'] = 'Invalid or missing content ID.';
    } else {
        $conn = get_db_connection();
        if ($conn) {
            $fields_to_update = [];
            $params = [];
            $types = '';

            // Whitelist of fields that can be updated in the 'content' table
            $allowed_fields = ['title', 'description', 'release_year', 'rating', 'parental_rating', 'country', 'duration'];

            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $fields_to_update[] = "$field = ?";
                    $params[] = $data[$field];
                    // Determine param type
                    if (is_int($data[$field])) {
                        $types .= 'i';
                    } elseif (is_double($data[$field]) || is_float($data[$field])) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
            }

            if (!empty($fields_to_update)) {
                $sql = "UPDATE content SET " . implode(', ', $fields_to_update) . " WHERE id = ?";
                $params[] = $content_id;
                $types .= 'i';

                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Content updated successfully.';
                } else {
                    $response['message'] = 'Error executing update statement: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'No valid fields provided for update.';
            }

            // Note: Updating servers, genres, seasons, and episodes will require
            // additional logic here or in separate API endpoints. For now, this
            // handles the main content fields.

            $conn->close();
        } else {
            $response['message'] = 'Database connection failed.';
        }
    }
}

echo json_encode($response);
?>
