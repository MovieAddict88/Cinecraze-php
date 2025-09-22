<?php
// api/import_json.php
// Handles importing data from a JSON file into the database.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['jsonFile']) && $_FILES['jsonFile']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['jsonFile']['tmp_name'];
        $json_string = file_get_contents($file_tmp_path);
        $data = json_decode($json_string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $response['message'] = 'Invalid JSON file. Parse error: ' . json_last_error_msg();
        } elseif (!isset($data['Categories']) || !is_array($data['Categories'])) {
            $response['message'] = 'Invalid JSON structure. Missing "Categories" array.';
        } else {
            $conn = get_db_connection();
            if ($conn) {
                // Start a transaction
                $conn->begin_transaction();

                try {
                    // Prepare statements for reuse
                    $genre_stmt = $conn->prepare("INSERT INTO genres (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), name=name");
                    $content_stmt = $conn->prepare("INSERT INTO content (tmdb_id, type, title, description, poster_url, thumbnail_url, release_year, rating, parental_rating, country, duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $season_stmt = $conn->prepare("INSERT INTO seasons (content_id, season_number, title, poster_url) VALUES (?, ?, ?, ?)");
                    $episode_stmt = $conn->prepare("INSERT INTO episodes (season_id, episode_number, title, description, thumbnail_url, duration) VALUES (?, ?, ?, ?, ?, ?)");
                    $server_stmt = $conn->prepare("INSERT INTO servers (content_id, episode_id, name, url, quality, type) VALUES (?, ?, ?, ?, ?, ?)");
                    $content_genre_stmt = $conn->prepare("INSERT INTO content_genres (content_id, genre_id) VALUES (?, ?)");

                    foreach ($data['Categories'] as $category) {
                        $main_category = $category['MainCategory'] ?? 'Unknown';
                        $content_type = strtolower(str_replace(' ', '_', $main_category));
                        if (!in_array($content_type, ['movies', 'tv_series', 'live_tv'])) {
                            // Normalize type names
                            if ($content_type === 'movies') $content_type = 'movie';
                            if ($content_type === 'tv_series') $content_type = 'series';
                            // continue; // Or handle as a generic type
                        }
                         if ($content_type === 'movies') $content_type = 'movie';
                         if ($content_type === 'tv_series') $content_type = 'series';

                        foreach ($category['Entries'] as $entry) {
                            // 1. Insert Genre
                            $genre_name = $entry['SubCategory'] ?? 'General';
                            $genre_stmt->bind_param("s", $genre_name);
                            $genre_stmt->execute();
                            $genre_id = $genre_stmt->insert_id;

                            // 2. Insert Content
                            $tmdb_id = $entry['TMDB_ID'] ?? null;
                            $title = $entry['Title'] ?? 'Untitled';
                            $desc = $entry['Description'] ?? null;
                            $poster = $entry['Poster'] ?? null;
                            $thumb = $entry['Thumbnail'] ?? $poster;
                            $year = isset($entry['Year']) ? (int)$entry['Year'] : null;
                            $rating = isset($entry['Rating']) ? (float)$entry['Rating'] : null;
                            $parental = $entry['parentalRating'] ?? null;
                            $country = $entry['Country'] ?? null;
                            $duration = $entry['Duration'] ?? null;

                            $content_stmt->bind_param("ssssssissss", $tmdb_id, $content_type, $title, $desc, $poster, $thumb, $year, $rating, $parental, $country, $duration);
                            $content_stmt->execute();
                            $content_id = $content_stmt->insert_id;

                            // 3. Link Content and Genre
                            $content_genre_stmt->bind_param("ii", $content_id, $genre_id);
                            $content_genre_stmt->execute();

                            // 4. Handle Servers (for movies/live_tv) or Seasons (for series)
                            if ($content_type === 'movie' || $content_type === 'live_tv') {
                                if (isset($entry['Servers']) && is_array($entry['Servers'])) {
                                    foreach ($entry['Servers'] as $server) {
                                        $server_name = $server['name'] ?? 'Default Server';
                                        $server_url = $server['url'] ?? '';
                                        $quality = $server['quality'] ?? 'Auto';
                                        $type = $server['type'] ?? 'direct';
                                        $server_stmt->bind_param("iissss", $content_id, $null, $server_name, $server_url, $quality, $type);
                                        $server_stmt->execute();
                                    }
                                }
                            } elseif ($content_type === 'series') {
                                if (isset($entry['Seasons']) && is_array($entry['Seasons'])) {
                                    foreach ($entry['Seasons'] as $season) {
                                        $season_num = $season['Season'] ?? 0;
                                        $season_poster = $season['SeasonPoster'] ?? $poster;
                                        $season_title = $season['Title'] ?? "Season {$season_num}";

                                        $season_stmt->bind_param("iiss", $content_id, $season_num, $season_title, $season_poster);
                                        $season_stmt->execute();
                                        $season_id = $season_stmt->insert_id;

                                        if (isset($season['Episodes']) && is_array($season['Episodes'])) {
                                            foreach ($season['Episodes'] as $episode) {
                                                $ep_num = $episode['Episode'] ?? 0;
                                                $ep_title = $episode['Title'] ?? "Episode {$ep_num}";
                                                $ep_desc = $episode['Description'] ?? null;
                                                $ep_thumb = $episode['Thumbnail'] ?? $thumb;
                                                $ep_duration = $episode['Duration'] ?? null;

                                                $episode_stmt->bind_param("iissss", $season_id, $ep_num, $ep_title, $ep_desc, $ep_thumb, $ep_duration);
                                                $episode_stmt->execute();
                                                $episode_id = $episode_stmt->insert_id;

                                                if (isset($episode['Servers']) && is_array($episode['Servers'])) {
                                                    foreach ($episode['Servers'] as $server) {
                                                        $server_name = $server['name'] ?? 'Default Server';
                                                        $server_url = $server['url'] ?? '';
                                                        $quality = $server['quality'] ?? 'Auto';
                                                        $type = $server['type'] ?? 'direct';
                                                        $server_stmt->bind_param("iissss", $null, $episode_id, $server_name, $server_url, $quality, $type);
                                                        $server_stmt->execute();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // If all queries were successful, commit the transaction
                    $conn->commit();
                    $response['success'] = true;
                    $response['message'] = 'JSON data imported successfully into the database!';

                } catch (mysqli_sql_exception $exception) {
                    // An error occurred, rollback the transaction
                    $conn->rollback();
                    $response['message'] = 'Database import failed: ' . $exception->getMessage();
                } finally {
                    // Close statements and connection
                    if(isset($genre_stmt)) $genre_stmt->close();
                    if(isset($content_stmt)) $content_stmt->close();
                    if(isset($season_stmt)) $season_stmt->close();
                    if(isset($episode_stmt)) $episode_stmt->close();
                    if(isset($server_stmt)) $server_stmt->close();
                    if(isset($content_genre_stmt)) $content_genre_stmt->close();
                    $conn->close();
                }
            } else {
                $response['message'] = 'Database connection failed.';
            }
        }
    } else {
        $response['message'] = 'File upload error or no file uploaded. Error code: ' . ($_FILES['jsonFile']['error'] ?? 'N/A');
    }
}

echo json_encode($response);
?>
