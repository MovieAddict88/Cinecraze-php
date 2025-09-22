<?php
header('Content-Type: application/json');
require_once '../includes/session_check.php';
require_once '../includes/database.php';

// --- Parameters ---
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$filterType = $_GET['type'] ?? 'all';
$searchQuery = $_GET['search'] ?? '';

$offset = ($page - 1) * $limit;
$searchParam = '%' . $searchQuery . '%';

// --- Build Query ---
$queries = [];
$countQueries = [];
$bindings = [];
$countBindings = [];
$types = '';
$countTypes = '';

if ($filterType === 'all' || $filterType === 'movie') {
    $queries[] = "(SELECT id, title, 'Movie' as type, release_date as date, poster_path FROM movies WHERE title LIKE ?)";
    $countQueries[] = "(SELECT COUNT(*) FROM movies WHERE title LIKE ?)";
    $bindings[] = $searchParam;
    $countBindings[] = $searchParam;
    $types .= 's';
    $countTypes .= 's';
}
if ($filterType === 'all' || $filterType === 'series') {
    $queries[] = "(SELECT id, name as title, 'TV Series' as type, first_air_date as date, poster_path FROM tv_series WHERE name LIKE ?)";
    $countQueries[] = "(SELECT COUNT(*) FROM tv_series WHERE name LIKE ?)";
    $bindings[] = $searchParam;
    $countBindings[] = $searchParam;
    $types .= 's';
    $countTypes .= 's';
}
if ($filterType === 'all' || $filterType === 'live') {
    $queries[] = "(SELECT id, name as title, 'Live TV' as type, NOW() as date, logo_path as poster_path FROM live_tv WHERE name LIKE ?)";
    $countQueries[] = "(SELECT COUNT(*) FROM live_tv WHERE name LIKE ?)";
    $bindings[] = $searchParam;
    $countBindings[] = $searchParam;
    $types .= 's';
    $countTypes .= 's';
}

if (empty($queries)) {
    echo json_encode(['success' => false, 'message' => 'Invalid filter type']);
    exit;
}

// --- Get Total Count ---
$totalRecords = 0;
$countSql = "SELECT SUM(count) as total FROM (" . implode(" UNION ALL ", $countQueries) . ") as counts";
$stmt_count = $conn->prepare($countSql);
if ($countTypes) {
    $stmt_count->bind_param($countTypes, ...$countBindings);
}
$stmt_count->execute();
$count_result = $stmt_count->get_result()->fetch_assoc();
$totalRecords = $count_result['total'] ?? 0;
$stmt_count->close();

$totalPages = ceil($totalRecords / $limit);

// --- Get Paginated Data ---
$dataSql = implode(" UNION ALL ", $queries);
$dataSql .= " ORDER BY date DESC LIMIT ? OFFSET ?";
$bindings[] = $limit;
$bindings[] = $offset;
$types .= 'ii';

$stmt_data = $conn->prepare($dataSql);
$stmt_data->bind_param($types, ...$bindings);
$stmt_data->execute();
$result = $stmt_data->get_result();

$content = [];
while ($row = $result->fetch_assoc()) {
    $content[] = $row;
}
$stmt_data->close();

// --- Response ---
echo json_encode([
    'success' => true,
    'content' => $content,
    'pagination' => [
        'page' => $page,
        'limit' => $limit,
        'totalRecords' => (int)$totalRecords,
        'totalPages' => $totalPages
    ]
]);

$conn->close();
?>
