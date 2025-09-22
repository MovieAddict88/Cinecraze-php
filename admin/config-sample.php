<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'cinecraze');

// TMDB API Keys - IMPORTANT: For production, use environment variables instead of hardcoding.
// Get your keys from https://www.themoviedb.org/settings/api
// ADD YOUR KEYS HERE. You can add more than one.
define('TMDB_API_KEYS', [
    'YOUR_FIRST_TMDB_API_KEY_HERE',
    'YOUR_SECOND_TMDB_API_KEY_HERE'
]);

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
