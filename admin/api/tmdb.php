<?php
include '../config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

// Get the API key index from the request, default to 0
$apiKeyIndex = isset($_GET['api_key_index']) ? intval($_GET['api_key_index']) : 0;

// Validate the index and get the key
if (!defined('TMDB_API_KEYS') || !isset(TMDB_API_KEYS[$apiKeyIndex])) {
    $response['message'] = 'Invalid API key configuration or index.';
    echo json_encode($response);
    exit;
}
$apiKey = TMDB_API_KEYS[$apiKeyIndex];

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = intval($_GET['id']);

    if (($type === 'movie' || $type === 'series') && $id > 0) {
        $tmdb_url = "https://api.themoviedb.org/3/";

        if ($type === 'movie') {
            $tmdb_url .= "movie/{$id}?api_key=" . $apiKey . "&append_to_response=credits,videos,release_dates";
        } else { // series
            $tmdb_url .= "tv/{$id}?api_key=" . $apiKey . "&append_to_response=credits,videos,content_ratings";
        }

        // Use cURL to fetch data from TMDB API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tmdb_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmdb_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $data = json_decode($tmdb_response, true);

            if ($type === 'movie') {
                // Insert movie data into the database
                $stmt = $conn->prepare("INSERT INTO movies (tmdb_id, title, description, poster_path, backdrop_path, release_date, vote_average, parental_rating, duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), poster_path=VALUES(poster_path), backdrop_path=VALUES(backdrop_path), release_date=VALUES(release_date), vote_average=VALUES(vote_average), parental_rating=VALUES(parental_rating), duration=VALUES(duration)");

                $parental_rating = 'N/A';
                if (isset($data['release_dates']['results'])) {
                    foreach ($data['release_dates']['results'] as $release) {
                        if ($release['iso_3166_1'] == 'US') {
                            $parental_rating = $release['release_dates'][0]['certification'];
                            break;
                        }
                    }
                }

                $stmt->bind_param("isssssdsi", $data['id'], $data['title'], $data['overview'], $data['poster_path'], $data['backdrop_path'], $data['release_date'], $data['vote_average'], $parental_rating, $data['runtime']);

                if ($stmt->execute()) {
                    $movie_id = $stmt->insert_id;
                    if($movie_id == 0) {
                        $stmt_get_id = $conn->prepare("SELECT id FROM movies WHERE tmdb_id = ?");
                        $stmt_get_id->bind_param("i", $data['id']);
                        $stmt_get_id->execute();
                        $result = $stmt_get_id->get_result();
                        $movie_id = $result->fetch_assoc()['id'];
                        $stmt_get_id->close();
                    }

                    // Handle genres
                    if (isset($data['genres'])) {
                        $stmt_genre = $conn->prepare("INSERT INTO genres (name) VALUES (?) ON DUPLICATE KEY UPDATE name=VALUES(name)");
                        $stmt_movie_genre = $conn->prepare("INSERT IGNORE INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
                        foreach ($data['genres'] as $genre_data) {
                            $stmt_genre->bind_param("s", $genre_data['name']);
                            $stmt_genre->execute();
                            $genre_id = $stmt_genre->insert_id;
                            if($genre_id == 0) {
                                $stmt_get_genre_id = $conn->prepare("SELECT id FROM genres WHERE name = ?");
                                $stmt_get_genre_id->bind_param("s", $genre_data['name']);
                                $stmt_get_genre_id->execute();
                                $result = $stmt_get_genre_id->get_result();
                                $genre_id = $result->fetch_assoc()['id'];
                                $stmt_get_genre_id->close();
                            }
                            $stmt_movie_genre->bind_param("ii", $movie_id, $genre_id);
                            $stmt_movie_genre->execute();
                        }
                        $stmt_genre->close();
                        $stmt_movie_genre->close();
                    }

                    $response['status'] = 'success';
                    $response['message'] = 'Movie data saved successfully.';
                } else {
                    $response['message'] = 'Error saving movie data: ' . $stmt->error;
                }
                $stmt->close();

            } else if ($type === 'series') {
                // Insert series data into the database
                $stmt = $conn->prepare("INSERT INTO series (tmdb_id, title, description, poster_path, backdrop_path, first_air_date, vote_average, parental_rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), poster_path=VALUES(poster_path), backdrop_path=VALUES(backdrop_path), first_air_date=VALUES(first_air_date), vote_average=VALUES(vote_average), parental_rating=VALUES(parental_rating)");

                $parental_rating = 'N/A';
                if (isset($data['content_ratings']['results'])) {
                    foreach ($data['content_ratings']['results'] as $rating) {
                        if ($rating['iso_3166_1'] == 'US') {
                            $parental_rating = $rating['rating'];
                            break;
                        }
                    }
                }

                $stmt->bind_param("isssssds", $data['id'], $data['name'], $data['overview'], $data['poster_path'], $data['backdrop_path'], $data['first_air_date'], $data['vote_average'], $parental_rating);

                if ($stmt->execute()) {
                    $series_id = $stmt->insert_id;
                    if($series_id == 0) {
                        $stmt_get_id = $conn->prepare("SELECT id FROM series WHERE tmdb_id = ?");
                        $stmt_get_id->bind_param("i", $data['id']);
                        $stmt_get_id->execute();
                        $result = $stmt_get_id->get_result();
                        $series_id = $result->fetch_assoc()['id'];
                        $stmt_get_id->close();
                    }

                    // Handle genres
                    if (isset($data['genres'])) {
                        $stmt_genre = $conn->prepare("INSERT INTO genres (name) VALUES (?) ON DUPLICATE KEY UPDATE name=VALUES(name)");
                        $stmt_series_genre = $conn->prepare("INSERT IGNORE INTO series_genres (series_id, genre_id) VALUES (?, ?)");
                        foreach ($data['genres'] as $genre_data) {
                            $stmt_genre->bind_param("s", $genre_data['name']);
                            $stmt_genre->execute();
                            $genre_id = $stmt_genre->insert_id;
                            if($genre_id == 0) {
                                $stmt_get_genre_id = $conn->prepare("SELECT id FROM genres WHERE name = ?");
                                $stmt_get_genre_id->bind_param("s", $genre_data['name']);
                                $stmt_get_genre_id->execute();
                                $result = $stmt_get_genre_id->get_result();
                                $genre_id = $result->fetch_assoc()['id'];
                                $stmt_get_genre_id->close();
                            }
                            $stmt_series_genre->bind_param("ii", $series_id, $genre_id);
                            $stmt_series_genre->execute();
                        }
                        $stmt_genre->close();
                        $stmt_series_genre->close();
                    }

                    // Handle seasons and episodes
                    if (isset($data['seasons'])) {
                        $stmt_season = $conn->prepare("INSERT INTO seasons (series_id, season_number, name, poster_path, air_date) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), poster_path=VALUES(poster_path), air_date=VALUES(air_date)");
                        $stmt_episode = $conn->prepare("INSERT INTO episodes (season_id, episode_number, title, description, still_path, air_date, duration, vote_average) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), still_path=VALUES(still_path), air_date=VALUES(air_date), duration=VALUES(duration), vote_average=VALUES(vote_average)");

                        foreach ($data['seasons'] as $season_data) {
                            $stmt_season->bind_param("iisss", $series_id, $season_data['season_number'], $season_data['name'], $season_data['poster_path'], $season_data['air_date']);
                            $stmt_season->execute();
                            $season_id = $stmt_season->insert_id;
                            if($season_id == 0) {
                                $stmt_get_season_id = $conn->prepare("SELECT id FROM seasons WHERE series_id = ? AND season_number = ?");
                                $stmt_get_season_id->bind_param("ii", $series_id, $season_data['season_number']);
                                $stmt_get_season_id->execute();
                                $result = $stmt_get_season_id->get_result();
                                $season_id = $result->fetch_assoc()['id'];
                                $stmt_get_season_id->close();
                            }

                            // Fetch episodes for the season
                            $episodes_url = "https://api.themoviedb.org/3/tv/{$id}/season/{$season_data['season_number']}?api_key=" . $apiKey;
                            $ch_ep = curl_init();
                            curl_setopt($ch_ep, CURLOPT_URL, $episodes_url);
                            curl_setopt($ch_ep, CURLOPT_RETURNTRANSFER, 1);
                            $episodes_response = curl_exec($ch_ep);
                            curl_close($ch_ep);
                            $episodes_data = json_decode($episodes_response, true);

                            if (isset($episodes_data['episodes'])) {
                                foreach ($episodes_data['episodes'] as $episode_data) {
                                    $stmt_episode->bind_param("iissssid", $season_id, $episode_data['episode_number'], $episode_data['name'], $episode_data['overview'], $episode_data['still_path'], $episode_data['air_date'], $episode_data['runtime'], $episode_data['vote_average']);
                                    $stmt_episode->execute();
                                }
                            }
                        }
                        $stmt_season->close();
                        $stmt_episode->close();
                    }

                    $response['status'] = 'success';
                    $response['message'] = 'Series data saved successfully.';
                } else {
                    $response['message'] = 'Error saving series data: ' . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $response['message'] = 'Failed to fetch data from TMDB. HTTP status: ' . $http_code;
        }
    }
}

echo json_encode($response);
?>
