<?php
class Database {
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private $pdo;

    public function __construct($host, $dbname, $user, $pass) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect() {
        if ($this->pdo == null) {
            try {
                $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function createTables() {
        $commands = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `genres` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL UNIQUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `movies` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `poster` VARCHAR(255),
                `thumbnail` VARCHAR(255),
                `year` INT,
                `rating` DECIMAL(3,1),
                `duration` VARCHAR(50),
                `country` VARCHAR(100),
                `parental_rating` VARCHAR(20),
                `tmdb_id` VARCHAR(20) UNIQUE,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `likes` INT DEFAULT 0,
                `dislikes` INT DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `series` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `poster` VARCHAR(255),
                `thumbnail` VARCHAR(255),
                `year` INT,
                `rating` DECIMAL(3,1),
                `country` VARCHAR(100),
                `parental_rating` VARCHAR(20),
                `tmdb_id` VARCHAR(20) UNIQUE,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `seasons` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `series_id` INT NOT NULL,
                `season_number` INT NOT NULL,
                `poster` VARCHAR(255),
                FOREIGN KEY (`series_id`) REFERENCES `series`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `episodes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `season_id` INT NOT NULL,
                `episode_number` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `thumbnail` VARCHAR(255),
                `duration` VARCHAR(50),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`season_id`) REFERENCES `seasons`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `servers` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `movie_id` INT,
                `episode_id` INT,
                `name` VARCHAR(255) NOT NULL,
                `url` VARCHAR(1024) NOT NULL,
                `is_embed` BOOLEAN DEFAULT FALSE,
                FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`episode_id`) REFERENCES `episodes`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `movie_genres` (
                `movie_id` INT NOT NULL,
                `genre_id` INT NOT NULL,
                PRIMARY KEY (`movie_id`, `genre_id`),
                FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`genre_id`) REFERENCES `genres`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `series_genres` (
                `series_id` INT NOT NULL,
                `genre_id` INT NOT NULL,
                PRIMARY KEY (`series_id`, `genre_id`),
                FOREIGN KEY (`series_id`) REFERENCES `series`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`genre_id`) REFERENCES `genres`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        try {
            $pdo = $this->connect();
            foreach ($commands as $command) {
                $pdo->exec($command);
            }
            return "Tables created successfully.";
        } catch (PDOException $e) {
            return "Error creating tables: " . $e->getMessage();
        }
    }
}
?>
