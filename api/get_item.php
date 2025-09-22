<?php
// api/get_item.php
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

// 1. Validate Input
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['type'])) {
    error_response('Invalid ID or type provided.');
}
$id = intval($_GET['id']);
$type = $_GET['type'];

if ($type !== 'movie' && $type !== 'series') {
    error_response('Invalid content type specified.');
}

try {
    $data = null;
    if ($type === 'movie') {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Fetch genres
            $stmt_genres = $pdo->prepare("
                SELECT g.id, g.name
                FROM genres g
                INNER JOIN movie_genres mg ON g.id = mg.genre_id
                WHERE mg.movie_id = ?
            ");
            $stmt_genres->execute([$id]);
            $data['genres'] = $stmt_genres->fetchAll(PDO::FETCH_ASSOC);
        }

    } elseif ($type === 'series') {
        $stmt = $pdo->prepare("SELECT * FROM series WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Fetch genres for series
            $stmt_genres = $pdo->prepare("
                SELECT g.id, g.name
                FROM genres g
                INNER JOIN series_genres sg ON g.id = sg.genre_id
                WHERE sg.series_id = ?
            ");
            $stmt_genres->execute([$id]);
            $data['genres'] = $stmt_genres->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        error_response(ucfirst($type) . ' not found.');
    }

} catch (Exception $e) {
    error_response('Database error: ' . $e->getMessage());
}
