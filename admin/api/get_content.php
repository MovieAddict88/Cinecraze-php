<?php
include_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Failed to fetch content.'];

$movies = [];
$series = [];

// Fetch movies
$movie_sql = "SELECT * FROM movies ORDER BY created_at DESC";
$movie_result = $conn->query($movie_sql);
if ($movie_result) {
    while ($row = $movie_result->fetch_assoc()) {
        $movies[] = $row;
    }
}

// Fetch series
$series_sql = "SELECT * FROM series ORDER BY created_at DESC";
$series_result = $conn->query($series_sql);
if ($series_result) {
    while ($row = $series_result->fetch_assoc()) {
        $series[] = $row;
    }
}

$response['status'] = 'success';
$response['message'] = 'Content fetched successfully.';
$response['data'] = [
    'movies' => $movies,
    'series' => $series
];

echo json_encode($response);
?>
