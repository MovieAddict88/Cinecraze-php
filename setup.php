<?php
// Simple script to set up the database tables.
// This should be run once after configuring config/db.php.

// Include the database configuration and connection file.
// The @ supresses warnings if the file is not found, we handle that case below.
@include_once 'config/db.php';

// Check if the database connection was successful.
if (!isset($conn) || $conn->connect_error) {
    die("<h1>Database Configuration Error</h1>" .
        "<p>Could not connect to the database. Please check your settings in <strong>config/db.php</strong>.</p>" .
        "<p><strong>Error:</strong> " . (isset($conn) ? $conn->connect_error : "Configuration file not found or invalid.") . "</p>");
}

echo "<h1>Database Setup</h1>";
echo "<p>Database connection successful. Attempting to create tables...</p>";

// Path to the schema file
$schema_file = 'database_schema.sql';

if (!file_exists($schema_file)) {
    die("<p style='color: red;'><strong>Error:</strong> {$schema_file} not found.</p>");
}

// Read the SQL file
$sql_queries = file_get_contents($schema_file);

// Execute the multi-query
if ($conn->multi_query($sql_queries)) {
    // Clear the results of the multi-query
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());

    echo "<p style='color: green;'><strong>Success!</strong> Database tables created successfully.</p>";
    echo "<p>You can now delete this <strong>setup.php</strong> file for security.</p>";
    echo "<p>Default admin login: <br>Username: <strong>admin</strong> <br>Password: <strong>password</strong></p>";

} else {
    echo "<p style='color: red;'><strong>Error creating tables:</strong> " . $conn->error . "</p>";
}

// Close the connection
$conn->close();
?>
