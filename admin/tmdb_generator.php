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
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option>
                <option value="2014">2014</option>
                <option value="2013">2013</option>
                <option value="2012">2012</option>
                <option value="2011">2011</option>
                <option value="2010">2010</option>
                <option value="2009">2009</option>
                <option value="2008">2008</option>
                <option value="2007">2007</option>
                <option value="2006">2006</option>
                <option value="2005">2005</option>
                <option value="2004">2004</option>
                <option value="2003">2003</option>
                <option value="2002">2002</option>
                <option value="2001">2001</option>
                <option value="2000">2000</option>
                <option value="1999">1999</option>
                <option value="1998">1998</option>
                <option value="1997">1997</option>
                <option value="1996">1996</option>
                <option value="1995">1995</option>
                <option value="all-recent">All Recent (2020-2025)</option>
                <option value="all-2010s">All 2010s (2010-2019)</option>
                <option value="all-2000s">All 2000s (2000-2009)</option>
                <option value="all-classic">All Classic (1990-1999)</option>
                <option value="all-time">All Time (1990-2025)</option>
            </select>
            <small style="display: block; margin-top: 8px; color: var(--text-secondary);">
                Choose any year (1995-2025) or decade collection for comprehensive results
            </small>
        </div>
    </div>
    <button class="btn btn-primary" onclick="searchTMDB()">
        <span class="loading" id="search-loading" style="display: none;"></span>
        Search TMDB
    </button>
    <div id="search-results" class="preview-grid"></div>
</div>
