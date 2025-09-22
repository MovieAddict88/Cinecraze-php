-- Database Schema for CineCraze

-- Users table for admin authentication
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Genres table
CREATE TABLE `genres` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Content table for movies, series, and live TV
CREATE TABLE `content` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `overview` TEXT,
  `poster_path` VARCHAR(255) DEFAULT NULL,
  `backdrop_path` VARCHAR(255) DEFAULT NULL,
  `release_date` DATE DEFAULT NULL,
  `content_type` ENUM('movie', 'series', 'live_tv') NOT NULL,
  `rating` DECIMAL(3,1) DEFAULT 0.0,
  `director` VARCHAR(255) DEFAULT NULL,
  `likes` INT(11) DEFAULT 0,
  `dislikes` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tmdb_id_type` (`tmdb_id`, `content_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Content-Genres mapping table
CREATE TABLE `content_genres` (
  `content_id` INT(11) NOT NULL,
  `genre_id` INT(11) NOT NULL,
  PRIMARY KEY (`content_id`, `genre_id`),
  FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seasons table for TV series
CREATE TABLE `seasons` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content_id` INT(11) NOT NULL,
  `season_number` INT(11) NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `overview` TEXT DEFAULT NULL,
  `poster_path` VARCHAR(255) DEFAULT NULL,
  `air_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `content_season` (`content_id`, `season_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Episodes table for TV series seasons
CREATE TABLE `episodes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `season_id` INT(11) NOT NULL,
  `episode_number` INT(11) NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `overview` TEXT DEFAULT NULL,
  `still_path` VARCHAR(255) DEFAULT NULL,
  `air_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `season_episode` (`season_id`, `episode_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Servers table for streaming links
CREATE TABLE `servers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content_id` INT(11) DEFAULT NULL,
  `episode_id` INT(11) DEFAULT NULL,
  `server_name` VARCHAR(100) NOT NULL,
  `server_url` VARCHAR(512) NOT NULL,
  `server_type` ENUM('direct', 'hls', 'embed', 'drm') NOT NULL,
  `drm_license_url` VARCHAR(512) DEFAULT NULL,
  `is_enabled` BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user
INSERT INTO `users` (`username`, `password`) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'
