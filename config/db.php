<?php
// --- Database Configuration ---
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cinecraze');

// --- External API Keys ---
// IMPORTANT: You must add your own TMDB API Key here.
// You can get one for free by signing up at https://www.themoviedb.org/signup
define('TMDB_API_KEY', 'YOUR_TMDB_API_KEY_HERE');


// --- Database Connection ---
// Establish database connection using mysqli
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// You can now include this file and use the $conn variable to interact with the database.
?>
