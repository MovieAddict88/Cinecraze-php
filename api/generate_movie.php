<?php
// api/generate_movie.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../config.php';
require_once '../includes/database.php';

// Function to return a JSON error response
function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

// 1. Get and Validate Input
if (!isset($_POST['tmdb_id']) || !is_numeric($_POST['tmdb_id'])) {
    error_response('Invalid TMDB ID provided.');
}
$tmdb_id = intval($_POST['tmdb_id']);

// Check if TMDB API key is available
if (empty(TMDB_API_KEY) || TMDB_API_KEY === 'YOUR_TMDB_API_KEY') {
    error_response('TMDB API key is not configured on the server.');
}

// 2. Fetch data from TMDB API
$api_url = "https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key=" . TMDB_API_KEY;
$api_response = @file_get_contents($api_url);

if ($api_response === false) {
    error_response('Failed to fetch data from TMDB API. The movie might not exist or the API is down.');
}

$movie_data = json_decode($api_response, true);

if (!$movie_data || isset($movie_data['success']) && $movie_data['success'] === false) {
    error_response('Invalid data received from TMDB API. TMDB ID might be incorrect.');
}

// 3. Extract relevant data
$title = $movie_data['title'] ?? 'N/A';
$overview = $movie_data['overview'] ?? '';
$poster_path = $movie_data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie_data['poster_path'] : '';
$backdrop_path = $movie_data['backdrop_path'] ? 'https://image.tmdb.org/t/p/w1280' . $movie_data['backdrop_path'] : '';
$release_date = $movie_data['release_date'] ?? '';
$year = $release_date ? date('Y', strtotime($release_date)) : 0;
$genres = $movie_data['genres'] ?? [];

// 4. Insert into Database
$pdo->beginTransaction();

try {
    // Check if movie already exists by TMDB ID
    $stmt_check = $pdo->prepare("SELECT id FROM movies WHERE tmdb_id = ?");
    $stmt_check->execute([$tmdb_id]);
    if ($stmt_check->fetch()) {
        $pdo->rollBack();
        error_response('Movie with this TMDB ID already exists in the database.');
    }

    // Insert movie
    $stmt_movie = $pdo->prepare(
        "INSERT INTO movies (title, overview, poster_url, backdrop_url, release_year, tmdb_id) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt_movie->execute([$title, $overview, $poster_path, $backdrop_path, $year, $tmdb_id]);
    $movie_id = $pdo->lastInsertId();

    // Handle Genres
    foreach ($genres as $genre_data) {
        $genre_name = $genre_data['name'];
        $genre_id_tmdb = $genre_data['id'];

        // Find or create the genre
        $stmt_genre = $pdo->prepare("SELECT id FROM genres WHERE name = ?");
        $stmt_genre->execute([$genre_name]);
        $genre_id = $stmt_genre->fetchColumn();

        if (!$genre_id) {
            $stmt_insert_genre = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
            $stmt_insert_genre->execute([$genre_name]);
            $genre_id = $pdo->lastInsertId();
        }

        // Link movie to genre
        $stmt_movie_genre = $pdo->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
        $stmt_movie_genre->execute([$movie_id, $genre_id]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Movie added successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_response('Database error: ' . $e->getMessage());
}
