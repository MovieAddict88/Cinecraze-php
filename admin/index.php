<?php include 'includes/header.php'; ?>

<!-- TMDB Generator Tab -->
<div id="tmdb-generator" class="tab-content active">
    <!-- API Key Selection -->
    <div class="card">
        <h2>🔑 API Key Management</h2>
        <div class="form-group">
        <label for="api-key-select">Select TMDB API Key</label>
        <select id="api-key-select">
            <?php
            if (defined('TMDB_API_KEYS') && is_array(TMDB_API_KEYS)) {
                foreach (TMDB_API_KEYS as $index => $key) {
                    // Display the key in a user-friendly, secure way
                    $displayName = "API Key " . ($index + 1) . " (..." . substr($key, -4) . ")";
                    echo "<option value=\"{$index}\">{$displayName}</option>";
                }
            } else {
                echo "<option value=\"\">API Keys not configured</option>";
            }
            ?>
            </select>
        <small class="api-status">Select a key to use for TMDB API requests.</small>
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
                <input type="text" id="tmdb-search-2" placeholder="Search for movies or TV shows...">
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
</div>

<!-- Manual Input Tab -->
<div id="manual-input" class="tab-content">
    <div class="card">
        <h2>✏️ Manual Content Input</h2>
        <div class="grid">
            <div class="form-group">
                <label>Content Type</label>
                <select id="manual-type" onchange="toggleManualFields()">
                    <option value="movie">Movie</option>
                    <option value="series">TV Series</option>
                    <option value="live">Live TV</option>
                </select>
            </div>
            <div class="form-group">
                <label>SubCategory</label>
                <select id="manual-subcategory">
                    <option value="Action">Action</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Drama">Drama</option>
                    <option value="Horror">Horror</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Anime">Anime</option>
                    <option value="Entertainment">Entertainment</option>
                </select>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Title</label>
                <input type="text" id="manual-title" placeholder="Content title">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" id="manual-country" placeholder="Country (optional)">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Description</label>
                <textarea id="manual-description" rows="4" placeholder="Content description"></textarea>
            </div>
            <div class="form-group">
                <label>Image/Poster URL</label>
                <input type="url" id="manual-image" placeholder="https://...">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label>Year</label>
                <input type="number" id="manual-year" min="1900" max="2030">
            </div>
            <div class="form-group">
                <label>Rating (IMDB)</label>
                <input type="number" id="manual-rating" min="0" max="10" step="0.1">
            </div>
            <div class="form-group">
                <label>Parental Rating</label>
                <input type="text" id="manual-parental-rating" placeholder="e.g., PG-13">
            </div>
        </div>

        <div class="form-group">
            <label>Video Sources</label>
            <div id="manual-sources" class="server-list">
                <div class="server-item">
                    <input type="text" placeholder="Source Name" class="source-name">
                    <input type="url" placeholder="Video URL" class="source-url">
                    <select class="source-type">
                        <option value="video">Direct Video</option>
                        <option value="embed">Embedded</option>
                        <option value="youtube">YouTube</option>
                        <option value="live">Live Stream</option>
                    </select>
                    <select class="source-quality">
                        <option value="1080p">1080p</option>
                        <option value="720p">720p</option>
                        <option value="480p">480p</option>
                        <option value="Auto">Auto</option>
                    </select>
                    <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                    <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                </div>
            </div>
            <button class="btn btn-secondary btn-small" onclick="addManualSource()">+ Add Source</button>
        </div>

        <div id="series-fields" style="display: none;">
            <div class="form-group">
                <label>Number of Seasons</label>
                <input type="number" id="manual-seasons" min="1" onchange="generateSeasonFields()">
            </div>
            <div id="season-container"></div>
        </div>

        <button class="btn btn-primary" onclick="addManualContent()">Add Content</button>
    </div>
</div>

