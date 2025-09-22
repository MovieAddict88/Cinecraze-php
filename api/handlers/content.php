<?php
// API handler for Content (Movies, Series, Live TV)

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handle_get_content($conn);
        break;
    case 'POST':
        handle_post_content($conn);
        break;
    case 'PUT':
        handle_put_content($conn);
        break;
    case 'DELETE':
        handle_delete_content($conn);
        break;
    default:
        send_error_response(405, "Method Not Allowed");
        break;
}

/**
 * Handles GET requests for content.
 * Fetches all content or a single content item by ID.
 */
function handle_get_content($conn) {
    if (isset($_GET['id'])) {
        // Get a single content item with all its details
        get_single_content_item($conn, intval($_GET['id']));
    } else {
        // Get a list of all content items with pagination and filtering
        get_content_list($conn);
    }
}

/**
 * Fetches a list of content with pagination and filtering.
 */
function get_content_list($conn) {
    // Basic Pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;

    // Filtering
    $where_clauses = [];
    $params = [];
    $types = '';

    if (isset($_GET['type']) && in_array($_GET['type'], ['movie', 'series', 'live_tv'])) {
        $where_clauses[] = "c.content_type = ?";
        $params[] = $_GET['type'];
        $types .= 's';
    }

    if (isset($_GET['search'])) {
        $where_clauses[] = "c.title LIKE ?";
        $params[] = '%' . $_GET['search'] . '%';
        $types .= 's';
    }

    $where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : "";

    // Main Query
    $sql = "SELECT c.id, c.title, c.poster_path, c.release_date, c.content_type, c.rating FROM content c $where_sql ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $content_list = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get total count for pagination
    $count_sql = "SELECT COUNT(*) as total FROM content c $where_sql";
    $stmt_count = $conn->prepare($count_sql);
    // Re-bind params for count query (without limit/offset)
    array_pop($params); // remove offset
    array_pop($params); // remove limit
    $count_types = substr($types, 0, -2);
    if ($count_types) {
        $stmt_count->bind_param($count_types, ...$params);
    }
    $stmt_count->execute();
    $count_result = $stmt_count->get_result()->fetch_assoc();
    $total_records = $count_result['total'];
    $stmt_count->close();


    $response = [
        'page' => $page,
        'limit' => $limit,
        'total_records' => $total_records,
        'total_pages' => ceil($total_records / $limit),
        'data' => $content_list
    ];

    send_success_response($response);
}

/**
 * Fetches a single content item and all its related data.
 */
function get_single_content_item($conn, $id) {
    // 1. Get main content details
    $stmt = $conn->prepare("SELECT * FROM content WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    $stmt->close();

    if (!$content) {
        send_error_response(404, "Content not found");
        return;
    }

    // 2. Get associated genres
    $genre_stmt = $conn->prepare("SELECT g.id, g.name FROM genres g JOIN content_genres cg ON g.id = cg.genre_id WHERE cg.content_id = ?");
    $genre_stmt->bind_param("i", $id);
    $genre_stmt->execute();
    $genres_result = $genre_stmt->get_result();
    $content['genres'] = $genres_result->fetch_all(MYSQLI_ASSOC);
    $genre_stmt->close();

    // 3. Get associated servers (for movies and live_tv)
    if ($content['content_type'] == 'movie' || $content['content_type'] == 'live_tv') {
        $server_stmt = $conn->prepare("SELECT * FROM servers WHERE content_id = ? AND episode_id IS NULL");
        $server_stmt->bind_param("i", $id);
        $server_stmt->execute();
        $servers_result = $server_stmt->get_result();
        $content['servers'] = $servers_result->fetch_all(MYSQLI_ASSOC);
        $server_stmt->close();
    }

    // 4. Get seasons and episodes (for series)
    if ($content['content_type'] == 'series') {
        $season_stmt = $conn->prepare("SELECT * FROM seasons WHERE content_id = ? ORDER BY season_number ASC");
        $season_stmt->bind_param("i", $id);
        $season_stmt->execute();
        $seasons_result = $season_stmt->get_result();
        $seasons = $seasons_result->fetch_all(MYSQLI_ASSOC);
        $season_stmt->close();

        // For each season, get its episodes and their servers
        for ($i = 0; $i < count($seasons); $i++) {
            $season_id = $seasons[$i]['id'];
            $episode_stmt = $conn->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC");
            $episode_stmt->bind_param("i", $season_id);
            $episode_stmt->execute();
            $episodes_result = $episode_stmt->get_result();
            $episodes = $episodes_result->fetch_all(MYSQLI_ASSOC);
            $episode_stmt->close();

            for ($j = 0; $j < count($episodes); $j++) {
                $episode_id = $episodes[$j]['id'];
                $server_stmt = $conn->prepare("SELECT * FROM servers WHERE episode_id = ?");
                $server_stmt->bind_param("i", $episode_id);
                $server_stmt->execute();
                $servers_result = $server_stmt->get_result();
                $episodes[$j]['servers'] = $servers_result->fetch_all(MYSQLI_ASSOC);
                $server_stmt->close();
            }

            $seasons[$i]['episodes'] = $episodes;
        }
        $content['seasons'] = $seasons;
    }

    send_success_response($content);
}

/**
 * Handles POST requests to create a new content item.
 */
function handle_post_content($conn) {
    $input = get_json_input();

    // --- Validation ---
    $required_fields = ['title', 'content_type'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            send_error_response(400, "Field '{$field}' is required.");
        }
    }
    if (!in_array($input['content_type'], ['movie', 'series', 'live_tv'])) {
        send_error_response(400, "Invalid content_type. Must be 'movie', 'series', or 'live_tv'.");
    }

    // --- Transaction ---
    $conn->begin_transaction();

    try {
        // 1. Insert into 'content' table
        $sql = "INSERT INTO content (tmdb_id, title, overview, poster_path, backdrop_path, release_date, content_type, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Set optional fields to null if not provided
        $tmdb_id = !empty($input['tmdb_id']) ? intval($input['tmdb_id']) : null;
        $overview = $input['overview'] ?? null;
        $poster_path = $input['poster_path'] ?? null;
        $backdrop_path = $input['backdrop_path'] ?? null;
        $release_date = !empty($input['release_date']) ? $input['release_date'] : null;
        $rating = !empty($input['rating']) ? floatval($input['rating']) : 0.0;

        $stmt->bind_param(
            "issssssd",
            $tmdb_id,
            $input['title'],
            $overview,
            $poster_path,
            $backdrop_path,
            $release_date,
            $input['content_type'],
            $rating
        );

        $stmt->execute();
        $content_id = $conn->insert_id;
        $stmt->close();

        // 2. Insert into 'content_genres' table
        if (!empty($input['genre_ids']) && is_array($input['genre_ids'])) {
            $genre_sql = "INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)";
            $genre_stmt = $conn->prepare($genre_sql);
            foreach ($input['genre_ids'] as $genre_id) {
                $genre_stmt->bind_param("ii", $content_id, $genre_id);
                $genre_stmt->execute();
            }
            $genre_stmt->close();
        }

        // --- Commit Transaction ---
        $conn->commit();

        // Respond with the newly created item's ID
        send_success_response(['id' => $content_id, 'message' => 'Content created successfully.']);

    } catch (Exception $e) {
        // --- Rollback Transaction on error ---
        $conn->rollback();
        send_error_response(500, "Database transaction failed: " . $e->getMessage());
    }
}

