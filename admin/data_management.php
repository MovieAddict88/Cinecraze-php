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
