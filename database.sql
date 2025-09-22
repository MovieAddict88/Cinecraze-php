-- Users table for admin login
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user with password 'password'
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Movies table
CREATE TABLE `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `poster_path` varchar(255) DEFAULT NULL,
  `backdrop_path` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TV series table
CREATE TABLE `tv_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `overview` text,
  `poster_path` varchar(255) DEFAULT NULL,
  `backdrop_path` varchar(255) DEFAULT NULL,
  `first_air_date` date DEFAULT NULL,
  `last_air_date` date DEFAULT NULL,
  `number_of_seasons` int(11) DEFAULT NULL,
  `number_of_episodes` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seasons table
CREATE TABLE `seasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tv_series_id` int(11) NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `season_number` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `overview` text,
  `poster_path` varchar(255) DEFAULT NULL,
  `air_date` date DEFAULT NULL,
  `episode_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tv_series_id`) REFERENCES `tv_series` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Episodes table
CREATE TABLE `episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `episode_number` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `overview` text,
  `still_path` varchar(255) DEFAULT NULL,
  `air_date` date DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Live TV table
CREATE TABLE `live_tv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `logo_path` varchar(255) DEFAULT NULL,
  `stream_url` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Servers table
CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `content_type` enum('movie','episode') NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `quality` varchar(255) DEFAULT NULL,
  `is_embed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Likes table
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `content_type` enum('movie','tv_series') NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_idx` (`content_id`, `content_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
