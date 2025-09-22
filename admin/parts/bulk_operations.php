<!-- Bulk Operations Tab -->
<div id="bulk-operations" class="tab-content">
    <div class="grid">
        <div class="card">
            <h2>📅 Year-based Bulk Generation</h2>
            <div class="form-group">
                <label>Content Type</label>
                <select id="bulk-type">
                    <option value="movie">Movies</option>
                    <option value="tv">TV Shows</option>
                </select>
            </div>
            <div class="form-group">
                <label>Year</label>
                <input type="number" id="bulk-year" min="1900" max="2030" value="2025">
            </div>
            <div class="form-group">
                <label>Number of Pages (1 page = 20 items)</label>
                <input type="number" id="bulk-pages" min="1" max="500" value="5">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="bulk-skip-duplicates" checked>
                    Skip Duplicates
                </label>
            </div>
            <button class="btn btn-primary" onclick="bulkGenerate()">
                <span class="loading" id="bulk-loading" style="display: none;"></span>
                Start Bulk Generation
            </button>
            <div class="progress-bar">
                <div class="progress-fill" id="bulk-progress"></div>
            </div>
            <div id="bulk-status"></div>
        </div>

        <div class="card">
            <h2>🎯 Genre-based Generation</h2>
            <div class="form-group">
                <label>Content Type</label>
                <select id="content-type-select">
                    <option value="movie">Movies Only</option>
                    <option value="tv">TV Series Only</option>
                    <option value="both">Both Movies & TV Series</option>
                </select>
            </div>
            <div class="form-group">
                <label>Genre</label>
                <select id="genre-select">
                    <optgroup label="Universal Genres">
                        <option value="28">Action</option>
                        <option value="12">Adventure</option>
                        <!-- ... other genres ... -->
                    </optgroup>
                    <optgroup label="TV-Specific Genres">
                        <option value="10759">Action & Adventure</option>
                        <!-- ... other genres ... -->
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label>Release Year (Optional)</label>
                <select id="year-select">
                    <option value="">Any Year</option>
                    <option value="2025">2025 (Latest)</option>
                    <!-- ... other years ... -->
                </select>
            </div>
            <div class="form-group">
                <label>Number of Items</label>
                <input type="number" id="genre-count" min="1" max="10000" value="20">
            </div>
            <button class="btn btn-primary" onclick="generateByGenre()">
                <span class="loading" id="genre-loading" style="display: none;"></span>
                Generate by Genre
            </button>
            <div id="genre-progress" style="margin-top: 15px; display: none;">
                <div class="progress-bar">
                    <div class="progress-fill" id="genre-progress-fill"></div>
                </div>
                <div class="progress-text" id="genre-progress-text">Processing...</div>
            </div>
        </div>

        <div class="card">
            <h2>🌍 Regional Bulk Generation</h2>
            <div class="form-group">
                <label>Region</label>
                <select id="bulk-regional-select">
                    <option value="hollywood">🎬 Hollywood</option>
                    <option value="anime">🇯🇵 Anime</option>
                    <!-- ... other regions ... -->
                </select>
            </div>
            <div class="form-group">
                <label>Content Type</label>
                <select id="bulk-regional-content-type">
                    <option value="both">🎭 Both Movies & Series</option>
                    <option value="movie">🎬 Movies Only</option>
                    <option value="tv">📺 TV Series Only</option>
                </select>
            </div>
            <div class="form-group">
                <label>Year or Range</label>
                <select id="bulk-regional-year-select">
                    <option value="">Any Year (Most Popular)</option>
                    <option value="2025">2025 (Latest)</option>
                    <!-- ... other years ... -->
                    <option value="all-time">All Time (1990-2025)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Number of Pages (1 page = 20 items)</label>
                <input type="number" id="bulk-regional-pages" min="1" max="500" value="5">
            </div>
            <button class="btn btn-primary" onclick="bulkGenerateRegional()">
                <span class="loading" id="bulk-regional-loading" style="display: none;"></span>
                Start Regional Generation
            </button>
            <div class="progress-bar" style="margin-top: 15px;">
                <div class="progress-fill" id="bulk-regional-progress"></div>
            </div>
            <div id="bulk-regional-status"></div>
        </div>
    </div>
</div>
