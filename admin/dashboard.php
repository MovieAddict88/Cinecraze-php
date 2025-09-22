<?php
// Simple session check for now. Will be expanded later.
session_start();
//if (!isset($_SESSION['user_id'])) {
//    header('Location: index.php');
//    exit;
//}

include 'parts/header.php';

// Determine the active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'tmdb-generator';
?>

<!-- TMDB Generator Tab -->
<div id="tmdb-generator" class="tab-content <?php echo $active_tab === 'tmdb-generator' ? 'active' : ''; ?>">
    <!-- Content from cinecraze.html for TMDB Generator -->
    <div class="card">
        <h2>🎬 Movie Generator</h2>
        <div class="form-group">
            <label>TMDB Movie ID</label>
            <input type="number" id="movie-tmdb-id" placeholder="e.g., 550 (Fight Club)">
        </div>
        <button class="btn btn-primary" onclick="generateFromTMDB('movie')">
            <span class="loading" id="movie-loading" style="display: none;"></span>
            Generate Movie
        </button>
    </div>
     <div class="card">
        <h2>📺 TV Series Generator</h2>
        <div class="form-group">
            <label>TMDB TV Series ID</label>
            <input type="number" id="series-tmdb-id" placeholder="e.g., 1399 (Game of Thrones)">
        </div>
        <button class="btn btn-primary" onclick="generateFromTMDB('series')">
            <span class="loading" id="series-loading" style="display: none;"></span>
            Generate Series
        </button>
    </div>
</div>

<!-- Manual Input Tab -->
<div id="manual-input" class="tab-content <?php echo $active_tab === 'manual-input' ? 'active' : ''; ?>">
    <div class="card">
        <h2>✏️ Manual Content Input</h2>
        <p>Manual input form will go here.</p>
    </div>
</div>

<!-- Bulk Operations Tab -->
<div id="bulk-operations" class="tab-content <?php echo $active_tab === 'bulk-operations' ? 'active' : ''; ?>">
    <div class="card">
        <h2>📦 Bulk Operations</h2>
        <p>Bulk operations form will go here.</p>
    </div>
</div>

<!-- Data Management Tab -->
<div id="data-management" class="tab-content <?php echo $active_tab === 'data-management' ? 'active' : ''; ?>">
    <div class="card">
        <h2>👁️ Content Preview & Management</h2>
        <p>Content preview grid will go here.</p>
    </div>
    <div class="card">
        <h2>🔗 Auto-Embed Server Management</h2>
        <p>Auto-embed server configuration will go here.</p>
    </div>
</div>


<?php include 'parts/navigation.php'; ?>
<?php include 'parts/footer.php'; ?>
