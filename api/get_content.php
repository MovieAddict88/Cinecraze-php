<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    // Fetch movies
    $stmt_movies = $pdo->prepare("SELECT * FROM movies ORDER BY created_at DESC");
    $stmt_movies->execute();
    $movies = $stmt_movies->fetchAll(PDO::FETCH_ASSOC);

    // Fetch series
    $stmt_series = $pdo->prepare("
        SELECT
            s.*,
            (SELECT GROUP_CONCAT(g.name SEPARATOR ', ') FROM genres g JOIN series_genres sg ON g.id = sg.genre_id WHERE sg.series_id = s.id) as genres
        FROM series s
        ORDER BY s.created_at DESC
    ");
    $stmt_series->execute();
    $series_list = $stmt_series->fetchAll(PDO::FETCH_ASSOC);

    $series_formatted = [];
    foreach ($series_list as $series_item) {
        // Fetch seasons and episodes for each series
        $stmt_seasons = $pdo->prepare("SELECT * FROM seasons WHERE series_id = ? ORDER BY season_number ASC");
        $stmt_seasons->execute([$series_item['id']]);
        $seasons = $stmt_seasons->fetchAll(PDO::FETCH_ASSOC);

        $seasons_formatted = [];
        foreach ($seasons as $season) {
            $stmt_episodes = $pdo->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC");
            $stmt_episodes->execute([$season['id']]);
            $episodes = $stmt_episodes->fetchAll(PDO::FETCH_ASSOC);
            $season['episodes'] = $episodes;
            $seasons_formatted[] = $season;
        }
        $series_item['seasons'] = $seasons_formatted;
        $series_formatted[] = $series_item;
    }


    echo json_encode([
        'success' => true,
        'movies' => $movies,
        'series' => $series_formatted
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch content: ' . $e->getMessage()
    ]);
}
?>
