<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

requireLogin();

switch ($method) {
    case 'GET':
        if ($action === 'search') {
            $query = $_GET['query'] ?? '';
            $type = $_GET['type'] ?? 'movie';
            if ($query) {
                $results = searchTMDB($query, $type);
                echo json_encode($results);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Query parameter required']);
            }
        } elseif ($action === 'details') {
            $id = $_GET['id'] ?? '';
            $type = $_GET['type'] ?? 'movie';
            if ($id) {
                $details = getTMDBDetails($id, $type);
                echo json_encode($details);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID parameter required']);
            }
        } elseif ($action === 'by-year') {
            $year = $_GET['year'] ?? '';
            $type = $_GET['type'] ?? 'movie';
            $region = $_GET['region'] ?? '';
            if ($year) {
                $results = getTMDBByYear($year, $type, $region);
                echo json_encode($results);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Year parameter required']);
            }
        }
        break;
        
    case 'POST':
        if ($action === 'import') {
            $input = json_decode(file_get_contents('php://input'), true);
            $tmdbId = $input['tmdb_id'] ?? '';
            $type = $input['type'] ?? 'movie';
            
            if ($tmdbId) {
                $details = getTMDBDetails($tmdbId, $type);
                if ($details) {
                    $movieData = [
                        'title' => $details['title'] ?? $details['name'],
                        'description' => $details['overview'],
                        'poster' => 'https://image.tmdb.org/t/p/w500' . $details['poster_path'],
                        'backdrop' => 'https://image.tmdb.org/t/p/w1280' . $details['backdrop_path'],
                        'year' => substr($details['release_date'] ?? $details['first_air_date'], 0, 4),
                        'genre' => implode(', ', array_column($details['genres'] ?? [], 'name')),
                        'rating' => $details['vote_average'],
                        'duration' => isset($details['runtime']) ? formatDuration($details['runtime']) : '',
                        'country' => implode(', ', array_column($details['production_countries'] ?? [], 'name')),
                        'language' => $details['original_language'],
                        'tmdb_id' => $tmdbId,
                        'type' => $type
                    ];
                    
                    if (addMovie($movieData)) {
                        echo json_encode(['success' => true, 'message' => 'Movie imported successfully']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to import movie']);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Movie not found on TMDB']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'TMDB ID required']);
            }
        } elseif ($action === 'bulk-import') {
            $input = json_decode(file_get_contents('php://input'), true);
            $year = $input['year'] ?? '';
            $type = $input['type'] ?? 'movie';
            $region = $input['region'] ?? '';
            $limit = $input['limit'] ?? 20;
            
            if ($year) {
                $results = getTMDBByYear($year, $type, $region);
                $imported = 0;
                
                foreach (array_slice($results['results'] ?? [], 0, $limit) as $item) {
                    $movieData = [
                        'title' => $item['title'] ?? $item['name'],
                        'description' => $item['overview'],
                        'poster' => 'https://image.tmdb.org/t/p/w500' . $item['poster_path'],
                        'backdrop' => 'https://image.tmdb.org/t/p/w1280' . $item['backdrop_path'],
                        'year' => substr($item['release_date'] ?? $item['first_air_date'], 0, 4),
                        'genre' => '',
                        'rating' => $item['vote_average'],
                        'duration' => '',
                        'country' => '',
                        'language' => $item['original_language'],
                        'tmdb_id' => $item['id'],
                        'type' => $type
                    ];
                    
                    if (addMovie($movieData)) {
                        $imported++;
                    }
                }
                
                echo json_encode(['success' => true, 'message' => "Imported $imported movies successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Year parameter required']);
            }
        }
        break;
}
?>