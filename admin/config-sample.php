<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'cinecraze');

// TMDB API Key - IMPORTANT: For production, use environment variables instead of hardcoding.
// Get your key from https://www.themoviedb.org/settings/api
define('TMDB_API_KEY', '');

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
