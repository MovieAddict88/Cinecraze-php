<?php
// api/search.php
// Handles searching for content in the database.

header('Content-Type: application/json');
require_once '../config/db.php';

$response = ['success' => false, 'message' => 'Invalid request.', 'data' => []];

$query = $_GET['query'] ?? '';

if (strlen($query) < 2) {
    $response['message'] = 'Search query must be at least 2 characters long.';
    echo json_encode($response);
    exit;
}

$conn = get_db_connection();
if (!$conn) {
    $response['message'] = 'Database connection failed.';
    echo json_encode($response);
    exit;
}

// Prepare the search query to prevent SQL injection
$search_term = "%" . $query . "%";
$sql = "
    SELECT
        c.id, c.title, c.type, c.poster_url, c.release_year, g.name as genre_name
    FROM content c
    LEFT JOIN content_genres cg ON c.id = cg.content_id
    LEFT JOIN genres g ON cg.genre_id = g.id
    WHERE c.title LIKE ?
    GROUP BY c.id
    ORDER BY c.release_year DESC, c.title ASC
    LIMIT 20
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

$search_results = [];
while ($row = $result->fetch_assoc()) {
    $search_results[] = $row;
}

$stmt->close();
$conn->close();

$response['success'] = true;
$response['message'] = count($search_results) . ' results found.';
$response['data'] = $search_results;

echo json_encode($response);
?>
