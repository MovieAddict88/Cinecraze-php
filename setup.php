<?php
// Include the database configuration
require_once 'includes/config.php';

echo "<h1>CineCraze Database Setup</h1>";

// SQL statements to create tables
$sql = "
-- Users table for admin authentication
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Main content table for movies, series, and live TV
CREATE TABLE IF NOT EXISTS `content` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tmdb_id` INT,
    `type` ENUM('movie', 'series', 'live') NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `poster_url` VARCHAR(255),
    `thumbnail_url` VARCHAR(255),
    `release_year` INT,
    `rating` DECIMAL(3, 1),
    `duration` VARCHAR(50),
    `parental_rating` VARCHAR(50),
    `country` VARCHAR(100),
    `genres` JSON,
    `views` INT DEFAULT 0,
    `likes` INT DEFAULT 0,
    `dislikes` INT DEFAULT 0,
    `servers` JSON, -- For movies and live TV, servers are stored here
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_type` (`type`),
    INDEX `idx_tmdb_id` (`tmdb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seasons table for TV series
CREATE TABLE IF NOT EXISTS `seasons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `content_id` INT NOT NULL,
    `season_number` INT NOT NULL,
    `title` VARCHAR(255),
    `poster_url` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`content_id`) REFERENCES `content`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Episodes table for TV series
CREATE TABLE IF NOT EXISTS `episodes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `season_id` INT NOT NULL,
    `episode_number` INT NOT NULL,
    `title` VARCHAR(255),
    `description` TEXT,
    `thumbnail_url` VARCHAR(255),
    `duration` VARCHAR(50),
    `servers` JSON,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`season_id`) REFERENCES `seasons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Watch Later table
CREATE TABLE IF NOT EXISTS `watch_later` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `content_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`content_id`) REFERENCES `content`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `user_content` (`user_id`, `content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings table for admin panel configurations
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Execute the multi-query
if ($conn->multi_query($sql)) {
    echo "<p style='color: green;'>Tables creating... ";
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check if there are more results
        if ($conn->more_results()) {
            echo "Processing next result... ";
        }
    } while ($conn->next_result());
    echo "All tables created successfully or already exist.</p>";

    // Add a default admin user if one doesn't exist
    $adminUser = 'admin';

    // Use a prepared statement to check if the user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $adminUser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Generate a random, secure password
        $newPassword = bin2hex(random_bytes(8)); // 16 characters long
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);

        $insertStmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $adminUser, $hashedPass);

        if ($insertStmt->execute()) {
            echo "<p style='color: blue;'>Default admin user created.</p>";
            echo "<p>Username: <strong>" . htmlspecialchars($adminUser) . "</strong></p>";
            echo "<p>Password: <strong style='background: #333; padding: 5px; border-radius: 5px;'>" . htmlspecialchars($newPassword) . "</strong></p>";
            echo "<p style='color: red;'><strong>IMPORTANT:</strong> Please save this password and change it immediately after logging in.</p>";
        } else {
            echo "<p style='color: red;'>Error creating default admin user: " . $conn->error . "</p>";
        }
        $insertStmt->close();
    } else {
        echo "<p style='color: orange;'>Admin user already exists. No new admin user created.</p>";
    }
    $stmt->close();

} else {
    echo "<p style='color: red;'>Error creating tables: " . $conn->error . "</p>";
}

// Close the connection
$conn->close();

echo "<h2>Setup Complete!</h2>";
echo "<p>You can now delete this `setup.php` file for security.</p>";

?>
