<?php
// api/content_get.php
// Fetches content from the database with filtering, searching, and pagination.

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php'); // Adjust path for API folder

require_once '../config/db.php';

// --- Parameters ---
$type = $_GET['type'] ?? 'all';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
$offset = ($page - 1) * $limit;

// --- Build Query ---
$conn = get_db_connection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$sql = "SELECT * FROM content WHERE 1=1";
$count_sql = "SELECT COUNT(id) as total FROM content WHERE 1=1";

$params = [];
$types = '';

// Add type filtering
if ($type !== 'all' && in_array($type, ['movie', 'series', 'live_tv'])) {
    $sql .= " AND type = ?";
    $count_sql .= " AND type = ?";
    $params[] = $type;
    $types .= 's';
}

// Add search filtering
if (!empty($search)) {
    $sql .= " AND title LIKE ?";
    $count_sql .= " AND title LIKE ?";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $types .= 's';
}

// Get total count for pagination
$stmt_count = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$count_result = $stmt_count->get_result()->fetch_assoc();
$total_records = $count_result['total'];
$total_pages = ceil($total_records / $limit);
$stmt_count->close();


// Add ordering and pagination to the main query
$sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

// --- Execute Query ---
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$content = [];
while ($row = $result->fetch_assoc()) {
    $content[] = $row;
}

$stmt->close();
$conn->close();

// --- Return JSON Response ---
echo json_encode([
    'success' => true,
    'data' => $content,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_records' => $total_records,
        'limit' => $limit
    ]
]);
?>
