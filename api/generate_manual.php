<?php
// api/generate_manual.php
// Handles inserting manually entered content into the database.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Basic validation
    if (empty($data['title']) || empty($data['type']) || !in_array($data['type'], ['movie', 'series', 'live_tv'])) {
        $response['message'] = 'Missing required fields: title and type.';
    } else {
        $conn = get_db_connection();
        if ($conn) {
            $conn->begin_transaction();
            try {
                // Prepare statements
                $genre_stmt = $conn->prepare("INSERT INTO genres (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), name=name");
                $content_stmt = $conn->prepare("INSERT INTO content (type, title, description, poster_url, release_year, rating, parental_rating, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $server_stmt = $conn->prepare("INSERT INTO servers (content_id, name, url) VALUES (?, ?, ?)");
                $content_genre_stmt = $conn->prepare("INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)");

                // 1. Insert Genre
                $genre_name = $data['subcategory'] ?? 'General';
                $genre_stmt->bind_param("s", $genre_name);
                $genre_stmt->execute();
                $genre_id = $genre_stmt->insert_id;

                // 2. Insert Content
                $type = $data['type'];
                $title = $data['title'];
                $description = $data['description'] ?? null;
                $poster_url = $data['image'] ?? null;
                $release_year = isset($data['year']) ? (int)$data['year'] : null;
                $rating = isset($data['rating']) ? (float)$data['rating'] : null;
                $parental_rating = $data['parental_rating'] ?? null;
                $country = $data['country'] ?? null;

                $content_stmt->bind_param("ssssidss", $type, $title, $description, $poster_url, $release_year, $rating, $parental_rating, $country);
                $content_stmt->execute();
                $content_id = $content_stmt->insert_id;

                // 3. Link Content and Genre
                $content_genre_stmt->bind_param("ii", $content_id, $genre_id);
                $content_genre_stmt->execute();

                // 4. Insert Servers
                if (isset($data['sources']) && is_array($data['sources'])) {
                    foreach ($data['sources'] as $server) {
                        if (!empty($server['name']) && !empty($server['url'])) {
                            $server_stmt->bind_param("iss", $content_id, $server['name'], $server['url']);
                            $server_stmt->execute();
                        }
                    }
                }

                // Note: This simplified version doesn't handle manual season/episode entry.
                // That would require a more complex UI and data structure in the POST request.

                $conn->commit();
                $response['success'] = true;
                $response['message'] = "Content '{$title}' added successfully!";

            } catch (Exception $e) {
                $conn->rollback();
                $response['message'] = 'Database insert failed: ' . $e->getMessage();
            } finally {
                if(isset($conn)) $conn->close();
            }
        } else {
            $response['message'] = 'Database connection failed.';
        }
    }
}

echo json_encode($response);
?>
