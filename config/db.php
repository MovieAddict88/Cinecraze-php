<?php
// Database Configuration
// Replace these with your actual database credentials.

define('DB_HOST', 'your_db_host');       // e.g., sqlXXX.infinityfree.com
define('DB_USER', 'your_db_username');  // e.g., if0_XXX
define('DB_PASS', 'your_db_password');  // Your database password
define('DB_NAME', 'your_db_name');      // e.g., if0_XXX_cinecraze

/**
 * Establishes a database connection and returns the connection object.
 *
 * @return mysqli|false The mysqli connection object on success, or false on failure.
 */
function get_db_connection() {
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        // In a real application, you might want to log this error instead of displaying it.
        error_log("Connection failed: " . $conn->connect_error);
        return false;
    }

    // Set character set to utf8mb4 for full Unicode support
    $conn->set_charset("utf8mb4");

    return $conn;
}

?>
