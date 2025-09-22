-- CineCraze Database Schema
-- This script defines the table structure for the CineCraze application.
-- It is designed to be scalable and normalized, moving away from a single JSON file.

--
-- Table structure for `admins`
-- Stores credentials for the admin panel.
--
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Stores hashed passwords',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `content`
-- A central table for all media types (movies, series, live TV).
--
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` varchar(20) DEFAULT NULL,
  `type` enum('movie','series','live_tv') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `poster_url` varchar(255) DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `release_year` int(4) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `parental_rating` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tmdb_id` (`tmdb_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `seasons`
-- Stores season-specific information for TV series.
--
CREATE TABLE `seasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `season_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_season` (`content_id`,`season_number`),
  CONSTRAINT `seasons_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `episodes`
-- Stores episode-specific information.
--
CREATE TABLE `episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `season_episode` (`season_id`,`episode_number`),
  CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `servers`
-- Stores video server links for movies, episodes, and live TV.
--
CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL COMMENT 'Link to content (for movies/live_tv)',
  `episode_id` int(11) DEFAULT NULL COMMENT 'Link to episodes (for series)',
  `name` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `quality` varchar(20) DEFAULT NULL,
  `type` enum('direct','embed','hls','dash') NOT NULL DEFAULT 'direct',
  `drm_license_url` text,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `episode_id` (`episode_id`),
  CONSTRAINT `servers_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  CONSTRAINT `servers_ibfk_2` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `genres`
-- Stores all available genres.
--
CREATE TABLE `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `content_genres`
-- Maps content to their respective genres (many-to-many relationship).
--
CREATE TABLE `content_genres` (
  `content_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`genre_id`),
  KEY `genre_id` (`genre_id`),
  CONSTRAINT `content_genres_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `interactions`
-- Stores real-time counts for likes, dislikes, and views.
--
CREATE TABLE `interactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_id` (`content_id`),
  CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `settings`
-- Stores application-wide settings like API keys and embed preferences.
--
CREATE TABLE `settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Default settings
--
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('tmdb_api_key', ''),
('auto_embed_servers', '[]');

--
-- Default admin user
-- It is highly recommended to change this password immediately.
-- Default: admin / password
--
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$T.a.y.l.o.r.S.w.i.f.t.S.u.p.e.r.S.t.a.r'); -- Replace with a secure hash for 'password' in a real setup.
