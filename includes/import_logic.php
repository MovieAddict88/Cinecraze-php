<?php

/**
 * Imports data from a parsed JSON array into the database.
 *
 * @param array $data The decoded JSON data, expected to have a "Categories" key.
 * @param mysqli $conn The database connection object.
 * @return array An array containing the status ('success' or 'error') and a message.
 */
function importJsonData($data, $conn) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        $movies_imported = 0;
        $series_imported = 0;
        $seasons_imported = 0;
        $episodes_imported = 0;
        $servers_imported = 0;

        // Prepare statements for insertion to improve performance and security
        $stmt_movie = $conn->prepare("INSERT INTO movies (title, description, poster_path, release_date, rating, parental_rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_series = $conn->prepare("INSERT INTO tv_series (title, description, poster_path, first_air_date, rating, parental_rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_season = $conn->prepare("INSERT INTO seasons (series_id, season_number, name, poster_path) VALUES (?, ?, ?, ?)");
        $stmt_episode = $conn->prepare("INSERT INTO episodes (season_id, episode_number, title, description, still_path) VALUES (?, ?, ?, ?, ?)");
        $stmt_server = $conn->prepare("INSERT INTO servers (content_id, content_type, server_name, server_url) VALUES (?, ?, ?, ?)");

        foreach ($data['Categories'] as $category) {
            $mainCategory = $category['MainCategory'];

            foreach ($category['Entries'] as $entry) {
                // --- MOVIES ---
                if ($mainCategory === 'Movies') {
                    $release_date = !empty($entry['Year']) ? $entry['Year'] . '-01-01' : null;
                    $stmt_movie->bind_param("ssssds", $entry['Title'], $entry['Description'], $entry['Poster'], $release_date, $entry['Rating'], $entry['parentalRating']);
                    $stmt_movie->execute();
                    $movie_id = $conn->insert_id;
                    $movies_imported++;

                    if (isset($entry['Servers']) && is_array($entry['Servers'])) {
                        foreach ($entry['Servers'] as $server) {
                            $content_type = 'movie';
                            $stmt_server->bind_param("isss", $movie_id, $content_type, $server['name'], $server['url']);
                            $stmt_server->execute();
                            $servers_imported++;
                        }
                    }
                }

                // --- TV SERIES ---
                if ($mainCategory === 'TV Series') {
                    $first_air_date = !empty($entry['Year']) ? $entry['Year'] . '-01-01' : null;
                    $stmt_series->bind_param("ssssds", $entry['Title'], $entry['Description'], $entry['Poster'], $first_air_date, $entry['Rating'], $entry['parentalRating']);
                    $stmt_series->execute();
                    $series_id = $conn->insert_id;
                    $series_imported++;

                    if (isset($entry['Seasons']) && is_array($entry['Seasons'])) {
                        foreach ($entry['Seasons'] as $season) {
                            $stmt_season->bind_param("iiss", $series_id, $season['Season'], $season['Title'], $season['SeasonPoster']);
                            $stmt_season->execute();
                            $season_id = $conn->insert_id;
                            $seasons_imported++;

                            if (isset($season['Episodes']) && is_array($season['Episodes'])) {
                                foreach ($season['Episodes'] as $episode) {
                                    $stmt_episode->bind_param("iisss", $season_id, $episode['Episode'], $episode['Title'], $episode['Description'], $episode['Thumbnail']);
                                    $stmt_episode->execute();
                                    $episode_id = $conn->insert_id;
                                    $episodes_imported++;

                                    if (isset($episode['Servers']) && is_array($episode['Servers'])) {
                                        foreach ($episode['Servers'] as $server) {
                                            $content_type = 'episode';
                                            $stmt_server->bind_param("isss", $episode_id, $content_type, $server['name'], $server['url']);
                                            $stmt_server->execute();
                                            $servers_imported++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                 // --- LIVE TV (Modeled as Movies) ---
                if ($mainCategory === 'Live TV') {
                    // For now, we treat Live TV as a movie with a specific genre (to be added)
                    $release_date = date("Y-m-d"); // Use current date for live channels
                    $rating = 0;
                    $parental = 'N/A';
                    $stmt_movie->bind_param("ssssds", $entry['Title'], $entry['Description'], $entry['Poster'], $release_date, $rating, $parental);
                    $stmt_movie->execute();
                    $movie_id = $conn->insert_id;
                    $movies_imported++; // Count as a movie

                    if (isset($entry['Servers']) && is_array($entry['Servers'])) {
                        foreach ($entry['Servers'] as $server) {
                            $content_type = 'movie';
                            $stmt_server->bind_param("isss", $movie_id, $content_type, $server['name'], $server['url']);
                            $stmt_server->execute();
                            $servers_imported++;
                        }
                    }
                }
            }
        }

        // If we reach here, no errors, so commit the transaction
        $conn->commit();

        $stmt_movie->close();
        $stmt_series->close();
        $stmt_season->close();
        $stmt_episode->close();
        $stmt_server->close();

        return [
            'status' => 'success',
            'message' => "Import successful! Added: {$movies_imported} movies, {$series_imported} series, {$seasons_imported} seasons, {$episodes_imported} episodes, and {$servers_imported} servers."
        ];

    } catch (Exception $e) {
        // An error occurred, roll back the transaction
        $conn->rollback();

        return [
            'status' => 'error',
            'message' => 'An error occurred during the import process: ' . $e->getMessage()
        ];
    }
}
?>
