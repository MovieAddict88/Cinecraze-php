<?php
// Include the database configuration to get the PDO object
require_once 'config.php';

try {
    // ---- Drop existing tables if they exist ----
    $pdo->exec("DROP TABLE IF EXISTS users;");
    $pdo->exec("DROP TABLE IF EXISTS movies;");
    $pdo->exec("DROP TABLE IF EXISTS series;");
    $pdo->exec("DROP TABLE IF EXISTS seasons;");
    $pdo->exec("DROP TABLE IF EXISTS episodes;");
    $pdo->exec("DROP TABLE IF EXISTS servers;");
    $pdo->exec("DROP TABLE IF EXISTS genres;");
    $pdo->exec("DROP TABLE IF EXISTS movie_genres;");
    $pdo->exec("DROP TABLE IF EXISTS series_genres;");
    $pdo->exec("DROP TABLE IF EXISTS likes;");
    echo "Dropped existing tables (if any).\n";

    // ---- Create tables ----

    // users table
    $pdo->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");
    echo "Table 'users' created.\n";

    // genres table
    $pdo->exec("CREATE TABLE genres (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL UNIQUE
    )");
    echo "Table 'genres' created.\n";

    // movies table
    $pdo->exec("CREATE TABLE movies (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tmdb_id INTEGER UNIQUE,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        poster_path VARCHAR(255),
        backdrop_path VARCHAR(255),
        release_date DATE,
        rating DECIMAL(3,1),
        duration INTEGER, -- in minutes
        parental_rating VARCHAR(20)
    )");
    echo "Table 'movies' created.\n";

    // series table
    $pdo->exec("CREATE TABLE series (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tmdb_id INTEGER UNIQUE,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        poster_path VARCHAR(255),
        backdrop_path VARCHAR(255),
        first_air_date DATE,
        rating DECIMAL(3,1),
        parental_rating VARCHAR(20)
    )");
    echo "Table 'series' created.\n";

    // seasons table
    $pdo->exec("CREATE TABLE seasons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        series_id INTEGER NOT NULL,
        season_number INTEGER NOT NULL,
        name VARCHAR(255),
        poster_path VARCHAR(255),
        FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
    )");
    echo "Table 'seasons' created.\n";

    // episodes table
    $pdo->exec("CREATE TABLE episodes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        season_id INTEGER NOT NULL,
        episode_number INTEGER NOT NULL,
        title VARCHAR(255),
        description TEXT,
        still_path VARCHAR(255),
        duration INTEGER, -- in minutes
        FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE
    )");
    echo "Table 'episodes' created.\n";

    // servers table (polymorphic)
    $pdo->exec("CREATE TABLE servers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content_id INTEGER NOT NULL,
        content_type VARCHAR(50) NOT NULL, -- 'movie' or 'episode'
        name VARCHAR(255) NOT NULL,
        url TEXT NOT NULL,
        quality VARCHAR(50)
    )");
    echo "Table 'servers' created.\n";

    // movie_genres pivot table
    $pdo->exec("CREATE TABLE movie_genres (
        movie_id INTEGER NOT NULL,
        genre_id INTEGER NOT NULL,
        PRIMARY KEY (movie_id, genre_id),
        FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
    )");
    echo "Table 'movie_genres' created.\n";

    // series_genres pivot table
    $pdo->exec("CREATE TABLE series_genres (
        series_id INTEGER NOT NULL,
        genre_id INTEGER NOT NULL,
        PRIMARY KEY (series_id, genre_id),
        FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE,
        FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
    )");
    echo "Table 'series_genres' created.\n";

    // likes table (polymorphic)
    $pdo->exec("CREATE TABLE likes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content_id INTEGER NOT NULL,
        content_type VARCHAR(50) NOT NULL, -- 'movie' or 'series'
        likes INTEGER DEFAULT 0,
        dislikes INTEGER DEFAULT 0,
        UNIQUE(content_id, content_type)
    )");
    echo "Table 'likes' created.\n";

    // ---- Insert initial data ----

    // Insert admin user
    $username = 'admin';
    $password = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Admin user created (admin/password).\n";

    echo "\nDatabase setup completed successfully!\n";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>
