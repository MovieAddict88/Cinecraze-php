<?php
// API handler for Servers

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        handle_post_servers($conn);
        break;
    case 'PUT':
        handle_put_servers($conn);
        break;
    case 'DELETE':
        handle_delete_servers($conn);
        break;
    default:
        send_error_response(405, "Method Not Allowed");
        break;
}

/**
 * Handles POST requests to create a new server.
 */
function handle_post_servers($conn) {
    $input = get_json_input();

    // --- Validation ---
    $required_fields = ['server_name', 'server_url', 'server_type'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            send_error_response(400, "Field '{$field}' is required.");
        }
    }
    if (!isset($input['content_id']) && !isset($input['episode_id'])) {
        send_error_response(400, "Either 'content_id' or 'episode_id' is required.");
    }
    if (isset($input['content_id']) && isset($input['episode_id'])) {
        send_error_response(400, "Provide either 'content_id' or 'episode_id', not both.");
    }

    // --- Database Insertion ---
    $sql = "INSERT INTO servers (content_id, episode_id, server_name, server_url, server_type, drm_license_url, is_enabled) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $content_id = isset($input['content_id']) ? intval($input['content_id']) : null;
    $episode_id = isset($input['episode_id']) ? intval($input['episode_id']) : null;
    $drm_license_url = $input['drm_license_url'] ?? null;
    $is_enabled = isset($input['is_enabled']) ? (bool)$input['is_enabled'] : true;

    $stmt->bind_param(
        "iissssi",
        $content_id,
        $episode_id,
        $input['server_name'],
        $input['server_url'],
        $input['server_type'],
        $drm_license_url,
        $is_enabled
    );

    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        send_success_response(['id' => $new_id, 'message' => 'Server created successfully.']);
    } else {
        send_error_response(500, "Database error: " . $stmt->error);
    }
    $stmt->close();
}

/**
 * Handles PUT requests to update an existing server.
 */
function handle_put_servers($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Server ID is required for update.");
    }
    $id = intval($_GET['id']);
    $input = get_json_input();

    $fields = [];
    $params = [];
    $types = '';

    $updatable_fields = ['server_name', 'server_url', 'server_type', 'drm_license_url', 'is_enabled'];
    foreach ($updatable_fields as $field) {
        if (isset($input[$field])) {
            $fields[] = "{$field} = ?";
            $params[] = ($field === 'is_enabled') ? (bool)$input[$field] : $input[$field];
            $types .= ($field === 'is_enabled') ? 'i' : 's';
        }
    }

    if (count($fields) == 0) {
        send_error_response(400, "No fields provided to update.");
    }

    $sql = "UPDATE servers SET " . implode(', ', $fields) . " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_success_response(['id' => $id, 'message' => 'Server updated successfully.']);
        } else {
            send_error_response(404, "Server not found or no changes made.");
        }
    } else {
        send_error_response(500, "Database error: " . $stmt->error);
    }
    $stmt->close();
}

/**
 * Handles DELETE requests to remove a server.
 */
function handle_delete_servers($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Server ID is required for deletion.");
    }
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM servers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_success_response(['message' => "Server with ID {$id} deleted successfully."]);
        } else {
            send_error_response(404, "Server not found.");
        }
    } else {
        send_error_response(500, "Database error: " . $stmt->error);
    }
    $stmt->close();
}
?>
