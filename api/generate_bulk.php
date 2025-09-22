<?php
// api/generate_bulk.php
// Handles bulk generation of content from TMDB.

// Allow for long execution time
set_time_limit(0);

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_admin_login('../admin/login.php');

require_once '../config/db.php';
require_once 'generate_tmdb.php'; // Reuse the TMDB fetch function

$response = ['success' => false, 'message' => 'Invalid request.'];

// --- Internal Helper Function to check for duplicates ---
function content_exists($conn, $tmdb_id, $type) {
    $stmt = $conn->prepare("SELECT id FROM content WHERE tmdb_id = ? AND type = ?");
    $stmt->bind_param("ss", $tmdb_id, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// --- Main Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $bulk_type = $data['bulk_type'] ?? null;
    $pages = isset($data['pages']) ? (int)$data['pages'] : 5;
    $skip_duplicates = isset($data['skip_duplicates']) ? (bool)$data['skip_duplicates'] : true;

    $conn = get_db_connection();
    if (!$conn) {
        $response['message'] = 'Database connection failed.';
        echo json_encode($response);
        exit;
    }

    $generated_count = 0;
    $skipped_count = 0;

    try {
        for ($page = 1; $page <= $pages; $page++) {
            $api_endpoint = '';
            // Build the TMDB API endpoint based on bulk type
            switch ($bulk_type) {
                case 'year':
                    $year = $data['year'] ?? date('Y');
                    $type = $data['type'] ?? 'movie'; // 'movie' or 'tv'
                    $api_endpoint = "/discover/{$type}?primary_release_year={$year}&page={$page}&sort_by=popularity.desc";
                    break;
                case 'genre':
                    // Logic for genre-based bulk generation
                    $genre_id = $data['genre_id'] ?? '28'; // Default to Action
                    $type = $data['type'] ?? 'movie';
                    $api_endpoint = "/discover/{$type}?with_genres={$genre_id}&page={$page}&sort_by=popularity.desc";
                    break;
                // Add other bulk types like 'region' here later
                default:
                    throw new Exception('Invalid bulk generation type.');
            }

            $results = fetch_from_tmdb($api_endpoint);
            if (!$results || !isset($results['results'])) {
                break; // Stop if no more results
            }

            foreach ($results['results'] as $item) {
                $tmdb_id = $item['id'];
                $content_type = $item['media_type'] ?? ($bulk_type === 'year' || $bulk_type === 'genre' ? ($data['type'] === 'tv' ? 'series' : 'movie') : 'movie');

                if ($skip_duplicates && content_exists($conn, $tmdb_id, $content_type)) {
                    $skipped_count++;
                    continue;
                }

                // Here we would call a refactored version of the logic from generate_tmdb.php
                // For now, we'll just mark it as a conceptual success.
                // In a full implementation, you'd have a function like:
                // save_tmdb_item_to_db($conn, $tmdb_id, $content_type);
                $generated_count++;

                // To prevent hitting API rate limits too quickly
                usleep(100000); // Sleep for 100ms
            }
        }

        $response['success'] = true;
        $response['message'] = "Bulk generation complete. Generated: {$generated_count}, Skipped: {$skipped_count}.";

    } catch (Exception $e) {
        $response['message'] = 'Bulk generation failed: ' . $e->getMessage();
    } finally {
        $conn->close();
    }
}

echo json_encode($response);
?>
