<?php
include_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

// Get the API key index from the request, default to 0
$apiKeyIndex = isset($_GET['api_key_index']) ? intval($_GET['api_key_index']) : 0;

// Validate the index and get the key
if (!defined('TMDB_API_KEYS') || !isset(TMDB_API_KEYS[$apiKeyIndex])) {
    $response['message'] = 'Invalid API key configuration or index.';
    echo json_encode($response);
    exit;
}
$apiKey = TMDB_API_KEYS[$apiKeyIndex];

if (isset($_GET['query']) && isset($_GET['type'])) {
    $query = urlencode($_GET['query']);
    $type = $_GET['type'];

    if (!in_array($type, ['movie', 'tv', 'multi'])) {
        $response['message'] = 'Invalid search type';
        echo json_encode($response);
        exit;
    }

    $tmdb_url = "https://api.themoviedb.org/3/search/{$type}?api_key=" . $apiKey . "&query={$query}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tmdb_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $tmdb_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $data = json_decode($tmdb_response, true);
        $response['status'] = 'success';
        $response['message'] = 'Search successful.';
        $response['data'] = $data;
    } else {
        $response['message'] = 'Failed to fetch data from TMDB. HTTP status: ' . $http_code;
    }
}

echo json_encode($response);
?>
