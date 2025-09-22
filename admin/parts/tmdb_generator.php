<!-- TMDB Generator Tab -->
<div id="tmdb-generator" class="tab-content active">
    <!-- API Key Selection -->
    <div class="card">
        <h2>🔑 API Key Management</h2>
        <div class="form-group">
            <label for="api-key-select">Select TMDB API Key (for backup detection avoidance)</label>
            <select id="api-key-select" onchange="switchApiKey()">
                <option value="primary">Primary Key (***61)</option>
                <option value="backup1">Backup Key 1 (***69)</option>
                <option value="backup2">Backup Key 2 (***3f)</option>
                <option value="backup3">Backup Key 3 (***8d)</option>
            </select>
            <small class="api-status">Current: <span id="current-api-status">Primary (Active)</span></small>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>🎬 Movie Generator</h2>
            <div class="form-group">
                <label>TMDB Movie ID</label>
                <input type="number" id="movie-tmdb-id" placeholder="e.g., 550 (Fight Club)">
            </div>
            <div class="form-group">
                <label>Additional Servers</label>
                <div id="movie-servers" class="server-list">
                    <div class="server-item">
                        <input type="text" placeholder="Server Name" class="server-name">
                        <input type="url" placeholder="Video URL" class="server-url">
                        <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                        <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                    </div>
                </div>
                <button class="btn btn-secondary btn-small" onclick="addServer('movie-servers')">+ Add Server</button>
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
            <div class="form-group">
                <label>Seasons to Include</label>
                <input type="text" id="series-seasons" placeholder="e.g., 1,2,3 or leave empty for all">
            </div>
            <div class="form-group">
                <label>Additional Servers</label>
                <div id="series-servers" class="server-list">
                    <div class="server-item">
                        <input type="text" placeholder="Server Name" class="server-name">
                        <input type="url" placeholder="Video URL Template (use {season} {episode})" class="server-url">
                        <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                        <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                    </div>
                </div>
                <button class="btn btn-secondary btn-small" onclick="addServer('series-servers')">+ Add Server</button>
            </div>
            <button class="btn btn-primary" onclick="generateFromTMDB('series')">
                <span class="loading" id="series-loading" style="display: none;"></span>
                Generate Series
            </button>
        </div>
    </div>

    <div class="card">
        <h2>🔍 TMDB Search & Preview</h2>
        <div class="grid">
            <div class="form-group">
                <label>Search Query</label>
                <input type="text" id="tmdb-search" placeholder="Search for movies or TV shows...">
            </div>
            <div class="form-group">
                <label>Content Type</label>
                <select id="search-type" onchange="handleSearchTypeChange()">
                    <option value="search">🔍 Search Mode</option>
                    <option value="hollywood">🎬 Hollywood</option>
                    <option value="anime">🇯🇵 Anime</option>
                    <option value="animation">🎨 Animation</option>
                    <option value="kids">🧸 Kids / Family</option>
                    <option value="kdrama">🇰🇷 K-Drama (Korean)</option>
                    <option value="cdrama">🇨🇳 C-Drama (Chinese)</option>
                    <option value="jdrama">🇯🇵 J-Drama (Japanese)</option>
                    <option value="pinoy">🇵🇭 Pinoy Series (Filipino)</option>
                    <option value="thai">🇹🇭 Thai Drama</option>
                    <option value="indian">🇮🇳 Indian Series</option>
                    <option value="turkish">🇹🇷 Turkish Drama</option>
                    <option value="korean-variety">🎭 Korean Variety Shows</option>
                </select>
            </div>
            <div class="form-group" id="search-input-group">
                <label>Search Query</label>
                <input type="text" id="tmdb-search" placeholder="Search for movies or TV shows...">
                <div class="form-group">
                    <label>Search Type</label>
                    <select id="search-subtype">
                        <option value="multi">All</option>
                        <option value="movie">Movies</option>
                        <option value="tv">TV Shows</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="regional-browse-group" style="display: none;">
                <label>Content Type</label>
                <select id="regional-content-type">
                    <option value="tv">📺 TV Series/Dramas</option>
                    <option value="movie">🎬 Movies</option>
                    <option value="both">🎭 Both Movies & Series</option>
                </select>
                <label>Select Year to Browse</label>
                <select id="year-filter" onchange="loadRegionalContent()">
                    <option value="">-- Select Year --</option>
                    <option value="2025">2025 (Latest)</option>
                    <option value="2024">2024</option>
                    <!-- ... other years ... -->
                    <option value="all-time">All Time (1990-2025)</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary" onclick="searchTMDB()">
            <span class="loading" id="search-loading" style="display: none;"></span>
            Search TMDB
        </button>
        <div id="search-results" class="preview-grid"></div>
    </div>
</div>
