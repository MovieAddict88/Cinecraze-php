<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

$bulk_data = file_get_contents('php://input');

if (empty(trim($bulk_data))) {
    echo json_encode(['success' => false, 'message' => 'No data submitted.']);
    exit;
}

$lines = explode("\n", trim($bulk_data));
$success_count = 0;
$error_count = 0;
$errors = [];

$conn->begin_transaction();

try {
    // Prepare statements outside the loop
    $stmt_movie = $conn->prepare("INSERT INTO movies (title, description, poster_path, backdrop_path, release_date, rating) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_server = $conn->prepare("INSERT INTO servers (content_id, content_type, name, url) VALUES (?, ?, ?, ?)");
    $stmt_series = $conn->prepare("INSERT INTO tv_series (name, overview, poster_path, backdrop_path, first_air_date, rating) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_live = $conn->prepare("INSERT INTO live_tv (name, description, logo_path, stream_url) VALUES (?, ?, ?, ?)");

    foreach ($lines as $index => $line) {
        $line = trim($line);
        if (empty($line)) continue;

        $data = str_getcsv($line);

        // Pad array with nulls if columns are missing
        $data = array_pad($data, 8, null);

        list($title, $description, $poster_url, $type, $year, $rating, $server_name, $server_url) = $data;
        $type = strtolower(trim($type));
        $release_date = !empty($year) ? $year . '-01-01' : null;

        if (empty($title) || empty($type)) {
            $error_count++;
            $errors[] = "Line " . ($index + 1) . ": Title and Type are required fields.";
            continue;
        }

        switch ($type) {
            case 'movie':
                $stmt_movie->bind_param("sssssd", $title, $description, $poster_url, $poster_url, $release_date, $rating);
                $stmt_movie->execute();
                $movie_id = $conn->insert_id;

                if ($movie_id && !empty($server_url)) {
                    $content_type_server = 'movie';
                    $stmt_server->bind_param("isss", $movie_id, $content_type_server, $server_name, $server_url);
                    $stmt_server->execute();
                }
                break;

            case 'series':
                $stmt_series->bind_param("sssssd", $title, $description, $poster_url, $poster_url, $release_date, $rating);
                $stmt_series->execute();
                // Note: Bulk import for series does not add seasons/episodes/servers. This must be done manually.
                break;

            case 'live':
                $stmt_live->bind_param("ssss", $title, $description, $poster_url, $server_url);
                $stmt_live->execute();
                break;

            default:
                $error_count++;
                $errors[] = "Line " . ($index + 1) . ": Invalid content type '{$type}'. Use 'movie', 'series', or 'live'.";
                continue 2; // continue the outer foreach loop
        }
        $success_count++;
    }

    // Close prepared statements
    $stmt_movie->close();
    $stmt_server->close();
    $stmt_series->close();
    $stmt_live->close();

    if ($error_count > 0) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => "Bulk import failed due to errors. No content was added.",
            'errors' => $errors
        ]);
    } else {
        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => "Bulk import successful. Added {$success_count} items."
        ]);
    }

} catch (Exception $e) {
    $conn->rollback();
    // Return a generic error to avoid exposing database details
    echo json_encode(['success' => false, 'message' => 'A database error occurred during the import process.']);
}

$conn->close();
?>
