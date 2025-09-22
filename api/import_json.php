<?php
// api/import_json.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 300); // 5 minutes
ini_set('memory_limit', '256M');

header('Content-Type: application/json');

require_once '../config.php';
require_once '../includes/database.php';

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Invalid request method.');
}

if (!isset($_FILES['importFile']) || $_FILES['importFile']['error'] !== UPLOAD_ERR_OK) {
    error_response('File upload error. Code: ' . ($_FILES['importFile']['error'] ?? 'Unknown'));
}

$file_path = $_FILES['importFile']['tmp_name'];
$file_content = file_get_contents($file_path);
$data = json_decode($file_content, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_response('Invalid JSON file. Error: ' . json_last_error_msg());
}

if (!isset($data['Categories']) || !is_array($data['Categories'])) {
    error_response('Invalid JSON structure. "Categories" array not found.');
}

$pdo->beginTransaction();
$movies_added = 0;
$series_added = 0;

try {
    foreach ($data['Categories'] as $category) {
        $main_category = $category['MainCategory'] ?? '';
        $entries = $category['Entries'] ?? [];

        foreach ($entries as $entry) {
            $title = $entry['Title'] ?? null;
            if (!$title) continue;

            if (stripos($main_category, 'movie') !== false) {
                // Check for duplicates by title
                $stmt_check = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
                $stmt_check->execute([$title]);
                if ($stmt_check->fetch()) continue; // Skip duplicate

                $stmt_movie = $pdo->prepare(
                    "INSERT INTO movies (title, overview, poster_url, backdrop_url, release_year) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt_movie->execute([
                    $title,
                    $entry['Description'] ?? '',
                    $entry['Thumbnail'] ?? '',
                    $entry['Poster'] ?? '',
                    $entry['Year'] ?? 0
                ]);
                $movies_added++;

            } elseif (stripos($main_category, 'series') !== false) {
                // Check for duplicates by title
                $stmt_check = $pdo->prepare("SELECT id FROM series WHERE title = ?");
                $stmt_check->execute([$title]);
                if ($stmt_check->fetch()) continue; // Skip duplicate

                $stmt_series = $pdo->prepare(
                    "INSERT INTO series (title, overview, poster_url, backdrop_url, release_year) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt_series->execute([
                    $title,
                    $entry['Description'] ?? '',
                    $entry['Thumbnail'] ?? '',
                    $entry['Poster'] ?? '',
                    $entry['Year'] ?? 0
                ]);
                $series_id = $pdo->lastInsertId();
                $series_added++;

                // Import seasons and episodes
                if (isset($entry['Seasons']) && is_array($entry['Seasons'])) {
                    foreach ($entry['Seasons'] as $season_data) {
                        $stmt_season = $pdo->prepare("INSERT INTO seasons (series_id, season_number, poster_url) VALUES (?, ?, ?)");
                        $stmt_season->execute([$series_id, $season_data['Season'] ?? 0, $season_data['SeasonPoster'] ?? '']);
                        $season_id = $pdo->lastInsertId();

                        if (isset($season_data['Episodes']) && is_array($season_data['Episodes'])) {
                            foreach ($season_data['Episodes'] as $episode_data) {
                                $stmt_episode = $pdo->prepare("INSERT INTO episodes (season_id, episode_number, title, overview) VALUES (?, ?, ?, ?)");
                                $stmt_episode->execute([$season_id, $episode_data['Episode'] ?? 0, $episode_data['Title'] ?? '', $episode_data['Description'] ?? '']);
                                $episode_id = $pdo->lastInsertId();

                                // Import servers for the episode
                                if (isset($episode_data['Servers']) && is_array($episode_data['Servers'])) {
                                    foreach ($episode_data['Servers'] as $server_data) {
                                        $stmt_server = $pdo->prepare("INSERT INTO servers (episode_id, name, url) VALUES (?, ?, ?)");
                                        $stmt_server->execute([$episode_id, $server_data['name'] ?? 'Unknown', $server_data['url'] ?? '']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    $pdo->commit();
    echo json_encode([
        'success' => true,
        'message' => "Import successful. Added $movies_added movies and $series_added series."
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_response('Database error during import: ' . $e->getMessage());
}
