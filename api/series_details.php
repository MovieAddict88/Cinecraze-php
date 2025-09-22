<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

$series_id = isset($_GET['series_id']) ? intval($_GET['series_id']) : 0;

if ($series_id === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid Series ID']);
    exit;
}

$seasons_data = [];

// Fetch seasons for the given series ID
$seasons_sql = "SELECT id, season_number, name, overview, poster_path, air_date FROM seasons WHERE tv_series_id = ? ORDER BY season_number ASC";
$stmt_seasons = $conn->prepare($seasons_sql);
$stmt_seasons->bind_param("i", $series_id);
$stmt_seasons->execute();
$seasons_result = $stmt_seasons->get_result();

while ($season_row = $seasons_result->fetch_assoc()) {
    $season_id = $season_row['id'];
    $episodes_data = [];

    // Fetch episodes for the current season ID
    $episodes_sql = "SELECT id, episode_number, name, overview, still_path FROM episodes WHERE season_id = ? ORDER BY episode_number ASC";
    $stmt_episodes = $conn->prepare($episodes_sql);
    $stmt_episodes->bind_param("i", $season_id);
    $stmt_episodes->execute();
    $episodes_result = $stmt_episodes->get_result();

    while ($episode_row = $episodes_result->fetch_assoc()) {
        $episode_id = $episode_row['id'];

        $episodes_data[] = [
            'id' => $episode_id,
            'Episode' => $episode_row['episode_number'],
            'Title' => $episode_row['name'],
            'Description' => $episode_row['overview'],
            'Thumbnail' => $episode_row['still_path'],
            // Servers will be fetched by the player using the episode ID
        ];
    }
    $stmt_episodes->close();

    $seasons_data[] = [
        'Season' => $season_row['season_number'],
        'SeasonPoster' => $season_row['poster_path'],
        'Episodes' => $episodes_data
    ];
}
$stmt_seasons->close();

echo json_encode(['success' => true, 'seasons' => $seasons_data]);

$conn->close();
?>
