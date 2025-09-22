<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/database.php';

// Function to fetch data from a URL using cURL
function fetch_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CineCraze/1.0');
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($http_code !== 200) {
        return null;
    }
    return json_decode($data, true);
}

// Get POST data
$tmdb_id = isset($_POST['tmdb_id']) ? intval($_POST['tmdb_id']) : 0;
$content_type = $_POST['content_type'] ?? '';
$api_key = TMDB_API_KEY;

if (empty($tmdb_id) || empty($content_type) || $api_key === 'YOUR_TMDB_API_KEY') {
    echo json_encode(['success' => false, 'message' => 'Invalid TMDB ID, content type, or missing TMDB API key.']);
    exit;
}

// --- MOVIE GENERATION ---
if ($content_type === 'movie') {
    // Check if movie already exists
    $stmt_check = $conn->prepare("SELECT id FROM movies WHERE tmdb_id = ?");
    $stmt_check->bind_param("i", $tmdb_id);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Movie with this TMDB ID already exists in the database.']);
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();

    // Fetch movie details from TMDB
    $movie_url = "https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$api_key}&language=en-US";
    $movie_data = fetch_url($movie_url);

    if (!$movie_data) {
        echo json_encode(['success' => false, 'message' => 'Could not fetch movie data from TMDB. Check the ID.']);
        exit;
    }

    // Insert movie into database
    $sql = "INSERT INTO movies (tmdb_id, title, description, poster_path, backdrop_path, release_date, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $poster_path = $movie_data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie_data['poster_path'] : null;
    $backdrop_path = $movie_data['backdrop_path'] ? 'https://image.tmdb.org/t/p/original' . $movie_data['backdrop_path'] : null;

    $stmt->bind_param(
        "isssssd",
        $movie_data['id'],
        $movie_data['title'],
        $movie_data['overview'],
        $poster_path,
        $backdrop_path,
        $movie_data['release_date'],
        $movie_data['vote_average']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Movie "' . htmlspecialchars($movie_data['title']) . '" added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add movie to database. Error: ' . $stmt->error]);
    }
    $stmt->close();
}
// --- TV SERIES GENERATION ---
elseif ($content_type === 'tv') {
    // Check if TV series already exists
    $stmt_check = $conn->prepare("SELECT id FROM tv_series WHERE tmdb_id = ?");
    $stmt_check->bind_param("i", $tmdb_id);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'TV series with this TMDB ID already exists in the database.']);
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();

    // Fetch main TV series data
    $tv_url = "https://api.themoviedb.org/3/tv/{$tmdb_id}?api_key={$api_key}&language=en-US";
    $tv_data = fetch_url($tv_url);

    if (!$tv_data) {
        echo json_encode(['success' => false, 'message' => 'Could not fetch TV series data from TMDB. Check the ID.']);
        exit;
    }

    $conn->begin_transaction();
    try {
        // Insert main series record
        $sql_series = "INSERT INTO tv_series (tmdb_id, name, overview, poster_path, backdrop_path, first_air_date, rating, number_of_seasons, number_of_episodes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_series = $conn->prepare($sql_series);

        $poster_path = $tv_data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $tv_data['poster_path'] : null;
        $backdrop_path = $tv_data['backdrop_path'] ? 'https://image.tmdb.org/t/p/original' . $tv_data['backdrop_path'] : null;

        $stmt_series->bind_param(
            "isssssdii",
            $tv_data['id'],
            $tv_data['name'],
            $tv_data['overview'],
            $poster_path,
            $backdrop_path,
            $tv_data['first_air_date'],
            $tv_data['vote_average'],
            $tv_data['number_of_seasons'],
            $tv_data['number_of_episodes']
        );
        $stmt_series->execute();
        $tv_series_id = $conn->insert_id;
        $stmt_series->close();

        // Fetch and insert each season and its episodes
        foreach ($tv_data['seasons'] as $season_info) {
            // Skip seasons with 0 episodes or no air date (specials)
            if ($season_info['episode_count'] == 0 || empty($season_info['air_date'])) {
                continue;
            }

            $season_url = "https://api.themoviedb.org/3/tv/{$tmdb_id}/season/{$season_info['season_number']}?api_key={$api_key}&language=en-US";
            $season_data = fetch_url($season_url);
            if (!$season_data) continue; // Skip if a season can't be fetched

            // Insert season
            $sql_season = "INSERT INTO seasons (tv_series_id, tmdb_id, season_number, name, overview, poster_path, air_date, episode_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_season = $conn->prepare($sql_season);
            $season_poster = $season_data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $season_data['poster_path'] : null;
            $stmt_season->bind_param(
                "iiissssi",
                $tv_series_id,
                $season_data['id'],
                $season_data['season_number'],
                $season_data['name'],
                $season_data['overview'],
                $season_poster,
                $season_data['air_date'],
                count($season_data['episodes'])
            );
            $stmt_season->execute();
            $season_id = $conn->insert_id;
            $stmt_season->close();

            // Insert episodes for the season
            if (!empty($season_data['episodes'])) {
                $sql_episode = "INSERT INTO episodes (season_id, tmdb_id, episode_number, name, overview, still_path, air_date, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_episode = $conn->prepare($sql_episode);
                foreach ($season_data['episodes'] as $ep_data) {
                    $still_path = $ep_data['still_path'] ? 'https://image.tmdb.org/t/p/w500' . $ep_data['still_path'] : null;
                    $stmt_episode->bind_param(
                        "iiissssd",
                        $season_id,
                        $ep_data['id'],
                        $ep_data['episode_number'],
                        $ep_data['name'],
                        $ep_data['overview'],
                        $still_path,
                        $ep_data['air_date'],
                        $ep_data['vote_average']
                    );
                    $stmt_episode->execute();
                }
                $stmt_episode->close();
            }
        }
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'TV Series "' . htmlspecialchars($tv_data['name']) . '" and all its seasons/episodes added successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'A database error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid content type specified.']);
}

$conn->close();
?>
