<?php
// API handler for TMDB Import

// The main router includes db.php and functions.php
// We need to handle the request here.
allow_methods('POST');
handle_tmdb_import($conn);


/**
 * Main handler for TMDB import.
 */
function handle_tmdb_import($conn) {
    if (TMDB_API_KEY == 'YOUR_TMDB_API_KEY_HERE') {
        send_error_response(503, "TMDB API Key not configured. Please set it in config/db.php.");
    }

    $input = get_json_input();
    $tmdb_id = intval($input['tmdb_id'] ?? 0);
    $type = $input['type'] ?? ''; // 'movie' or 'tv'

    if (empty($tmdb_id) || !in_array($type, ['movie', 'tv'])) {
        send_error_response(400, "tmdb_id and a valid type ('movie' or 'tv') are required.");
    }

    // Check if content already exists
    $stmt_check = $conn->prepare("SELECT id FROM content WHERE tmdb_id = ? AND content_type = ?");
    $stmt_check->bind_param("is", $tmdb_id, $type);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        send_error_response(409, "Conflict: This content (TMDb ID: {$tmdb_id}) already exists in the database.");
    }
    $stmt_check->close();


    if ($type === 'movie') {
        import_movie($conn, $tmdb_id);
    } else {
        import_tv_show($conn, $tmdb_id);
    }
}

/**
 * Fetches data from TMDB and imports a movie.
 */
