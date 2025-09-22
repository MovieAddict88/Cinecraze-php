<!-- Data Management Tab -->
<div id="data-management" class="tab-content">
    <div class="grid">
        <div class="card">
            <h2>📂 Import/Export</h2>
            <div class="form-group">
                <label>Import JSON File</label>
                <input type="file" id="import-file" accept=".json">
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                    <button class="btn btn-secondary" onclick="importData()">
                        <span class="loading" id="import-loading" style="display: none;"></span>
                        Import to DB
                    </button>
                </div>
                <small style="color: var(--text-secondary); margin-top: 5px; display: block;">
                    💡 This will parse the JSON and store its content in the database.
                </small>
            </div>

            <!-- Import Progress Section -->
            <div id="import-progress-section" style="display: none;">
                <!-- ... progress bar html ... -->
            </div>

            <div class="form-group">
                <button class="btn btn-primary" onclick="exportData()">Export from DB</button>
                <small>This feature will be developed to export the DB content to a JSON file.</small>
            </div>
        </div>

        <div class="card">
            <h2>🔗 Auto-Embed Server Management</h2>
            <div class="form-group">
                <label>Auto-Embed Configuration</label>
                <div style="margin-bottom: 15px;">
                    <button class="btn btn-secondary btn-small" onclick="toggleAllEmbedServers(true)">✅ Enable All Servers</button>
                    <button class="btn btn-secondary btn-small" onclick="toggleAllEmbedServers(false)">❌ Disable All Servers</button>
                </div>
                <div class="auto-embed-config">
                    <!-- A list of checkboxes for different embed providers -->
                    <div class="embed-option">
                        <input type="checkbox" id="auto-vidsrc" checked>
                        <label for="auto-vidsrc">VidSrc.net Auto-Embed</label>
                    </div>
                    <!-- ... other providers ... -->
                </div>
                 <button class="btn btn-success" style="margin-top: 15px;" onclick="saveEmbedSettings()">Save Embed Settings</button>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>👁️ Content Preview & Management</h2>
        <p>This section will display content from the database. Edit and Delete buttons will interact with the API.</p>
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
            <!-- Pagination will be generated here -->
        </div>
        <div id="content-preview" class="preview-grid">
            <!-- Content will be loaded here from the API -->
        </div>
    </div>
</div>
