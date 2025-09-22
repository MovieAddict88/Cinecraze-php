</div> <!-- This closes the .container div from header.php -->

<!-- Bottom Navigation Bar -->
<nav class="bottom-nav" role="navigation" aria-label="Main navigation">
    <div class="nav-container">
        <a href="#tmdb-generator" class="nav-item active" data-tab="tmdb-generator">
            <div class="nav-icon">🎭</div>
            <div class="nav-label">TMDB</div>
        </a>
        <a href="#manual-input" class="nav-item" data-tab="manual-input">
            <div class="nav-icon">✏️</div>
            <div class="nav-label">Manual</div>
        </a>
        <a href="#bulk-operations" class="nav-item" data-tab="bulk-operations">
            <div class="nav-icon">📦</div>
            <div class="nav-label">Bulk</div>
        </a>
        <a href="#data-management" class="nav-item" data-tab="data-management">
            <div class="nav-icon">🗂️</div>
            <div class="nav-label">Data</div>
        </a>
    </div>
</nav>

<!-- Modal for Editing/Adding Servers -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Edit Content</h2>
        <div id="modal-body"></div>
        <div id="modal-footer"></div>
    </div>
</div>

<!-- Global Status/Toast -->
<div id="global-status" style="position: fixed; top: 80px; right: 20px; z-index: 9999;"></div>

<script src="../assets/js/admin.js" defer></script>
</body>
</html>
