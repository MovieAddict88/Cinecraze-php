<?php
header('Content-Type: application/json');

$settings_file = 'settings.json';

if (file_exists($settings_file)) {
    $settings = file_get_contents($settings_file);
    // No need to decode and re-encode, just output the raw content
    echo $settings;
} else {
    // If the file doesn't exist, return a default structure
    echo json_encode([]);
}
?>
