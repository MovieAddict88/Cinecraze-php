<?php
// api/get_public_content.php

// This script is designed to be publicly accessible.
// It fetches all content from the database and formats it in a JSON structure
// that the original `index.html` (now `index.php`) frontend expects.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin

require_once '../config.php';
require_once '../includes/database.php';

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

try {
    // 1. Fetch all base data in a more optimized way
    $movies_raw = $pdo->query("SELECT * FROM movies")->fetchAll(PDO::FETCH_ASSOC);
    $series_raw = $pdo->query("SELECT * FROM series")->fetchAll(PDO::FETCH_ASSOC);
    $seasons_raw = $pdo->query("SELECT * FROM seasons ORDER BY season_number ASC")->fetchAll(PDO::FETCH_ASSOC);
    $episodes_raw = $pdo->query("SELECT * FROM episodes ORDER BY episode_number ASC")->fetchAll(PDO::FETCH_ASSOC);
    $servers_raw = $pdo->query("SELECT * FROM servers")->fetchAll(PDO::FETCH_ASSOC);
    $movie_genres_raw = $pdo->query("SELECT mg.movie_id, g.name FROM movie_genres mg JOIN genres g ON mg.genre_id = g.id")->fetchAll(PDO::FETCH_ASSOC);
    $series_genres_raw = $pdo->query("SELECT sg.series_id, g.name FROM series_genres sg JOIN genres g ON sg.genre_id = g.id")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Process and map data into associative arrays for easy lookup
    $servers_by_movie = [];
    $servers_by_episode = [];
    foreach ($servers_raw as $server) {
        if ($server['movie_id']) {
            $servers_by_movie[$server['movie_id']][] = ['name' => $server['name'], 'url' => $server['url']];
        }
        if ($server['episode_id']) {
            $servers_by_episode[$server['episode_id']][] = ['name' => $server['name'], 'url' => $server['url']];
        }
    }

    $genres_by_movie = [];
    foreach ($movie_genres_raw as $genre_link) {
        $genres_by_movie[$genre_link['movie_id']][] = $genre_link['name'];
    }

    $genres_by_series = [];
    foreach ($series_genres_raw as $genre_link) {
        $genres_by_series[$genre_link['series_id']][] = $genre_link['name'];
    }

    $episodes_by_season = [];
    foreach ($episodes_raw as $episode) {
        $episodes_by_season[$episode['season_id']][] = $episode;
    }

    $seasons_by_series = [];
    foreach ($seasons_raw as $season) {
        $seasons_by_series[$season['series_id']][] = $season;
    }

    // 3. Build the final structure
    $movies_category = [
        'MainCategory' => 'Movies',
        'Entries' => []
    ];
    foreach ($movies_raw as $movie) {
        $movies_category['Entries'][] = [
            'Title' => $movie['title'],
            'Description' => $movie['overview'],
            'Thumbnail' => $movie['poster_url'],
            'Poster' => $movie['backdrop_url'],
            'Year' => $movie['release_year'],
            'Rating' => 'N/A', // Placeholder, add if you store this
            'Country' => 'N/A', // Placeholder
            'Duration' => 'N/A', // Placeholder
            'SubCategory' => implode(', ', $genres_by_movie[$movie['id']] ?? []),
            'Servers' => $servers_by_movie[$movie['id']] ?? []
        ];
    }

    $series_category = [
        'MainCategory' => 'TV Series',
        'Entries' => []
    ];
    foreach ($series_raw as $series) {
        $seasons_data = [];
        if (isset($seasons_by_series[$series['id']])) {
            foreach ($seasons_by_series[$series['id']] as $season) {
                $episodes_data = [];
                if (isset($episodes_by_season[$season['id']])) {
                    foreach ($episodes_by_season[$season['id']] as $episode) {
                        $episodes_data[] = [
                            'Episode' => $episode['episode_number'],
                            'Title' => $episode['title'],
                            'Description' => $episode['overview'],
                            'Servers' => $servers_by_episode[$episode['id']] ?? []
                        ];
                    }
                }
                $seasons_data[] = [
                    'Season' => $season['season_number'],
                    'SeasonPoster' => $season['poster_url'],
                    'Episodes' => $episodes_data
                ];
            }
        }

        $series_category['Entries'][] = [
            'Title' => $series['title'],
            'Description' => $series['overview'],
            'Thumbnail' => $series['poster_url'],
            'Poster' => $series['backdrop_url'],
            'Year' => $series['release_year'],
            'Rating' => 'N/A',
            'Country' => 'N/A',
            'Duration' => 'N/A',
            'SubCategory' => implode(', ', $genres_by_series[$series['id']] ?? []),
            'Seasons' => $seasons_data
        ];
    }

    // Final JSON structure
    $output = [
        'Categories' => [$movies_category, $series_category]
        // Add other categories like 'Live TV' if needed
    ];

    echo json_encode($output, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    // In a public API, it's better not to expose detailed error messages.
    error_log('Public API Error: ' . $e->getMessage());
    error_response('An error occurred while fetching content.');
}
