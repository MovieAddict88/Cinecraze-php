<?php
include_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data) {
        $type = $data['type'];
        $title = $data['title'];
        $subcategory = $data['subcategory'];
        $country = $data['country'];
        $description = $data['description'];
        $image = $data['image'];
        $year = $data['year'];
        $rating = $data['rating'];
        $parental_rating = $data['parental_rating'];
        $servers = $data['servers'];

        if ($type === 'movie') {
            $stmt = $conn->prepare("INSERT INTO movies (title, description, poster_path, release_date, vote_average, parental_rating) VALUES (?, ?, ?, ?, ?, ?)");
            $release_date = "$year-01-01";
            $stmt->bind_param("ssssds", $title, $description, $image, $release_date, $rating, $parental_rating);
            if ($stmt->execute()) {
                $movie_id = $stmt->insert_id;
                // Add servers
                $stmt_server = $conn->prepare("INSERT INTO servers (content_id, content_type, name, url) VALUES (?, 'movie', ?, ?)");
                foreach ($servers as $server) {
                    $stmt_server->bind_param("iss", $movie_id, $server['name'], $server['url']);
                    $stmt_server->execute();
                }
                $stmt_server->close();
                $response['status'] = 'success';
                $response['message'] = 'Movie added successfully.';
            } else {
                $response['message'] = 'Error adding movie: ' . $stmt->error;
            }
            $stmt->close();
        } else if ($type === 'series') {
            $seasons = $data['seasons'];

            $stmt = $conn->prepare("INSERT INTO series (title, description, poster_path, first_air_date, vote_average, parental_rating) VALUES (?, ?, ?, ?, ?, ?)");
            $first_air_date = "$year-01-01";
            $stmt->bind_param("ssssds", $title, $description, $image, $first_air_date, $rating, $parental_rating);

            if ($stmt->execute()) {
                $series_id = $stmt->insert_id;

                $stmt_season = $conn->prepare("INSERT INTO seasons (series_id, season_number) VALUES (?, ?)");
                $stmt_episode = $conn->prepare("INSERT INTO episodes (season_id, episode_number, title) VALUES (?, ?, ?)");
                $stmt_server = $conn->prepare("INSERT INTO servers (content_id, content_type, name, url) VALUES (?, 'episode', ?, ?)");

                foreach ($seasons as $season_data) {
                    $stmt_season->bind_param("ii", $series_id, $season_data['season_number']);
                    $stmt_season->execute();
                    $season_id = $stmt_season->insert_id;

                    foreach ($season_data['episodes'] as $episode_data) {
                        $stmt_episode->bind_param("iis", $season_id, $episode_data['episode_number'], $episode_data['title']);
                        $stmt_episode->execute();
                        $episode_id = $stmt_episode->insert_id;

                        foreach ($episode_data['servers'] as $server) {
                            $stmt_server->bind_param("iss", $episode_id, $server['name'], $server['url']);
                            $stmt_server->execute();
                        }
                    }
                }
                $stmt_season->close();
                $stmt_episode->close();
                $stmt_server->close();

                $response['status'] = 'success';
                $response['message'] = 'Series added successfully.';
            } else {
                $response['message'] = 'Error adding series: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}

echo json_encode($response);
?>