/**
 * Handles PUT requests to update an existing content item.
 */
function handle_put_content($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Content ID is required for update.");
    }
    $id = intval($_GET['id']);
    $input = get_json_input();

    // --- Build dynamic UPDATE query ---
    $fields = [];
    $params = [];
    $types = '';

    // Add fields to update if they exist in the input
    $updatable_fields = ['tmdb_id', 'title', 'overview', 'poster_path', 'backdrop_path', 'release_date', 'content_type', 'rating'];
    foreach ($updatable_fields as $field) {
        if (isset($input[$field])) {
            $fields[] = "{$field} = ?";
            $params[] = $input[$field];
            // Determine type for bind_param
            if (in_array($field, ['tmdb_id'])) $types .= 'i';
            elseif (in_array($field, ['rating'])) $types .= 'd';
            else $types .= 's';
        }
    }

    if (count($fields) == 0 && !isset($input['genre_ids'])) {
        send_error_response(400, "No fields provided to update.");
    }

    // --- Transaction ---
    $conn->begin_transaction();
    try {
        // 1. Update 'content' table if there are fields to update
        if (count($fields) > 0) {
            $sql = "UPDATE content SET " . implode(', ', $fields) . " WHERE id = ?";
            $params[] = $id;
            $types .= 'i';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        }

        // 2. Update 'content_genres' table if genre_ids are provided
        if (isset($input['genre_ids']) && is_array($input['genre_ids'])) {
            // First, delete existing genre associations
            $delete_stmt = $conn->prepare("DELETE FROM content_genres WHERE content_id = ?");
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Then, insert new genre associations
            if (!empty($input['genre_ids'])) {
                $genre_sql = "INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)";
                $genre_stmt = $conn->prepare($genre_sql);
                foreach ($input['genre_ids'] as $genre_id) {
                    $genre_stmt->bind_param("ii", $id, $genre_id);
                    $genre_stmt->execute();
                }
                $genre_stmt->close();
            }
        }

        // --- Commit Transaction ---
        $conn->commit();
        send_success_response(['id' => $id, 'message' => 'Content updated successfully.']);

    } catch (Exception $e) {
        // --- Rollback Transaction on error ---
        $conn->rollback();
        send_error_response(500, "Database transaction failed: " . $e->getMessage());
    }
}

/**
 * Handles DELETE requests to remove a content item.
 */
function handle_delete_content($conn) {
    if (!isset($_GET['id'])) {
        send_error_response(400, "Content ID is required for deletion.");
    }
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_success_response(['message' => "Content with ID {$id} and all its related data deleted successfully."]);
        } else {
            send_error_response(404, "Content not found.");
        }
    } else {
        send_error_response(500, "Database error: " . $stmt->error);
    }
    $stmt->close();
}
?>
