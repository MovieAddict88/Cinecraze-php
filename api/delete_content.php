<?php
// api/delete_content.php
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
if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['type'])) {
    error_response('Invalid ID or type provided.');
}
$id = intval($_POST['id']);
$type = $_POST['type'];

if ($type !== 'movie' && $type !== 'series') {
    error_response('Invalid content type specified.');
}

// 2. Perform Deletion within a Transaction
$pdo->beginTransaction();

try {
    if ($type === 'movie') {
        // First, delete from the linking table
        $stmt_genres = $pdo->prepare("DELETE FROM movie_genres WHERE movie_id = ?");
        $stmt_genres->execute([$id]);

        // Then, delete from the movies table
        $stmt_movie = $pdo->prepare("DELETE FROM movies WHERE id = ?");
        $stmt_movie->execute([$id]);

        if ($stmt_movie->rowCount() === 0) {
            throw new Exception("Movie with ID {$id} not found.");
        }

    } elseif ($type === 'series') {
        // Delete from series_genres linking table
        $stmt_genres = $pdo->prepare("DELETE FROM series_genres WHERE series_id = ?");
        $stmt_genres->execute([$id]);

        // Find all seasons for the series
        $stmt_seasons = $pdo->prepare("SELECT id FROM seasons WHERE series_id = ?");
        $stmt_seasons->execute([$id]);
        $season_ids = $stmt_seasons->fetchAll(PDO::FETCH_COLUMN);

        if (count($season_ids) > 0) {
            // Delete all episodes for those seasons
            $in_clause = implode(',', array_fill(0, count($season_ids), '?'));
            $stmt_episodes = $pdo->prepare("DELETE FROM episodes WHERE season_id IN ($in_clause)");
            $stmt_episodes->execute($season_ids);
        }

        // Delete the seasons
        $stmt_delete_seasons = $pdo->prepare("DELETE FROM seasons WHERE series_id = ?");
        $stmt_delete_seasons->execute([$id]);

        // Finally, delete the series itself
        $stmt_series = $pdo->prepare("DELETE FROM series WHERE id = ?");
        $stmt_series->execute([$id]);

        if ($stmt_series->rowCount() === 0) {
            throw new Exception("Series with ID {$id} not found.");
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => ucfirst($type) . ' deleted successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_response('Database error: ' . $e->getMessage());
}
