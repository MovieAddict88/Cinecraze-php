<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>CineCraze Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/cinecraze.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎬 CineCraze Admin Panel</h1>
            <p class="subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Logout</a></p>
        </header>

        <div id="tmdb-generator" class="tab-content active">
            <?php include 'tmdb_generator.php'; ?>
        </div>

        <div id="manual-input" class="tab-content">
            <?php include 'manual_input.php'; ?>
        </div>

        <div id="bulk-operations" class="tab-content">
            <?php include 'bulk_operations.php'; ?>
        </div>

        <div id="data-management" class="tab-content">
            <?php include 'data_management.php'; ?>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
            <div class="nav-item active" onclick="switchTab('tmdb-generator')" role="button" tabindex="0" aria-label="TMDB Generator">
                <div class="nav-icon">🎭</div>
                <div class="nav-label">TMDB</div>
            </div>
            <div class="nav-item" onclick="switchTab('manual-input')" role="button" tabindex="0" aria-label="Manual Input">
                <div class="nav-icon">✏️</div>
                <div class="nav-label">Manual</div>
            </div>
            <div class="nav-item" onclick="switchTab('bulk-operations')" role="button" tabindex="0" aria-label="Bulk Operations">
                <div class="nav-icon">📦</div>
                <div class="nav-label">Bulk</div>
            </div>
            <div class="nav-item" onclick="switchTab('data-management')" role="button" tabindex="0" aria-label="Data Management">
                <div class="nav-icon">🗂️</div>
                <div class="nav-label">Data</div>
            </div>
        </div>
    </nav>

    <script src="../assets/js/cinecraze.js"></script>
</body>
</html>
