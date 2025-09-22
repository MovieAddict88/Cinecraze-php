<?php require_once 'header.php'; ?>

<!-- Main Content -->
<main>
    <!-- TMDB Generator Tab -->
    <div id="tmdb-generator" class="tab-content active">
        <div class="grid grid-2">
            <div class="card">
                <h2>🎬 Movie Generator</h2>
                <div class="form-group">
                    <label for="movie-tmdb-id">TMDB Movie ID</label>
                    <input type="number" id="movie-tmdb-id" placeholder="e.g., 550 (Fight Club)">
                </div>
                <button class="btn btn-primary" id="btn-generate-movie">
                    <span class="loading" style="display: none;"></span>
                    Generate Movie
                </button>
            </div>

            <div class="card">
                <h2>📺 TV Series Generator</h2>
                <div class="form-group">
                    <label for="series-tmdb-id">TMDB TV Series ID</label>
                    <input type="number" id="series-tmdb-id" placeholder="e.g., 1399 (Game of Thrones)">
                </div>
                <button class="btn btn-primary" id="btn-generate-series">
                    <span class="loading" style="display: none;"></span>
                    Generate Series
                </button>
            </div>
        </div>
        <div class="card">
            <h2>🔍 TMDB Search</h2>
            <div class="grid">
                <div class="form-group">
                    <label for="tmdb-search-query">Search Query</label>
                    <input type="text" id="tmdb-search-query" placeholder="Search for movies or TV shows...">
                </div>
                <div class="form-group">
                    <label for="tmdb-search-type">Content Type</label>
                    <select id="tmdb-search-type">
                        <option value="multi">All</option>
                        <option value="movie">Movies</option>
                        <option value="tv">TV Shows</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" id="btn-tmdb-search">
                <span class="loading" style="display: none;"></span>
                Search TMDB
            </button>
            <div id="tmdb-search-results" class="preview-grid"></div>
        </div>
    </div>

    <!-- Manual Input Tab -->
    <div id="manual-input" class="tab-content">
        <div class="card">
            <h2>✏️ Manual Content Input</h2>
            <form id="manual-form">
                <div class="grid">
                    <div class="form-group">
                        <label>Content Type</label>
                        <select id="manual-type">
                            <option value="movie">Movie</option>
                            <option value="series">TV Series</option>
                            <option value="live_tv">Live TV</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" id="manual-title" placeholder="Content title" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="manual-overview">Description</label>
                    <textarea id="manual-overview" rows="3" placeholder="Content description"></textarea>
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label for="manual-poster-path">Image/Poster URL</label>
                        <input type="url" id="manual-poster-path" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label for="manual-release-date">Release Date</label>
                        <input type="date" id="manual-release-date">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="loading" style="display: none;"></span>
                    Add Content
                </button>
            </form>
        </div>
    </div>

    <!-- Bulk Operations Tab -->
    <div id="bulk-operations" class="tab-content">
        <div class="card">
            <h2>🚧 Bulk Operations</h2>
            <p>Bulk operations will be implemented here. This section will allow generating content from TMDB by year, genre, or region.</p>
        </div>
    </div>

    <!-- Data Management Tab -->
    <div id="data-management" class="tab-content">
        <div class="card">
            <h2>🔗 Auto-Embed Server Management</h2>
            <p>This section will allow managing auto-embed server configurations.</p>
            <!-- Simplified for now -->
        </div>

        <div class="card">
            <h2>👁️ Content Preview & Management</h2>
            <div class="form-group">
                <label for="preview-filter">Filter by Type</label>
                <select id="preview-filter">
                    <option value="">All Content</option>
                    <option value="movie">Movies</option>
                    <option value="series">TV Series</option>
                    <option value="live_tv">Live TV</option>
                </select>
            </div>
            <div class="form-group">
                <label for="preview-search">Search by Title</label>
                <input type="text" id="preview-search" placeholder="Enter title to search...">
            </div>
            <div id="pagination-controls" style="text-align: center; margin-bottom: 20px;">
                <!-- Pagination will be rendered here by JS -->
            </div>
            <div id="content-preview" class="preview-grid">
                <!-- Content preview will be rendered here by JS -->
            </div>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>
