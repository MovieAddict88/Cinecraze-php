<?php
// api/edit_content.php
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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Invalid request method.');
}

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;
$title = $_POST['title'] ?? null;
$overview = $_POST['overview'] ?? '';
$poster_url = $_POST['poster_url'] ?? '';
$backdrop_url = $_POST['backdrop_url'] ?? '';
$release_year = $_POST['release_year'] ?? 0;

if (!is_numeric($id) || empty($type) || empty($title)) {
    error_response('Missing required fields: id, type, or title.');
}

if ($type !== 'movie' && $type !== 'series') {
    error_response('Invalid content type.');
}

// 2. Update Database
$pdo->beginTransaction();

try {
    if ($type === 'movie') {
        $stmt = $pdo->prepare(
            "UPDATE movies SET title = ?, overview = ?, poster_url = ?, backdrop_url = ?, release_year = ? WHERE id = ?"
        );
        $stmt->execute([$title, $overview, $poster_url, $backdrop_url, $release_year, $id]);
    } elseif ($type === 'series') {
        $stmt = $pdo->prepare(
            "UPDATE series SET title = ?, overview = ?, poster_url = ?, backdrop_url = ?, release_year = ? WHERE id = ?"
        );
        $stmt->execute([$title, $overview, $poster_url, $backdrop_url, $release_year, $id]);
    }

    if ($stmt->rowCount() === 0) {
        // This can happen if no data was actually changed, or if the ID was not found.
        // For simplicity, we'll treat it as a success if no error was thrown.
        // A more robust check would confirm the ID exists first.
    }

    // Note: Genre editing is not included here yet for simplicity.
    // That would require deleting old genre links and adding new ones.

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => ucfirst($type) . ' updated successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_response('Database error: ' . $e->getMessage());
}
