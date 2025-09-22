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
