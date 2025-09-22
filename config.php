<?php
// Database configuration
define('DB_PATH', __DIR__ . '/database.db');

// Create a new PDO connection
try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If connection fails, stop the script and display an error
    die("Database connection failed: " . $e->getMessage());
}

// TMDB API Key - can be managed here or in a separate settings table later
define('TMDB_API_KEY', 'ec926176bf467b3f7735e3154238c161');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
