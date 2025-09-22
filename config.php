<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cinecraze');
define('DB_USER', 'root');
define('DB_PASS', '');

// TMDB API Key
define('TMDB_API_KEY', 'YOUR_TMDB_API_KEY');

// Application URL
define('APP_URL', 'http://localhost/cinecraze');

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database class
require_once 'includes/database.php';

// Global database object
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

?>