<!-- Data Management Tab -->
<div id="data-management" class="tab-content">
    <div class="grid">
        <div class="card">
            <h2>🔗 Auto-Embed Server Management</h2>
            <div class="form-group">
                <label>Auto-Embed Configuration</label>
                <div style="margin-bottom: 15px;">
                    <button class="btn btn-secondary btn-small" onclick="toggleAllEmbedServers(true)">✅ Enable All Servers</button>
                    <button class="btn btn-secondary btn-small" onclick="toggleAllEmbedServers(false)">❌ Disable All Servers</button>
                    <button class="btn btn-accent btn-small" onclick="enableRecommendedServers()">⭐ Enable Recommended Only</button>
                    <button class="btn btn-secondary btn-small" onclick="checkEmbedProvidersStatus()">🩺 Check Provider Status</button>
                </div>
                <div id="provider-status" class="status info" style="display: none; margin-bottom: 12px;"></div>
                <div class="auto-embed-config">
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidsrc" checked>
                        <label for="auto-vidsrc">VidSrc.net Auto-Embed</label>
                        <select id="vidsrc-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidjoy" checked>
                        <label for="auto-vidjoy">VidJoy.pro Auto-Embed</label>
                        <select id="vidjoy-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-multiembed" checked>
                        <label for="auto-multiembed">MultiEmbed.mov Auto-Embed</label>
                        <select id="multiembed-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-embedsu" checked>
                        <label for="auto-embedsu">Embed.su Auto-Embed</label>
                        <select id="embedsu-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidsrcme" checked>
                        <label for="auto-vidsrcme">VidSrc.me Auto-Embed</label>
                        <select id="vidsrcme-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-autoembed" checked>
                        <label for="auto-autoembed">AutoEmbed.cc Auto-Embed</label>
                        <select id="autoembed-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-smashystream" checked>
                        <label for="auto-smashystream">VidSrc.win Auto-Embed</label>
                        <select id="smashystream-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidsrcto" checked>
                        <label for="auto-vidsrcto">VidSrc.to Auto-Embed</label>
                        <select id="vidsrcto-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidsrcxyz" checked>
                        <label for="auto-vidsrcxyz">VidSrc.xyz Auto-Embed</label>
                        <select id="vidsrcxyz-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-embedsoap" checked>
                        <label for="auto-embedsoap">EmbedSoap Auto-Embed</label>
                        <select id="embedsoap-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-moviesapi" checked>
                        <label for="auto-moviesapi">MoviesAPI Auto-Embed</label>
                        <select id="moviesapi-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-dbgo" checked>
                        <label for="auto-dbgo">DBGO.fun Auto-Embed</label>
                        <select id="dbgo-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-flixhq" checked>
                        <label for="auto-flixhq">FlixHQ Auto-Embed</label>
                        <select id="flixhq-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-gomovies" checked>
                        <label for="auto-gomovies">GoMovies Auto-Embed</label>
                        <select id="gomovies-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-showbox" checked>
                        <label for="auto-showbox">ShowBox Auto-Embed</label>
                        <select id="showbox-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-primewire" checked>
                        <label for="auto-primewire">PrimeWire Auto-Embed</label>
                        <select id="primewire-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-hdtoday" checked>
                        <label for="auto-hdtoday">HDToday Auto-Embed</label>
                        <select id="hdtoday-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidcloud" checked>
                        <label for="auto-vidcloud">VidCloud Auto-Embed</label>
                        <select id="vidcloud-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-streamwish" checked>
                        <label for="auto-streamwish">StreamWish Auto-Embed</label>
                        <select id="streamwish-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-doodstream" checked>
                        <label for="auto-doodstream">DoodStream Auto-Embed</label>
                        <select id="doodstream-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-streamtape" checked>
                        <label for="auto-streamtape">Vidplus Auto-Embed</label>
                        <select id="streamtape-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-mixdrop" checked>
                        <label for="auto-mixdrop">2embed.stream Auto-Embed</label>
                        <select id="mixdrop-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-videasy" checked>
                        <label for="auto-videasy">VidEasy Auto-Embed</label>
                        <select id="videasy-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-upstream" checked>
                        <label for="auto-upstream">VidFast.pro Auto-Embed</label>
                        <select id="upstream-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-godriveplayer" checked>
                        <label for="auto-godriveplayer">GoDrivePlayer Auto-Embed</label>
                        <select id="godriveplayer-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-2embed" checked>
                        <label for="auto-2embed">2Embed.cc Auto-Embed</label>
                        <select id="2embed-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidlink" checked>
                        <label for="auto-vidlink">VidLink.pro Auto-Embed</label>
                        <select id="vidlink-quality">
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Select Content to Auto-Embed (Max 10 items)</label>
                <div class="checkbox-content-container" id="checkbox-content-container">
                    <div class="checkbox-header">
                        <button class="btn btn-secondary btn-small" onclick="refreshContentCheckboxes()">🔄 Refresh List</button>
                        <button class="btn btn-danger btn-small" onclick="clearAllSelections()">❌ Clear All</button>
                        <span id="selection-counter" class="selection-counter">Selected: 0/10</span>
                    </div>
                    <div id="content-checkbox-list" class="content-checkbox-list">
                        <!-- Checkboxes will be populated here -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Bulk Update Selected Content</label>
                <div class="bulk-update-container">
                    <button class="btn btn-success" id="bulk-update-btn" onclick="bulkUpdateSelectedContent()" disabled>
                        🚀 Update Selected Content with TMDB Metadata & Sources
                    </button>
                    <div id="bulk-update-progress" class="progress-bar" style="display: none;">
                        <div class="progress-fill" id="bulk-update-progress-fill"></div>
                    </div>
                    <div id="bulk-update-status" class="status info" style="display: none;"></div>
                </div>
            </div>
            <div class="form-group">
                <div id="selected-content-info" class="status info" style="display: none;"></div>
            </div>
            <div class="form-group">
                                <label>Apply Auto-Embed to All Content</label>
        <div class="auto-embed-actions">
            <button class="btn btn-warning" onclick="applyAutoEmbedToMovies()">Apply to All Movies</button>
            <button class="btn btn-warning" onclick="applyAutoEmbedToSeries()">Apply to All TV Series</button>
            <button class="btn btn-success" onclick="applyAutoEmbedToAll()">Apply to All Content</button>
        </div>

        <label>Missing Content Auto-Generation</label>
        <div class="auto-embed-actions">
            <button class="btn btn-accent" onclick="detectAndGenerateMissingContent()">
                🔄 Auto-Generate Missing Seasons & Episodes
            </button>
            <small style="display: block; margin-top: 8px; color: var(--text-secondary);">
                Automatically detects and generates missing seasons/episodes with metadata and sources (excluding vidjoy, vidsrc, multiembed)
            </small>
        </div>
            </div>
            <div class="form-group">
                <div id="auto-embed-status" class="status info" style="display: none;"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>👁️ Content Preview & Management</h2>
        <div class="form-group">
            <label>Filter by Type</label>
            <select id="preview-filter" onchange="updatePreview()">
                <option value="all">All Content</option>
                <option value="movie">Movies</option>
                <option value="series">TV Series</option>
                <option value="live">Live TV</option>
            </select>
        </div>
        <div class="form-group">
            <label for="preview-search">Search by Title</label>
            <input type="text" id="preview-search" onkeyup="debouncedUpdatePreview()" placeholder="Enter title to search...">
        </div>
        <div id="pagination-controls" style="text-align: center; margin-bottom: 20px;">
            <button class="btn btn-secondary btn-small" id="prev-page" onclick="changePage(-1)">Previous</button>
            <span id="page-info" style="margin: 0 15px; font-weight: 600;">Page 1 of 1</span>
            <button class="btn btn-secondary btn-small" id="next-page" onclick="changePage(1)">Next</button>
        </div>
        <div id="content-preview" class="preview-grid"></div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
