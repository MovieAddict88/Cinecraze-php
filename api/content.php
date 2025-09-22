<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

// Initialize an array to hold all the content
$content = array(
    'Categories' => array()
);

// --- Fetch Movies ---
$movies_sql = "SELECT id, tmdb_id, title, description, poster_path, backdrop_path, release_date, rating, parental_rating FROM movies ORDER BY release_date DESC";
$movies_result = $conn->query($movies_sql);
$movie_entries = array();
if ($movies_result && $movies_result->num_rows > 0) {
    while ($row = $movies_result->fetch_assoc()) {
        $movie_entries[] = array(
            'id' => $row['id'],
            'type' => 'movie',
            'Title' => $row['title'],
            'Description' => $row['description'],
            'Poster' => $row['poster_path'],
            'Thumbnail' => $row['backdrop_path'],
            'Rating' => $row['rating'],
            'Year' => $row['release_date'] ? date('Y', strtotime($row['release_date'])) : null,
            'parentalRating' => $row['parental_rating'] ?? null
            // Servers will be fetched on demand
        );
    }
}
if (!empty($movie_entries)) {
    $content['Categories'][] = array(
        'MainCategory' => 'Movies',
        'Entries' => $movie_entries
    );
}


// --- Fetch TV Series ---
$series_sql = "SELECT id, tmdb_id, name, overview, poster_path, backdrop_path, first_air_date, rating, parental_rating FROM tv_series ORDER BY first_air_date DESC";
$series_result = $conn->query($series_sql);
$series_entries = array();
if ($series_result && $series_result->num_rows > 0) {
    while ($row = $series_result->fetch_assoc()) {
        $series_entries[] = array(
            'id' => $row['id'],
            'type' => 'series',
            'Title' => $row['name'],
            'Description' => $row['overview'],
            'Poster' => $row['poster_path'],
            'Thumbnail' => $row['backdrop_path'],
            'Rating' => $row['rating'],
            'Year' => $row['first_air_date'] ? date('Y', strtotime($row['first_air_date'])) : null,
            'parentalRating' => $row['parental_rating'] ?? null
            // Seasons and episodes will be fetched on demand
        );
    }
}
if (!empty($series_entries)) {
    $content['Categories'][] = array(
        'MainCategory' => 'TV Series',
        'Entries' => $series_entries
    );
}


// --- Fetch Live TV ---
$livetv_sql = "SELECT id, name, description, logo_path, stream_url, category FROM live_tv";
$livetv_result = $conn->query($livetv_sql);
$livetv_entries = array();
if ($livetv_result && $livetv_result->num_rows > 0) {
    while ($row = $livetv_result->fetch_assoc()) {
        $livetv_entries[] = array(
            'id' => $row['id'],
            'type' => 'live',
            'Title' => $row['name'],
            'Description' => $row['description'],
            'Poster' => $row['logo_path'],
            'Thumbnail' => $row['logo_path'],
            'parentalRating' => null, // Live TV might not have ratings
            'Servers' => array(
                array(
                    'name' => 'Live Stream',
                    'url' => $row['stream_url'],
                    'quality' => 'Live'
                )
            )
        );
    }
}
if (!empty($livetv_entries)) {
    $content['Categories'][] = array(
        'MainCategory' => 'Live TV',
        'Entries' => $livetv_entries
    );
}


echo json_encode($content, JSON_PRETTY_PRINT);

$conn->close();
?>
