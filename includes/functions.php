<?php
require_once 'config.php';

// Authentication functions
function isLoggedIn() {
    session_start();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function login($username, $password) {
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function logout() {
    session_start();
    session_destroy();
}

function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// TMDB API functions
function tmdbRequest($endpoint, $params = []) {
    $url = TMDB_BASE_URL . $endpoint . '?api_key=' . TMDB_API_KEY;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function searchTMDB($query, $type = 'movie') {
    return tmdbRequest("/search/$type", ['query' => $query]);
}

function getTMDBDetails($id, $type = 'movie') {
    return tmdbRequest("/$type/$id");
}

function getTMDBByYear($year, $type = 'movie', $region = '') {
    $params = [
        'primary_release_year' => $year,
        'sort_by' => 'popularity.desc'
    ];
    if ($region) {
        $params['region'] = $region;
    }
    return tmdbRequest("/discover/$type", $params);
}

// Database functions
function getAllMovies($limit = null, $offset = 0) {
    $pdo = getDBConnection();
    $sql = "SELECT m.*, COUNT(s.id) as server_count 
            FROM movies m 
            LEFT JOIN servers s ON m.id = s.movie_id AND s.status = 'active'
            WHERE m.status = 'active' 
            GROUP BY m.id 
            ORDER BY m.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $limit OFFSET $offset";
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMovieById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ? AND status = 'active'");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMovieServers($movieId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM servers WHERE movie_id = ? AND status = 'active' ORDER BY id ASC");
    $stmt->execute([$movieId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addMovie($data) {
    $pdo = getDBConnection();
    $sql = "INSERT INTO movies (title, description, poster, backdrop, year, genre, rating, duration, country, language, tmdb_id, type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['title'], $data['description'], $data['poster'], $data['backdrop'],
        $data['year'], $data['genre'], $data['rating'], $data['duration'],
        $data['country'], $data['language'], $data['tmdb_id'], $data['type']
    ]);
}

function updateMovie($id, $data) {
    $pdo = getDBConnection();
    $sql = "UPDATE movies SET title=?, description=?, poster=?, backdrop=?, year=?, genre=?, rating=?, duration=?, country=?, language=?, tmdb_id=?, type=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['title'], $data['description'], $data['poster'], $data['backdrop'],
        $data['year'], $data['genre'], $data['rating'], $data['duration'],
        $data['country'], $data['language'], $data['tmdb_id'], $data['type'], $id
    ]);
}

function deleteMovie($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE movies SET status = 'inactive' WHERE id = ?");
    return $stmt->execute([$id]);
}

function addServer($movieId, $data) {
    $pdo = getDBConnection();
    $sql = "INSERT INTO servers (movie_id, server_name, server_url, server_type, quality, language, embed_enabled) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $movieId, $data['server_name'], $data['server_url'], $data['server_type'],
        $data['quality'], $data['language'], $data['embed_enabled']
    ]);
}

function updateServer($id, $data) {
    $pdo = getDBConnection();
    $sql = "UPDATE servers SET server_name=?, server_url=?, server_type=?, quality=?, language=?, embed_enabled=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['server_name'], $data['server_url'], $data['server_type'],
        $data['quality'], $data['language'], $data['embed_enabled'], $id
    ]);
}

function deleteServer($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE servers SET status = 'inactive' WHERE id = ?");
    return $stmt->execute([$id]);
}

// User interaction functions
function recordInteraction($movieId, $type) {
    $pdo = getDBConnection();
    $userIp = $_SERVER['REMOTE_ADDR'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO user_interactions (movie_id, user_ip, interaction_type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP");
        $stmt->execute([$movieId, $userIp, $type]);
        
        // Update movie counters
        if ($type === 'like') {
            $pdo->prepare("UPDATE movies SET likes = (SELECT COUNT(*) FROM user_interactions WHERE movie_id = ? AND interaction_type = 'like') WHERE id = ?")->execute([$movieId, $movieId]);
        } elseif ($type === 'dislike') {
            $pdo->prepare("UPDATE movies SET dislikes = (SELECT COUNT(*) FROM user_interactions WHERE movie_id = ? AND interaction_type = 'dislike') WHERE id = ?")->execute([$movieId, $movieId]);
        } elseif ($type === 'view') {
            $pdo->prepare("UPDATE movies SET views = views + 1 WHERE id = ?")->execute([$movieId]);
        }
        
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function getMovieStats($movieId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT likes, dislikes, views FROM movies WHERE id = ?");
    $stmt->execute([$movieId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Utility functions
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function generateSlug($title) {
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
}

function formatDuration($minutes) {
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
}
?>