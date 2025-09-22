<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            $limit = $_GET['limit'] ?? null;
            $offset = $_GET['offset'] ?? 0;
            $movies = getAllMovies($limit, $offset);
            echo json_encode($movies);
        } elseif ($action === 'get' && isset($_GET['id'])) {
            $movie = getMovieById($_GET['id']);
            if ($movie) {
                $servers = getMovieServers($_GET['id']);
                $movie['servers'] = $servers;
                echo json_encode($movie);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Movie not found']);
            }
        } elseif ($action === 'servers' && isset($_GET['movie_id'])) {
            $servers = getMovieServers($_GET['movie_id']);
            echo json_encode($servers);
        }
        break;
        
    case 'POST':
        requireLogin();
        if ($action === 'add') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (addMovie($input)) {
                echo json_encode(['success' => true, 'message' => 'Movie added successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add movie']);
            }
        } elseif ($action === 'interact' && isset($_GET['id'])) {
            $input = json_decode(file_get_contents('php://input'), true);
            $type = $input['type'] ?? '';
            if (in_array($type, ['like', 'dislike', 'view'])) {
                recordInteraction($_GET['id'], $type);
                $stats = getMovieStats($_GET['id']);
                echo json_encode($stats);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid interaction type']);
            }
        }
        break;
        
    case 'PUT':
        requireLogin();
        if ($action === 'update' && isset($_GET['id'])) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (updateMovie($_GET['id'], $input)) {
                echo json_encode(['success' => true, 'message' => 'Movie updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update movie']);
            }
        }
        break;
        
    case 'DELETE':
        requireLogin();
        if ($action === 'delete' && isset($_GET['id'])) {
            if (deleteMovie($_GET['id'])) {
                echo json_encode(['success' => true, 'message' => 'Movie deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete movie']);
            }
        }
        break;
}
?>