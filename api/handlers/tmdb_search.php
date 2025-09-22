<?php
// API handler for proxying TMDB search requests to hide the API key.

allow_methods('GET');

if (TMDB_API_KEY == 'YOUR_TMDB_API_KEY_HERE') {
    send_error_response(503, "TMDB API Key not configured on the server.");
}

$query = $_GET['query'] ?? '';
$type = $_GET['type'] ?? 'multi'; // 'multi', 'movie', 'tv'

if (empty($query)) {
    send_error_response(400, "A search query is required.");
}
if (!in_array($type, ['multi', 'movie', 'tv'])) {
    send_error_response(400, "Invalid search type.");
}

$endpoint = "search/{$type}";
$url = "https://api.themoviedb.org/3/{$endpoint}?api_key=" . TMDB_API_KEY . "&query=" . urlencode($query);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'CineCrazeApp/1.0');
$output = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code != 200) {
    send_error_response($http_code, "Failed to fetch data from TMDB. Response: " . $output);
} else {
    // Forward the raw JSON response from TMDB
    header("Content-Type: application/json");
    http_response_code(200);
    echo $output;
    exit();
}
?>