function import_movie($conn, $tmdb_id) {
    // 1. Fetch movie details and credits from TMDB
    $movie_details = fetch_tmdb_data("movie/{$tmdb_id}");
    $credits = fetch_tmdb_data("movie/{$tmdb_id}/credits");

    if (!$movie_details || !$credits) {
        send_error_response(404, "Could not fetch movie data from TMDb. Check the ID.");
    }

    // 2. Find the director from credits
    $director = 'N/A';
    foreach ($credits['crew'] as $crew_member) {
        if ($crew_member['job'] === 'Director') {
            $director = $crew_member['name'];
            break;
        }
    }

    // --- Transaction ---
    $conn->begin_transaction();
    try {
        // 3. Get or create genre IDs
        $genre_names = array_column($movie_details['genres'], 'name');
        $genre_ids = get_or_create_genre_ids($conn, $genre_names);

        // 4. Insert into 'content' table
        $sql = "INSERT INTO content (tmdb_id, title, overview, poster_path, backdrop_path, release_date, content_type, rating, director) VALUES (?, ?, ?, ?, ?, ?, 'movie', ?, ?)";
        $stmt = $conn->prepare($sql);

        $poster_path = $movie_details['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie_details['poster_path'] : null;
        $backdrop_path = $movie_details['backdrop_path'] ? "https://image.tmdb.org/t/p/original" . $movie_details['backdrop_path'] : null;

        $stmt->bind_param(
            "isssssds",
            $tmdb_id,
            $movie_details['title'],
            $movie_details['overview'],
            $poster_path,
            $backdrop_path,
            $movie_details['release_date'],
            $movie_details['vote_average'],
            $director
        );
        $stmt->execute();
        $content_id = $conn->insert_id;
        $stmt->close();

        // 5. Insert into 'content_genres' table
        if (!empty($genre_ids)) {
            $genre_sql = "INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)";
            $genre_stmt = $conn->prepare($genre_sql);
            foreach ($genre_ids as $genre_id) {
                $genre_stmt->bind_param("ii", $content_id, $genre_id);
                $genre_stmt->execute();
            }
            $genre_stmt->close();
        }

        // --- Commit Transaction ---
        $conn->commit();
        send_success_response(['id' => $content_id, 'title' => $movie_details['title'], 'message' => 'Movie imported successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        send_error_response(500, "Database transaction failed: " . $e->getMessage());
    }
}


/**
 * Helper function to fetch data from the TMDB API using cURL.
 */
function fetch_tmdb_data($endpoint) {
    $url = "https://api.themoviedb.org/3/{$endpoint}?api_key=" . TMDB_API_KEY;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CineCrazeApp/1.0');
    $output = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        return null;
    }
    return json_decode($output, true);
}

/**
 * Takes an array of genre names, finds their IDs, creates them if they don't exist,
 * and returns an array of their IDs.
 */
function get_or_create_genre_ids($conn, $genre_names) {
    if (empty($genre_names)) {
        return [];
    }
    $genre_ids = [];
    $placeholders = implode(',', array_fill(0, count($genre_names), '?'));
    $types = str_repeat('s', count($genre_names));

    // Find existing genres
    $sql_find = "SELECT id, name FROM genres WHERE name IN ({$placeholders})";
    $stmt_find = $conn->prepare($sql_find);
    $stmt_find->bind_param($types, ...$genre_names);
    $stmt_find->execute();
    $result = $stmt_find->get_result();
    $existing_genres = [];
    while ($row = $result->fetch_assoc()) {
        $genre_ids[] = $row['id'];
        $existing_genres[] = $row['name'];
    }
    $stmt_find->close();

    // Determine which genres are new
    $new_genres = array_diff($genre_names, $existing_genres);

    // Create new genres
    if (!empty($new_genres)) {
        $sql_insert = "INSERT INTO genres (name) VALUES (?)";
        $stmt_insert = $conn->prepare($sql_insert);
        foreach ($new_genres as $name) {
            $stmt_insert->bind_param("s", $name);
            $stmt_insert->execute();
            $genre_ids[] = $conn->insert_id;
        }
        $stmt_insert->close();
    }

    return $genre_ids;
}

/**
 * Fetches data from TMDB and imports a TV show, including its seasons and episodes.
 */
function import_tv_show($conn, $tmdb_id) {
    // 1. Fetch main TV show details
    $show_details = fetch_tmdb_data("tv/{$tmdb_id}");
    if (!$show_details) {
        send_error_response(404, "Could not fetch TV show data from TMDb. Check the ID.");
    }

    // --- Transaction ---
    $conn->begin_transaction();
    try {
        // 2. Get or create genre IDs
        $genre_names = array_column($show_details['genres'], 'name');
        $genre_ids = get_or_create_genre_ids($conn, $genre_names);

        // 3. Insert main series info into 'content' table
        $sql_content = "INSERT INTO content (tmdb_id, title, overview, poster_path, backdrop_path, release_date, content_type, rating) VALUES (?, ?, ?, ?, ?, ?, 'series', ?)";
        $stmt_content = $conn->prepare($sql_content);

        $poster_path = $show_details['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $show_details['poster_path'] : null;
        $backdrop_path = $show_details['backdrop_path'] ? "https://image.tmdb.org/t/p/original" . $show_details['backdrop_path'] : null;

        $stmt_content->bind_param(
            "isssssd",
            $tmdb_id,
            $show_details['name'],
            $show_details['overview'],
            $poster_path,
            $backdrop_path,
            $show_details['first_air_date'],
            $show_details['vote_average']
        );
        $stmt_content->execute();
        $content_id = $conn->insert_id;
        $stmt_content->close();

        // 4. Link genres
        if (!empty($genre_ids)) {
            $genre_sql = "INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)";
            $genre_stmt = $conn->prepare($genre_sql);
            foreach ($genre_ids as $genre_id) {
                $genre_stmt->bind_param("ii", $content_id, $genre_id);
                $genre_stmt->execute();
            }
            $genre_stmt->close();
        }

        // 5. Loop through seasons and episodes
        foreach ($show_details['seasons'] as $season_data) {
            // Skip "Specials" seasons if they have season number 0
            if ($season_data['season_number'] == 0) continue;

            // Insert season into 'seasons' table
            $sql_season = "INSERT INTO seasons (content_id, season_number, name, overview, poster_path, air_date) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_season = $conn->prepare($sql_season);

            $season_poster = $season_data['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $season_data['poster_path'] : null;

            $stmt_season->bind_param(
                "iissss",
                $content_id,
                $season_data['season_number'],
                $season_data['name'],
                $season_data['overview'],
                $season_poster,
                $season_data['air_date']
            );
            $stmt_season->execute();
            $season_id = $conn->insert_id;
            $stmt_season->close();

            // Fetch details for this specific season to get all its episodes
            $season_details = fetch_tmdb_data("tv/{$tmdb_id}/season/{$season_data['season_number']}");
            if ($season_details && isset($season_details['episodes'])) {
                foreach ($season_details['episodes'] as $episode_data) {
                    // Insert episode into 'episodes' table
                    $sql_episode = "INSERT INTO episodes (season_id, episode_number, title, overview, still_path, air_date) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_episode = $conn->prepare($sql_episode);

                    $still_path = $episode_data['still_path'] ? "https://image.tmdb.org/t/p/w300" . $episode_data['still_path'] : null;

                    $stmt_episode->bind_param(
                        "iissss",
                        $season_id,
                        $episode_data['episode_number'],
                        $episode_data['name'],
                        $episode_data['overview'],
                        $still_path,
                        $episode_data['air_date']
                    );
                    $stmt_episode->execute();
                    $stmt_episode->close();
                }
            }
        }

        // --- Commit Transaction ---
        $conn->commit();
        send_success_response(['id' => $content_id, 'title' => $show_details['name'], 'message' => 'TV Show imported successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        send_error_response(500, "Database transaction failed: " . $e->getMessage());
    }
}
?>
