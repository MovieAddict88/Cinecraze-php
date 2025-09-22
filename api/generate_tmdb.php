<?php
// api/generate_tmdb.php
// Fetches data from TMDB for a given ID and saves it to the database.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];

// --- Hardcoded TMDB API Key (TODO: Move to database settings) ---
define('TMDB_API_KEY', 'ec926176bf467b3f7735e3154238c161');
define('TMDB_API_BASE', 'https://api.themoviedb.org/3');

// --- Helper function to fetch from TMDB ---
function fetch_from_tmdb($endpoint) {
    $url = TMDB_API_BASE . $endpoint . '?api_key=' . TMDB_API_KEY;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// --- Main Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tmdb_id = $_POST['tmdb_id'] ?? null;
    $type = $_POST['type'] ?? null; // 'movie' or 'series'
    $additional_servers = isset($_POST['servers']) ? json_decode($_POST['servers'], true) : [];

    if (empty($tmdb_id) || !in_array($type, ['movie', 'series'])) {
        $response['message'] = 'Missing or invalid TMDB ID or type.';
    } else {
        $conn = get_db_connection();
        if ($conn) {
            $conn->begin_transaction();
            try {
                if ($type === 'movie') {
                    // Fetch movie data from TMDB
                    $movie_data = fetch_from_tmdb("/movie/{$tmdb_id}");
                    if (!$movie_data || isset($movie_data['success']) && !$movie_data['success']) {
                        throw new Exception("Could not fetch movie data from TMDB for ID: $tmdb_id");
                    }

                    // Insert into content table
                    $content_stmt = $conn->prepare("INSERT INTO content (tmdb_id, type, title, description, poster_url, release_year, rating) VALUES (?, 'movie', ?, ?, ?, ?, ?)");
                    $poster_path = 'https://image.tmdb.org/t/p/w500' . $movie_data['poster_path'];
                    $release_year = substr($movie_data['release_date'], 0, 4);
                    $content_stmt->bind_param("ssssid", $tmdb_id, $movie_data['title'], $movie_data['overview'], $poster_path, $release_year, $movie_data['vote_average']);
                    $content_stmt->execute();
                    $content_id = $content_stmt->insert_id;

                    // Insert servers
                    $server_stmt = $conn->prepare("INSERT INTO servers (content_id, name, url) VALUES (?, ?, ?)");
                    foreach ($additional_servers as $server) {
                        $server_stmt->bind_param("iss", $content_id, $server['name'], $server['url']);
                        $server_stmt->execute();
                    }
                    // TODO: Add auto-embed servers based on settings

                    $response['message'] = "Movie '{$movie_data['title']}' added successfully!";

                } elseif ($type === 'series') {
                    // Fetch series data
                    $series_data = fetch_from_tmdb("/tv/{$tmdb_id}");
                    if (!$series_data || isset($series_data['success']) && !$series_data['success']) {
                        throw new Exception("Could not fetch series data from TMDB for ID: $tmdb_id");
                    }

                    // Insert into content table
                    $content_stmt = $conn->prepare("INSERT INTO content (tmdb_id, type, title, description, poster_url, release_year, rating) VALUES (?, 'series', ?, ?, ?, ?, ?)");
                    $poster_path = 'https://image.tmdb.org/t/p/w500' . $series_data['poster_path'];
                    $release_year = substr($series_data['first_air_date'], 0, 4);
                    $content_stmt->bind_param("ssssid", $tmdb_id, $series_data['name'], $series_data['overview'], $poster_path, $release_year, $series_data['vote_average']);
                    $content_stmt->execute();
                    $content_id = $content_stmt->insert_id;

                    // Insert seasons and episodes
                    $season_stmt = $conn->prepare("INSERT INTO seasons (content_id, season_number, title) VALUES (?, ?, ?)");
                    $episode_stmt = $conn->prepare("INSERT INTO episodes (season_id, episode_number, title) VALUES (?, ?, ?)");

                    foreach ($series_data['seasons'] as $season_info) {
                        $season_number = $season_info['season_number'];
                        // Fetch details for each season
                        $season_details = fetch_from_tmdb("/tv/{$tmdb_id}/season/{$season_number}");
                        if (!$season_details) continue;

                        $season_stmt->bind_param("iis", $content_id, $season_number, $season_info['name']);
                        $season_stmt->execute();
                        $season_id = $season_stmt->insert_id;

                        foreach ($season_details['episodes'] as $episode_info) {
                            $episode_stmt->bind_param("iis", $season_id, $episode_info['episode_number'], $episode_info['name']);
                            $episode_stmt->execute();
                            $episode_id = $episode_stmt->insert_id;
                            // TODO: Add servers for each episode
                        }
                    }
                    $response['message'] = "Series '{$series_data['name']}' added successfully!";
                }

                $conn->commit();
                $response['success'] = true;

            } catch (Exception $e) {
                $conn->rollback();
                $response['message'] = 'Database import failed: ' . $e->getMessage();
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
