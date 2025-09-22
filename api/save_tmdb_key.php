<?php
// api/save_tmdb_key.php
session_start();
header('Content-Type: application/json');

function error_response($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    error_response('Authentication required.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Invalid request method.');
}

$api_key = $_POST['api_key'] ?? '';

if (empty($api_key)) {
    error_response('API key cannot be empty.');
}

$config_path = '../config.php';

if (!is_writable($config_path)) {
    error_response('Configuration file is not writable. Please check file permissions.');
}

try {
    $config_content = file_get_contents($config_path);
    if ($config_content === false) {
        throw new Exception('Could not read config file.');
    }

    // Use a regular expression to replace the TMDB_API_KEY value
    $new_config_content = preg_replace(
        "/define\('TMDB_API_KEY', '.*?'\);/",
        "define('TMDB_API_KEY', '" . addslashes($api_key) . "');",
        $config_content
    );

    if ($new_config_content === null || $new_config_content === $config_content) {
         // If the pattern was not found, maybe it's the first time. Let's try to append.
         // This is a fallback and assumes a simple config structure.
        if (strpos($config_content, "define('TMDB_API_KEY'") === false) {
             $new_config_content = $config_content . "\ndefine('TMDB_API_KEY', '" . addslashes($api_key) . "');";
        } else {
            throw new Exception('Could not find or update the TMDB API key definition in the config file.');
        }
    }

    if (file_put_contents($config_path, $new_config_content) === false) {
        throw new Exception('Could not write to config file.');
    }

    echo json_encode(['success' => true, 'message' => 'TMDB API Key saved successfully.']);

} catch (Exception $e) {
    error_response('An error occurred: ' . $e->getMessage());
}
