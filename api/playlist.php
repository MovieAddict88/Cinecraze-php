<?php
// api/playlist.php
// This script generates the main JSON data for the public-facing site (index.html).
// It queries the database and structures the data to match the original playlist.json format.

header('Content-Type: application/json');
require_once '../config/db.php';

// Main function to fetch and structure all data
function get_full_playlist() {
    $conn = get_db_connection();
    if (!$conn) {
        return ['error' => 'Database connection failed'];
    }

    // --- Get Filter Parameters ---
    $genre_filter = $_GET['genre'] ?? 'all';
    $country_filter = $_GET['country'] ?? 'all';
    $year_filter = $_GET['year'] ?? 'all';

    $playlist = [
        'Categories' => []
    ];

    $category_map = [];

    // 1. Build the query with filters
    $sql = "
        SELECT DISTINCT
            c.*, g.name as genre_name
        FROM content c
        LEFT JOIN content_genres cg ON c.id = cg.content_id
        LEFT JOIN genres g ON cg.genre_id = g.id
        WHERE 1=1
    ";

    $params = [];
    $types = '';

    if ($genre_filter !== 'all') {
        $sql .= " AND g.name = ?";
        $params[] = $genre_filter;
        $types .= 's';
    }
    if ($country_filter !== 'all') {
        $sql .= " AND c.country = ?";
        $params[] = $country_filter;
        $types .= 's';
    }
    if ($year_filter !== 'all') {
        $sql .= " AND c.release_year = ?";
        $params[] = (int)$year_filter;
        $types .= 'i';
    }

    $sql .= " ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $content_by_id = [];
    while($item = $result->fetch_assoc()) {
        // Since one content can have multiple genres, we only add it once
        if (!isset($content_by_id[$item['id']])) {
            $content_by_id[$item['id']] = $item;
        }
    }
    $stmt->close();

    // 2. Fetch all seasons, episodes, and servers in efficient queries
    $seasons_by_content_id = fetch_seasons($conn);
    $episodes_by_season_id = fetch_episodes($conn);
    $servers_by_content_id = fetch_servers($conn, 'content');
    $servers_by_episode_id = fetch_servers($conn, 'episode');

    // 3. Structure the data
    foreach($content_by_id as $item) {
        $main_category_name = '';
        switch ($item['type']) {
            case 'movie': $main_category_name = 'Movies'; break;
            case 'series': $main_category_name = 'TV Series'; break;
            case 'live_tv': $main_category_name = 'Live TV'; break;
        }

        if (!isset($category_map[$main_category_name])) {
            $category_map[$main_category_name] = [
                'MainCategory' => $main_category_name,
                'SubCategories' => [],
                'Entries' => []
            ];
        }

        $entry = [
            'Title' => $item['title'],
            'SubCategory' => $item['genre_name'] ?? 'General',
            'Country' => $item['country'],
            'Description' => $item['description'],
            'Poster' => $item['poster_url'],
            'Thumbnail' => $item['thumbnail_url'],
            'Rating' => (float)$item['rating'],
            'Duration' => $item['duration'],
            'Year' => (int)$item['release_year'],
            'parentalRating' => $item['parental_rating'],
            'Servers' => $servers_by_content_id[$item['id']] ?? []
        ];

        if ($item['type'] === 'series') {
            $entry['Seasons'] = [];
            $item_seasons = $seasons_by_content_id[$item['id']] ?? [];
            foreach($item_seasons as $season) {
                $season_entry = [
                    'Season' => (int)$season['season_number'],
                    'SeasonPoster' => $season['poster_url'],
                    'Episodes' => []
                ];

                $season_episodes = $episodes_by_season_id[$season['id']] ?? [];
                foreach($season_episodes as $episode) {
                    $season_entry['Episodes'][] = [
                        'Episode' => (int)$episode['episode_number'],
                        'Title' => $episode['title'],
                        'Duration' => $episode['duration'],
                        'Description' => $episode['description'],
                        'Thumbnail' => $episode['thumbnail_url'],
                        'Servers' => $servers_by_episode_id[$episode['id']] ?? []
                    ];
                }
                $entry['Seasons'][] = $season_entry;
            }
        }

        $category_map[$main_category_name]['Entries'][] = $entry;

        // Add subcategory to list if not present
        if (!in_array($entry['SubCategory'], $category_map[$main_category_name]['SubCategories'])) {
            $category_map[$main_category_name]['SubCategories'][] = $entry['SubCategory'];
        }
    }

    $playlist['Categories'] = array_values($category_map);
    $conn->close();
    return $playlist;
}

function fetch_seasons($conn) {
    $data = [];
    $sql = "SELECT * FROM seasons ORDER BY season_number ASC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data[$row['content_id']][] = $row;
    }
    return $data;
}

function fetch_episodes($conn) {
    $data = [];
    $sql = "SELECT * FROM episodes ORDER BY episode_number ASC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data[$row['season_id']][] = $row;
    }
    return $data;
}

function fetch_servers($conn, $type) {
    $data = [];
    $id_column = $type === 'content' ? 'content_id' : 'episode_id';
    $sql = "SELECT id, {$id_column}, name, url, quality, type, drm_license_url FROM servers WHERE {$id_column} IS NOT NULL";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data[$row[$id_column]][] = [
            'name' => $row['name'],
            'url' => $row['url'],
            'quality' => $row['quality'],
            'type' => $row['type'],
            'drm_license_url' => $row['drm_license_url']
        ];
    }
    return $data;
}


// --- Execute and Output ---
$playlist_data = get_full_playlist();
echo json_encode($playlist_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
