<?php
// API handler for Genres

// The main router has already included functions.php and db.php

// Use a switch statement to handle different request methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_genres($conn);
        break;
    case 'POST':
        handle_post_genres($conn);
        break;
    case 'PUT':
        handle_put_genres($conn);
        break;
    case 'DELETE':
        handle_delete_genres($conn);
        break;
    default:
        // Method Not Allowed
        send_error_response(405, "Method Not Allowed");
        break;
}

/**
 * Handles GET requests for genres.
 * Fetches all genres or a single genre by ID.
 */
function handle_get_genres($conn) {
    if (isset($_GET['id'])) {
        // Get a single genre
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT id, name FROM genres WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $genre = $result->fetch_assoc();
        $stmt->close();

        if ($genre) {
            send_success_response($genre);
        } else {
            send_error_response(404, "Genre not found");
        }
    } else {
        // Get all genres
        $result = $conn->query("SELECT id, name FROM genres ORDER BY name ASC");
        $genres = $result->fetch_all(MYSQLI_ASSOC);
        send_success_response($genres);
    }
}

/**
 * Handles POST requests to create a new genre.
 */
function handle_post_genres($conn) {
    $input = get_json_input();
    $name = trim($input['name'] ?? '');

    if (empty($name)) {
        send_error_response(400, "Genre name is required.");
    }

    $stmt = $conn->prepare("INSERT INTO genres (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        send_success_response(['id' => $new_id, 'name' => $name]);
    } else {
        // Check for duplicate entry
        if ($conn->errno == 1062) {
            send_error_response(409, "Genre '{$name}' already exists.");
        } else {
            send_error_response(500, "Database error: " . $stmt->error);
        }
    }
    $stmt->close();
}

/**
 * Handles PUT requests to update a genre.
 */
function handle_put_genres($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Genre ID is required for update.");
    }
    $id = intval($_GET['id']);
    $input = get_json_input();
    $name = trim($input['name'] ?? '');

    if (empty($name)) {
        send_error_response(400, "Genre name is required.");
    }

    $stmt = $conn->prepare("UPDATE genres SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_success_response(['id' => $id, 'name' => $name]);
        } else {
            send_error_response(404, "Genre not found or no changes made.");
        }
    } else {
        if ($conn->errno == 1062) {
            send_error_response(409, "Genre '{$name}' already exists.");
        } else {
            send_error_response(500, "Database error: " . $stmt->error);
        }
    }
    $stmt->close();
}

/**
 * Handles DELETE requests to remove a genre.
 */
function handle_delete_genres($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Genre ID is required for deletion.");
    }
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM genres WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_success_response(['message' => "Genre with ID {$id} deleted successfully."]);
        } else {
            send_error_response(404, "Genre not found.");
        }
    } else {
        send_error_response(500, "Database error: " . $stmt->error);
    }
    $stmt->close();
}
?>
