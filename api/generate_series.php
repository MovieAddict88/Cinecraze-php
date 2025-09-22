<?php
// api/generate_series.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../config.php';
require_once '../includes/database.php';

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

// 1. Get and Validate Input
if (!isset($_POST['tmdb_id']) || !is_numeric($_POST['tmdb_id'])) {
    error_response('Invalid TMDB ID provided.');
}
$tmdb_id = intval($_POST['tmdb_id']);

if (empty(TMDB_API_KEY) || TMDB_API_KEY === 'YOUR_TMDB_API_KEY') {
    error_response('TMDB API key is not configured on the server.');
}

// 2. Fetch Series Data from TMDB
$series_api_url = "https://api.themoviedb.org/3/tv/{$tmdb_id}?api_key=" . TMDB_API_KEY;
$series_response = @file_get_contents($series_api_url);
if ($series_response === false) {
    error_response('Failed to fetch series data from TMDB API.');
}
$series_data = json_decode($series_response, true);
if (!$series_data || isset($series_data['success']) && $series_data['success'] === false) {
    error_response('Invalid data received from TMDB for the series.');
}

// 3. Begin Database Transaction
$pdo->beginTransaction();

try {
    // Check if series already exists
    $stmt_check = $pdo->prepare("SELECT id FROM series WHERE tmdb_id = ?");
    $stmt_check->execute([$tmdb_id]);
    if ($stmt_check->fetch()) {
        $pdo->rollBack();
        error_response('Series with this TMDB ID already exists.');
    }

    // Insert the series
    $stmt_series = $pdo->prepare(
        "INSERT INTO series (title, overview, poster_url, backdrop_url, release_year, tmdb_id) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt_series->execute([
        $series_data['name'],
        $series_data['overview'],
        'https://image.tmdb.org/t/p/w500' . $series_data['poster_path'],
        'https://image.tmdb.org/t/p/w1280' . $series_data['backdrop_path'],
        date('Y', strtotime($series_data['first_air_date'])),
        $tmdb_id
    ]);
    $series_id = $pdo->lastInsertId();

    // Handle Genres
    foreach ($series_data['genres'] as $genre_data) {
        $stmt_genre = $pdo->prepare("SELECT id FROM genres WHERE name = ?");
        $stmt_genre->execute([$genre_data['name']]);
        $genre_id = $stmt_genre->fetchColumn();
        if (!$genre_id) {
            $stmt_insert_genre = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
            $stmt_insert_genre->execute([$genre_data['name']]);
            $genre_id = $pdo->lastInsertId();
        }
        $stmt_series_genre = $pdo->prepare("INSERT INTO series_genres (series_id, genre_id) VALUES (?, ?)");
        $stmt_series_genre->execute([$series_id, $genre_id]);
    }

    // 4. Fetch and Insert Seasons and Episodes
    foreach ($series_data['seasons'] as $season_summary) {
        // Skip "Specials" seasons if they have season_number 0
        if ($season_summary['season_number'] == 0) {
            continue;
        }

        $season_api_url = "https://api.themoviedb.org/3/tv/{$tmdb_id}/season/{$season_summary['season_number']}?api_key=" . TMDB_API_KEY;
        $season_response = @file_get_contents($season_api_url);
        if ($season_response === false) continue; // Skip if season data can't be fetched
        $season_data = json_decode($season_response, true);

        // Insert season
        $stmt_season = $pdo->prepare(
            "INSERT INTO seasons (series_id, season_number, poster_url) VALUES (?, ?, ?)"
        );
        $stmt_season->execute([
            $series_id,
            $season_data['season_number'],
            'https://image.tmdb.org/t/p/w500' . $season_data['poster_path']
        ]);
        $season_id = $pdo->lastInsertId();

        // Insert episodes for the season
        foreach ($season_data['episodes'] as $episode_data) {
            $stmt_episode = $pdo->prepare(
                "INSERT INTO episodes (season_id, episode_number, title, overview) VALUES (?, ?, ?, ?)"
            );
            $stmt_episode->execute([
                $season_id,
                $episode_data['episode_number'],
                $episode_data['name'],
                $episode_data['overview']
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Series added successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_response('Database error: ' . $e->getMessage());
}
