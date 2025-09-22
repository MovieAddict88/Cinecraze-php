<?php
// --- IMPORTANT SECURITY NOTICE ---
// These are default credentials for local development.
// PLEASE CHANGE THESE VALUES before deploying to a live server.
// Do not commit this file with production credentials to a public repository.
// ---------------------------------

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cinecraze');

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for full emoji and special character support
$conn->set_charset("utf8mb4");
?>
