<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

// Get the raw POST data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
    exit;
}

$contentType = $data['contentType'] ?? '';

$conn->begin_transaction();

try {
    switch ($contentType) {
        case 'movie':
            $sql = "INSERT INTO movies (title, description, poster_path, backdrop_path, release_date, rating, parental_rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $year = !empty($data['year']) ? $data['year'] . '-01-01' : null;
            $stmt->bind_param(
                "sssssds",
                $data['title'],
                $data['description'],
                $data['poster'],
                $data['poster'], // Using poster for backdrop as well for manual input
                $year,
                $data['rating'],
                $data['parentalRating']
            );
            $stmt->execute();
            $movie_id = $conn->insert_id;
            $stmt->close();

            // Insert servers
            if (!empty($data['sources'])) {
                $sql_server = "INSERT INTO servers (content_id, content_type, name, url, quality, is_embed) VALUES (?, 'movie', ?, ?, ?, ?)";
                $stmt_server = $conn->prepare($sql_server);
                foreach ($data['sources'] as $source) {
                    $is_embed = ($source['type'] === 'embed');
                    $stmt_server->bind_param(
                        "isssi",
                        $movie_id,
                        $source['name'],
                        $source['url'],
                        $source['quality'],
                        $is_embed
                    );
                    $stmt_server->execute();
                }
                $stmt_server->close();
            }
            break;

        case 'series':
            $sql = "INSERT INTO tv_series (name, overview, poster_path, backdrop_path, first_air_date, rating, parental_rating, number_of_seasons) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $year = !empty($data['year']) ? $data['year'] . '-01-01' : null;
            $num_seasons = count($data['seasons']);
            $stmt->bind_param(
                "sssssdsi",
                $data['title'],
                $data['description'],
                $data['poster'],
                $data['poster'],
                $year,
                $data['rating'],
                $data['parentalRating'],
                $num_seasons
            );
            $stmt->execute();
            $tv_series_id = $conn->insert_id;
            $stmt->close();

            // Loop through seasons
            foreach ($data['seasons'] as $season_data) {
                $sql_season = "INSERT INTO seasons (tv_series_id, season_number, name) VALUES (?, ?, ?)";
                $stmt_season = $conn->prepare($sql_season);
                $stmt_season->bind_param("iis", $tv_series_id, $season_data['number'], $season_data['name']);
                $stmt_season->execute();
                $season_id = $conn->insert_id;
                $stmt_season->close();

                // Loop through episodes
                foreach ($season_data['episodes'] as $episode_data) {
                    $sql_episode = "INSERT INTO episodes (season_id, episode_number, name) VALUES (?, ?, ?)";
                    $stmt_episode = $conn->prepare($sql_episode);
                    $stmt_episode->bind_param("iis", $season_id, $episode_data['number'], $episode_data['name']);
                    $stmt_episode->execute();
                    $episode_id = $conn->insert_id;
                    $stmt_episode->close();

                    // Insert servers for the episode
                    if (!empty($episode_data['sources'])) {
                        $sql_server = "INSERT INTO servers (content_id, content_type, name, url, quality, is_embed) VALUES (?, 'episode', ?, ?, ?, ?)";
                        $stmt_server = $conn->prepare($sql_server);
                        foreach ($episode_data['sources'] as $source) {
                             $is_embed = ($source['type'] === 'embed');
                             $stmt_server->bind_param(
                                "isssi",
                                $episode_id,
                                $source['name'],
                                $source['url'],
                                $source['quality'],
                                $is_embed
                            );
                            $stmt_server->execute();
                        }
                        $stmt_server->close();
                    }
                }
            }
            break;

        case 'live':
            $sql = "INSERT INTO live_tv (name, description, logo_path, stream_url, category) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // Assume the first source is the primary stream URL
            $stream_url = $data['sources'][0]['url'] ?? '';
            $stmt->bind_param(
                "sssss",
                $data['title'],
                $data['description'],
                $data['poster'],
                $stream_url,
                $data['subcategory']
            );
            $stmt->execute();
            $stmt->close();
            break;

        default:
            throw new Exception("Invalid content type specified.");
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => ucfirst($contentType) . ' added successfully!']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>
