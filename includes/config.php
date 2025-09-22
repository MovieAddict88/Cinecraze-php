<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cinecraze');

// TMDB API Key
define('TMDB_API_KEY', 'YOUR_TMDB_API_KEY');

// Admin Credentials
define('ADMIN_USERNAME', 'admin');
// It's recommended to change this default password.
// You can generate a new hash at the login screen if you forget it.
define('ADMIN_PASSWORD_HASH', password_hash('password123', PASSWORD_DEFAULT));

// Site URL
define('SITE_URL', 'http://localhost/cinecraze');

// Error Reporting
// Set to 0 for production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Function to establish a database connection.
 * @return mysqli|false
 */
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        // In a real application, you'd want to handle this more gracefully
        // and not expose detailed error messages.
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
