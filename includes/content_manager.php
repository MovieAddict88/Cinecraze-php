<?php

/**
 * Fetches content from the database with pagination and filtering.
 *
 * @param mysqli $conn The database connection.
 * @param array $options An array of options including 'page', 'type', and 'search'.
 * @return array An array containing the content items and total pages.
 */
function getContent($conn, $options = []) {
    $limit = 50; // Items per page
    $page = isset($options['page']) ? (int)$options['page'] : 1;
    $offset = ($page - 1) * $limit;
    $type = isset($options['type']) ? $options['type'] : 'all';
    $search = isset($options['search']) ? $options['search'] : '';

    $where_clauses = [];
    $params = [];
    $param_types = '';

    if (!empty($search)) {
        $where_clauses[] = "title LIKE ?";
        $params[] = "%" . $search . "%";
        $param_types .= 's';
    }

    $results = [];
    $total_items = 0;

    // --- Fetch Movies ---
    if ($type === 'all' || $type === 'movie') {
        $sql_movies = "SELECT id, 'movie' as type, title, poster_path, release_date as date FROM movies";
        $sql_count_movies = "SELECT COUNT(id) as total FROM movies";

        $movie_where = $where_clauses;
        if (!empty($movie_where)) {
            $sql_movies .= " WHERE " . implode(' AND ', $movie_where);
            $sql_count_movies .= " WHERE " . implode(' AND ', $movie_where);
        }

        // Get total count for movies
        $stmt_count = $conn->prepare($sql_count_movies);
        if (!empty($params)) {
            $stmt_count->bind_param($param_types, ...$params);
        }
        $stmt_count->execute();
        $count_result = $stmt_count->get_result()->fetch_assoc();
        $total_items += $count_result['total'];
        $stmt_count->close();

        // Get paginated results for movies
        $sql_movies .= " ORDER BY date DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql_movies);
        $current_params = $params;
        $current_params[] = $limit;
        $current_params[] = $offset;
        $current_param_types = $param_types . 'ii';
        $stmt->bind_param($current_param_types, ...$current_params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        $stmt->close();
    }

    // --- Fetch TV Series ---
    if ($type === 'all' || $type === 'series') {
        $sql_series = "SELECT id, 'series' as type, title, poster_path, first_air_date as date FROM tv_series";
        $sql_count_series = "SELECT COUNT(id) as total FROM tv_series";

        $series_where = $where_clauses;
         if (!empty($series_where)) {
            $sql_series .= " WHERE " . implode(' AND ', $series_where);
            $sql_count_series .= " WHERE " . implode(' AND ', $series_where);
        }

        // Get total count for series
        $stmt_count = $conn->prepare($sql_count_series);
        if (!empty($params)) {
            $stmt_count->bind_param($param_types, ...$params);
        }
        $stmt_count->execute();
        $count_result = $stmt_count->get_result()->fetch_assoc();
        $total_items += $count_result['total'];
        $stmt_count->close();

        // Get paginated results for series
        $sql_series .= " ORDER BY date DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql_series);
        $current_params = $params;
        $current_params[] = $limit;
        $current_params[] = $offset;
        $current_param_types = $param_types . 'ii';
        $stmt->bind_param($current_param_types, ...$current_params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        $stmt->close();
    }

    // Sort combined results by date
    usort($results, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return [
        'items' => array_slice($results, 0, $limit), // Ensure we only return one page worth
        'total_pages' => ceil($total_items / $limit)
    ];
}

/**
 * Deletes a content item from the database.
 *
 * @param mysqli $conn The database connection.
 * @param int $id The ID of the content to delete.
 * @param string $type The type of content ('movie' or 'series').
 * @return bool True on success, false on failure.
 */
function deleteContent($conn, $id, $type) {
    if ($type === 'movie') {
        $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    } elseif ($type === 'series') {
        $stmt = $conn->prepare("DELETE FROM tv_series WHERE id = ?");
    } else {
        return false;
    }

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

?>
