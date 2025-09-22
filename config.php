<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinecraze');

// Admin credentials (change these for security)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Change this password

// TMDB API configuration
define('TMDB_API_KEY', 'your_tmdb_api_key_here');
define('TMDB_BASE_URL', 'https://api.themoviedb.org/3');

// Site configuration
define('SITE_URL', 'http://localhost');
define('UPLOAD_PATH', 'uploads/');

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Auto-create database and tables
function setupDatabase() {
    try {
        // Create database if not exists
        $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        
        // Connect to the database
        $pdo = getDBConnection();
        
        // Create movies table
        $pdo->exec("CREATE TABLE IF NOT EXISTS movies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            poster VARCHAR(500),
            backdrop VARCHAR(500),
            year INT,
            genre VARCHAR(255),
            rating DECIMAL(3,1),
            duration VARCHAR(50),
            country VARCHAR(100),
            language VARCHAR(100),
            tmdb_id INT,
            type ENUM('movie', 'tv') DEFAULT 'movie',
            status ENUM('active', 'inactive') DEFAULT 'active',
            likes INT DEFAULT 0,
            dislikes INT DEFAULT 0,
            views INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // Create servers table
        $pdo->exec("CREATE TABLE IF NOT EXISTS servers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            movie_id INT,
            server_name VARCHAR(100),
            server_url TEXT,
            server_type ENUM('direct', 'embed', 'hls', 'live') DEFAULT 'direct',
            quality VARCHAR(50),
            language VARCHAR(50),
            status ENUM('active', 'inactive') DEFAULT 'active',
            embed_enabled BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
        )");
        
        // Create admin_settings table
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // Create user_interactions table for likes/dislikes
        $pdo->exec("CREATE TABLE IF NOT EXISTS user_interactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            movie_id INT,
            user_ip VARCHAR(45),
            interaction_type ENUM('like', 'dislike', 'view'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
            UNIQUE KEY unique_interaction (movie_id, user_ip, interaction_type)
        )");
        
        return true;
    } catch(PDOException $e) {
        error_log("Database setup error: " . $e->getMessage());
        return false;
    }
}

// Initialize database
setupDatabase();
?>