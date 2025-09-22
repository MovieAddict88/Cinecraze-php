<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$conn = db_connect();

function fetchData() {
    global $conn;

    $cineData = [
        'Categories' => []
    ];

    // --- Fetch Movies and Live TV ---
    $movies_category = ['MainCategory' => 'Movies', 'SubCategories' => [], 'Entries' => []];
    $livetv_category = ['MainCategory' => 'Live TV', 'SubCategories' => [], 'Entries' => []];

    $sql_movies = "SELECT * FROM movies ORDER BY release_date DESC";
    $result_movies = $conn->query($sql_movies);

    $movie_servers = [];
    $sql_movie_servers = "SELECT * FROM servers WHERE content_type = 'movie'";
    $result_movie_servers = $conn->query($sql_movie_servers);
    while($row = $result_movie_servers->fetch_assoc()) {
        if (!isset($movie_servers[$row['content_id']])) {
            $movie_servers[$row['content_id']] = [];
        }
        $movie_servers[$row['content_id']][] = ['name' => $row['server_name'], 'url' => $row['server_url']];
    }

    while ($movie = $result_movies->fetch_assoc()) {
        $entry = [
            'Title' => $movie['title'],
            'SubCategory' => 'General', // Genre info needs to be added
            'Country' => '', // Needs to be added
            'Description' => $movie['description'],
            'Poster' => $movie['poster_path'],
            'Thumbnail' => $movie['poster_path'],
            'Rating' => (float)$movie['rating'],
            'Duration' => gmdate("H:i:s", $movie['runtime'] * 60),
            'Year' => (int)date('Y', strtotime($movie['release_date'])),
            'parentalRating' => $movie['parental_rating'],
            'Servers' => isset($movie_servers[$movie['id']]) ? $movie_servers[$movie['id']] : []
        ];

        // This is a simple way to differentiate Live TV for now
        // A better way would be a dedicated genre or type field
        if (strpos(strtolower($movie['title']), 'live') !== false || strpos(strtolower($movie['description']), 'live') !== false) {
             $livetv_category['Entries'][] = $entry;
        } else {
             $movies_category['Entries'][] = $entry;
        }
    }

    // --- Fetch TV Series ---
    $series_category = ['MainCategory' => 'TV Series', 'SubCategories' => [], 'Entries' => []];
    $sql_series = "SELECT * FROM tv_series ORDER BY first_air_date DESC";
    $result_series = $conn->query($sql_series);

    while ($series = $result_series->fetch_assoc()) {
        $series_entry = [
            'Title' => $series['title'],
            'SubCategory' => 'General', // Genre info
            'Country' => '', // Needs to be added
            'Description' => $series['description'],
            'Poster' => $series['poster_path'],
            'Thumbnail' => $series['poster_path'],
            'Rating' => (float)$series['rating'],
            'Year' => (int)date('Y', strtotime($series['first_air_date'])),
            'parentalRating' => $series['parental_rating'],
            'Seasons' => []
        ];

        // Fetch seasons for this series
        $sql_seasons = "SELECT * FROM seasons WHERE series_id = {$series['id']} ORDER BY season_number ASC";
        $result_seasons = $conn->query($sql_seasons);

        while($season = $result_seasons->fetch_assoc()) {
            $season_entry = [
                'Season' => (int)$season['season_number'],
                'SeasonPoster' => $season['poster_path'] ?: $series['poster_path'],
                'Episodes' => []
            ];

            // Fetch episodes for this season
            $sql_episodes = "SELECT * FROM episodes WHERE season_id = {$season['id']} ORDER BY episode_number ASC";
            $result_episodes = $conn->query($sql_episodes);

            $episode_servers = [];
            $sql_episode_servers = "SELECT s.* FROM servers s JOIN episodes e ON s.content_id = e.id WHERE s.content_type = 'episode' AND e.season_id = {$season['id']}";
            $result_episode_servers = $conn->query($sql_episode_servers);
            while($row = $result_episode_servers->fetch_assoc()) {
                if (!isset($episode_servers[$row['content_id']])) {
                    $episode_servers[$row['content_id']] = [];
                }
                $episode_servers[$row['content_id']][] = ['name' => $row['server_name'], 'url' => $row['server_url']];
            }

            while($episode = $result_episodes->fetch_assoc()) {
                $episode_entry = [
                    'Episode' => (int)$episode['episode_number'],
                    'Title' => $episode['title'],
                    'Duration' => gmdate("H:i:s", $episode['runtime'] * 60),
                    'Description' => $episode['description'],
                    'Thumbnail' => $episode['still_path'],
                    'Servers' => isset($episode_servers[$episode['id']]) ? $episode_servers[$episode['id']] : []
                ];
                $season_entry['Episodes'][] = $episode_entry;
            }
            $series_entry['Seasons'][] = $season_entry;
        }
        $series_category['Entries'][] = $series_entry;
    }

    // Add categories to the main data structure
    if (!empty($livetv_category['Entries'])) {
        $cineData['Categories'][] = $livetv_category;
    }
    if (!empty($movies_category['Entries'])) {
        $cineData['Categories'][] = $movies_category;
    }
    if (!empty($series_category['Entries'])) {
        $cineData['Categories'][] = $series_category;
    }

    return $cineData;
}

$data = fetchData();
echo json_encode($data, JSON_PRETTY_PRINT);

$conn->close();
?>
