<?php
include 'config.php';

// SQL to create tables
$sql = "
CREATE TABLE IF NOT EXISTS movies (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tmdb_id INT(11) UNIQUE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    poster_path VARCHAR(255),
    backdrop_path VARCHAR(255),
    release_date DATE,
    vote_average DECIMAL(3,1),
    parental_rating VARCHAR(20),
    duration INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS series (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tmdb_id INT(11) UNIQUE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    poster_path VARCHAR(255),
    backdrop_path VARCHAR(255),
    first_air_date DATE,
    vote_average DECIMAL(3,1),
    parental_rating VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS seasons (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    series_id INT(11) NOT NULL,
    season_number INT(11) NOT NULL,
    name VARCHAR(255),
    poster_path VARCHAR(255),
    air_date DATE,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS episodes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    season_id INT(11) NOT NULL,
    episode_number INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    still_path VARCHAR(255),
    air_date DATE,
    duration INT(11),
    vote_average DECIMAL(3,1),
    FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS servers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    content_id INT(11) NOT NULL,
    content_type ENUM('movie', 'episode') NOT NULL,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    quality VARCHAR(50),
    server_type VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS genres (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS movie_genres (
    movie_id INT(11) NOT NULL,
    genre_id INT(11) NOT NULL,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS series_genres (
    series_id INT(11) NOT NULL,
    genre_id INT(11) NOT NULL,
    PRIMARY KEY (series_id, genre_id),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);
";

// Execute multi query
if ($conn->multi_query($sql)) {
    echo "Tables created successfully";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>
