<?php
require_once 'config.php';

echo "<h1>CineCraze Setup</h1>";

// Create a new database connection for setup
$setup_db_host = DB_HOST;
$setup_db_user = DB_USER;
$setup_db_pass = DB_PASS;
$setup_db_name = DB_NAME;

try {
    // Connect to MySQL server without specifying a database
    $pdo = new PDO("mysql:host=$setup_db_host", $setup_db_user, $setup_db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$setup_db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "<p>Database '{$setup_db_name}' created or already exists.</p>";

    // Now connect to the specific database
    $db = new Database($setup_db_host, $setup_db_name, $setup_db_user, $setup_db_pass);
    $pdo = $db->connect();

    // Create tables
    echo "<h2>Creating tables...</h2>";
    $result = $db->createTables();
    echo "<p>{$result}</p>";

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();

    if (!$user) {
        echo "<h2>Creating default admin user...</h2>";
        $username = 'admin';
        $password = 'password';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashed_password])) {
            echo "<p>Default admin user created successfully.</p>";
            echo "<p><b>Username:</b> admin</p>";
            echo "<p><b>Password:</b> password</p>";
            echo "<p style='color:red;'><b>IMPORTANT:</b> Please change this password after your first login!</p>";
        } else {
            echo "<p style='color:red;'>Error: Could not create default admin user.</p>";
        }
    } else {
        echo "<h2>Admin user already exists.</h2>";
        echo "<p>Skipping admin user creation.</p>";
    }

    echo "<h2>Setup Complete!</h2>";
    echo "<p>You can now delete this `setup.php` file for security.</p>";
    echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>";

} catch (PDOException $e) {
    die("<p style='color:red;'>Setup failed: " . $e->getMessage() . "</p>");
}
?>
