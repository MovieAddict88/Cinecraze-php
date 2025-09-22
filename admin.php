<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraze Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 100%);
            color: #ffffff;
            min-height: 100vh;
        }

        .header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff6b6b;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab {
            background: none;
            border: none;
            color: #b0b0b0;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            color: #ff6b6b;
            border-bottom-color: #ff6b6b;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #d0d0d0;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .search-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: end;
        }

        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .result-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .result-poster {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .result-info {
            padding: 1rem;
        }

        .result-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .result-year {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .movie-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .movie-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .movie-header {
            display: flex;
            justify-content: between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .movie-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .movie-meta {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .movie-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-danger {
            background: #f44336;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        .server-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .server-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }

        .server-info {
            flex: 1;
        }

        .server-name {
            font-weight: 500;
        }

        .server-details {
            font-size: 0.8rem;
            color: #b0b0b0;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            overflow-y: auto;
        }

        .modal-content {
            background: #1a1a2e;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 15px;
            max-width: 800px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #b0b0b0;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid #4CAF50;
            color: #4CAF50;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .error-message {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid #f44336;
            color: #f44336;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .tabs {
                flex-wrap: wrap;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .search-section {
                flex-direction: column;
            }

            .movie-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">CineCraze Admin</div>
        <div class="header-controls">
            <a href="index.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                View Site
            </a>
            <button class="btn btn-secondary" onclick="changePassword()">
                <i class="fas fa-key"></i>
                Change Password
            </button>
            <button class="btn" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </header>

    <div class="container">
        <div class="tabs">
            <button class="tab active" onclick="switchTab('tmdb')">TMDB Generator</button>
            <button class="tab" onclick="switchTab('manual')">Manual Entry</button>
            <button class="tab" onclick="switchTab('bulk')">Bulk Import</button>
            <button class="tab" onclick="switchTab('management')">Data Management</button>
        </div>

        <!-- TMDB Generator Tab -->
        <div id="tmdb" class="tab-content active">
            <div class="card">
                <h2>TMDB Movie Generator</h2>
                <div class="search-section">
                    <div class="form-group" style="flex: 1;">
                        <label>Search Query</label>
                        <input type="text" id="tmdbSearch" placeholder="Enter movie or TV show name">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select id="tmdbType">
                            <option value="movie">Movie</option>
                            <option value="tv">TV Show</option>
                        </select>
                    </div>
                    <button class="btn" onclick="searchTMDB()">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                </div>
                <div id="tmdbResults" class="search-results"></div>
            </div>

            <div class="card">
                <h2>Regional Generation</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" id="regionYear" min="1900" max="2024" value="2023">
                    </div>
                    <div class="form-group">
                        <label>Region</label>
                        <select id="regionCode">
                            <option value="">All Regions</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="IN">India</option>
                            <option value="JP">Japan</option>
                            <option value="KR">South Korea</option>
                            <option value="FR">France</option>
                            <option value="DE">Germany</option>
                            <option value="ES">Spain</option>
                            <option value="IT">Italy</option>
                            <option value="BR">Brazil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select id="regionType">
                            <option value="movie">Movie</option>
                            <option value="tv">TV Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Limit</label>
                        <input type="number" id="regionLimit" min="1" max="100" value="20">
                    </div>
                </div>
                <button class="btn" onclick="generateByRegion()">
                    <i class="fas fa-globe"></i>
                    Generate Movies
                </button>
                <div id="regionResults" class="search-results"></div>
            </div>
        </div>

        <!-- Manual Entry Tab -->
        <div id="manual" class="tab-content">
            <div class="card">
                <h2>Manual Movie Entry</h2>
                <form id="manualForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" id="manualTitle" required>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" id="manualYear" min="1900" max="2024">
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <input type="number" id="manualRating" min="0" max="10" step="0.1">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" id="manualDuration" placeholder="e.g., 2h 30m">
                        </div>
                        <div class="form-group">
                            <label>Genre</label>
                            <input type="text" id="manualGenre" placeholder="e.g., Action, Drama">
                        </div>
                        <div class="form-group">
                            <label>Language</label>
                            <input type="text" id="manualLanguage" placeholder="e.g., English">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" id="manualCountry" placeholder="e.g., United States">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select id="manualType">
                                <option value="movie">Movie</option>
                                <option value="tv">TV Show</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Poster URL</label>
                            <input type="url" id="manualPoster">
                        </div>
                        <div class="form-group">
                            <label>Backdrop URL</label>
                            <input type="url" id="manualBackdrop">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="manualDescription" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn">
                        <i class="fas fa-plus"></i>
                        Add Movie
                    </button>
                </form>
            </div>
        </div>

        <!-- Bulk Import Tab -->
        <div id="bulk" class="tab-content">
            <div class="card">
                <h2>Bulk Import from TMDB</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Year *</label>
                        <input type="number" id="bulkYear" min="1900" max="2024" value="2023" required>
                    </div>
                    <div class="form-group">
                        <label>Region</label>
                        <select id="bulkRegion">
                            <option value="">All Regions</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="IN">India</option>
                            <option value="JP">Japan</option>
                            <option value="KR">South Korea</option>
                            <option value="FR">France</option>
                            <option value="DE">Germany</option>
                            <option value="ES">Spain</option>
                            <option value="IT">Italy</option>
                            <option value="BR">Brazil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select id="bulkType">
                            <option value="movie">Movie</option>
                            <option value="tv">TV Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Limit</label>
                        <input type="number" id="bulkLimit" min="1" max="100" value="50">
                    </div>
                </div>
                <button class="btn" onclick="bulkImport()">
                    <i class="fas fa-download"></i>
                    Start Bulk Import
                </button>
                <div id="bulkProgress" style="margin-top: 1rem;"></div>
            </div>
        </div>

        <!-- Data Management Tab -->
        <div id="management" class="tab-content">
            <div class="card">
                <h2>Server Selection Settings</h2>
                <div class="form-group">
                    <label>Auto Embed Servers</label>
                    <select id="autoEmbedSetting">
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>
                <button class="btn" onclick="saveSettings()">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
            </div>

            <div class="card">
                <h2>Content Management</h2>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h3>All Movies</h3>
                    <button class="btn" onclick="refreshMovies()">
                        <i class="fas fa-refresh"></i>
                        Refresh
                    </button>
                </div>
                <div id="moviesList" class="movie-list">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Loading movies...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movie Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
            <h2>Edit Movie</h2>
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" id="editTitle" required>
                    </div>
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" id="editYear">
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <input type="number" id="editRating" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" id="editDuration">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="editDescription" rows="4"></textarea>
                </div>
                <button type="submit" class="btn">Update Movie</button>
            </form>
        </div>
    </div>

    <!-- Server Management Modal -->
    <div id="serverModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeServerModal()">&times;</button>
            <h2>Manage Servers</h2>
            <div id="serverContent">
                <form id="serverForm">
                    <input type="hidden" id="serverMovieId">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Server Name</label>
                            <input type="text" id="serverName" required>
                        </div>
                        <div class="form-group">
                            <label>Server URL</label>
                            <input type="url" id="serverUrl" required>
                        </div>
                        <div class="form-group">
                            <label>Server Type</label>
                            <select id="serverType">
                                <option value="direct">Direct Link</option>
                                <option value="embed">Embed</option>
                                <option value="hls">HLS Stream</option>
                                <option value="live">Live TV</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quality</label>
                            <input type="text" id="serverQuality" placeholder="e.g., 1080p, 720p">
                        </div>
                        <div class="form-group">
                            <label>Language</label>
                            <input type="text" id="serverLanguage" placeholder="e.g., English">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="serverEmbedEnabled" checked>
                                Enable Embed
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn">Add Server</button>
                </form>
                <div id="existingServers"></div>
            </div>
        </div>
    </div>

    <script>
        let currentMovies = [];

        // Initialize admin panel
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            loadMovies();
        });

        // Check authentication
        async function checkAuth() {
            try {
                const response = await fetch('api/auth.php?action=check');
                const result = await response.json();
                if (!result.logged_in) {
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                window.location.href = 'index.php';
            }
        }

        // Tab switching
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabName).classList.add('active');

            // Load data for specific tabs
            if (tabName === 'management') {
                loadMovies();
            }
        }

        // TMDB Search
        async function searchTMDB() {
            const query = document.getElementById('tmdbSearch').value;
            const type = document.getElementById('tmdbType').value;
            
            if (!query) {
                alert('Please enter a search query');
                return;
            }

            const resultsDiv = document.getElementById('tmdbResults');
            resultsDiv.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i><p>Searching...</p></div>';

            try {
                const response = await fetch(`api/tmdb.php?action=search&query=${encodeURIComponent(query)}&type=${type}`);
                const data = await response.json();
                
                if (data.results && data.results.length > 0) {
                    resultsDiv.innerHTML = data.results.map(item => `
                        <div class="result-card" onclick="importFromTMDB(${item.id}, '${type}')">
                            <img src="https://image.tmdb.org/t/p/w300${item.poster_path}" alt="${item.title || item.name}" class="result-poster" onerror="this.src='https://via.placeholder.com/300x450?text=No+Image'">
                            <div class="result-info">
                                <div class="result-title">${item.title || item.name}</div>
                                <div class="result-year">${(item.release_date || item.first_air_date || '').substring(0, 4)}</div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    resultsDiv.innerHTML = '<p>No results found</p>';
                }
            } catch (error) {
                console.error('Search error:', error);
                resultsDiv.innerHTML = '<p>Search failed</p>';
            }
        }

        // Import from TMDB
        async function importFromTMDB(tmdbId, type) {
            try {
                const response = await fetch('api/tmdb.php?action=import', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ tmdb_id: tmdbId, type: type })
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Movie imported successfully!', 'success');
                    loadMovies();
                } else {
                    showMessage('Failed to import movie', 'error');
                }
            } catch (error) {
                console.error('Import error:', error);
                showMessage('Import failed', 'error');
            }
        }

        // Regional generation
        async function generateByRegion() {
            const year = document.getElementById('regionYear').value;
            const region = document.getElementById('regionCode').value;
            const type = document.getElementById('regionType').value;
            const limit = document.getElementById('regionLimit').value;

            if (!year) {
                alert('Please enter a year');
                return;
            }

            const resultsDiv = document.getElementById('regionResults');
            resultsDiv.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i><p>Generating...</p></div>';

            try {
                const response = await fetch(`api/tmdb.php?action=by-year&year=${year}&region=${region}&type=${type}`);
                const data = await response.json();
                
                if (data.results && data.results.length > 0) {
                    const limitedResults = data.results.slice(0, limit);
                    resultsDiv.innerHTML = limitedResults.map(item => `
                        <div class="result-card" onclick="importFromTMDB(${item.id}, '${type}')">
                            <img src="https://image.tmdb.org/t/p/w300${item.poster_path}" alt="${item.title || item.name}" class="result-poster" onerror="this.src='https://via.placeholder.com/300x450?text=No+Image'">
                            <div class="result-info">
                                <div class="result-title">${item.title || item.name}</div>
                                <div class="result-year">${(item.release_date || item.first_air_date || '').substring(0, 4)}</div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    resultsDiv.innerHTML = '<p>No results found</p>';
                }
            } catch (error) {
                console.error('Generation error:', error);
                resultsDiv.innerHTML = '<p>Generation failed</p>';
            }
        }

        // Manual form submission
        document.getElementById('manualForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                title: document.getElementById('manualTitle').value,
                year: document.getElementById('manualYear').value,
                rating: document.getElementById('manualRating').value,
                duration: document.getElementById('manualDuration').value,
                genre: document.getElementById('manualGenre').value,
                language: document.getElementById('manualLanguage').value,
                country: document.getElementById('manualCountry').value,
                type: document.getElementById('manualType').value,
                poster: document.getElementById('manualPoster').value,
                backdrop: document.getElementById('manualBackdrop').value,
                description: document.getElementById('manualDescription').value,
                tmdb_id: null
            };

            try {
                const response = await fetch('api/movies.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Movie added successfully!', 'success');
                    this.reset();
                    loadMovies();
                } else {
                    showMessage('Failed to add movie', 'error');
                }
            } catch (error) {
                console.error('Add movie error:', error);
                showMessage('Failed to add movie', 'error');
            }
        });

        // Bulk import
        async function bulkImport() {
            const year = document.getElementById('bulkYear').value;
            const region = document.getElementById('bulkRegion').value;
            const type = document.getElementById('bulkType').value;
            const limit = document.getElementById('bulkLimit').value;

            if (!year) {
                alert('Please enter a year');
                return;
            }

            const progressDiv = document.getElementById('bulkProgress');
            progressDiv.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i><p>Importing movies...</p></div>';

            try {
                const response = await fetch('api/tmdb.php?action=bulk-import', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ year, region, type, limit })
                });

                const result = await response.json();
                if (result.success) {
                    progressDiv.innerHTML = `<div class="success-message">${result.message}</div>`;
                    loadMovies();
                } else {
                    progressDiv.innerHTML = `<div class="error-message">Bulk import failed</div>`;
                }
            } catch (error) {
                console.error('Bulk import error:', error);
                progressDiv.innerHTML = '<div class="error-message">Bulk import failed</div>';
            }
        }

        // Load movies for management
        async function loadMovies() {
            try {
                const response = await fetch('api/movies.php?action=list');
                currentMovies = await response.json();
                renderMoviesList();
            } catch (error) {
                console.error('Error loading movies:', error);
                document.getElementById('moviesList').innerHTML = '<div class="error-message">Failed to load movies</div>';
            }
        }

        // Render movies list
        function renderMoviesList() {
            const listDiv = document.getElementById('moviesList');
            
            if (currentMovies.length === 0) {
                listDiv.innerHTML = '<p>No movies found</p>';
                return;
            }

            listDiv.innerHTML = currentMovies.map(movie => `
                <div class="movie-item">
                    <div class="movie-header">
                        <div>
                            <div class="movie-title">${movie.title}</div>
                            <div class="movie-meta">${movie.year} • ${movie.rating}/10 • ${movie.server_count} servers</div>
                        </div>
                    </div>
                    <div class="movie-actions">
                        <button class="btn btn-small" onclick="editMovie(${movie.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-small" onclick="manageServers(${movie.id})">
                            <i class="fas fa-server"></i> Servers
                        </button>
                        <button class="btn btn-small btn-danger" onclick="deleteMovie(${movie.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Edit movie
        function editMovie(movieId) {
            const movie = currentMovies.find(m => m.id == movieId);
            if (!movie) return;

            document.getElementById('editId').value = movie.id;
            document.getElementById('editTitle').value = movie.title;
            document.getElementById('editYear').value = movie.year;
            document.getElementById('editRating').value = movie.rating;
            document.getElementById('editDuration').value = movie.duration;
            document.getElementById('editDescription').value = movie.description;

            document.getElementById('editModal').style.display = 'block';
        }

        // Edit form submission
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const movieId = document.getElementById('editId').value;
            const formData = {
                title: document.getElementById('editTitle').value,
                year: document.getElementById('editYear').value,
                rating: document.getElementById('editRating').value,
                duration: document.getElementById('editDuration').value,
                description: document.getElementById('editDescription').value
            };

            try {
                const response = await fetch(`api/movies.php?action=update&id=${movieId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Movie updated successfully!', 'success');
                    closeEditModal();
                    loadMovies();
                } else {
                    showMessage('Failed to update movie', 'error');
                }
            } catch (error) {
                console.error('Update error:', error);
                showMessage('Failed to update movie', 'error');
            }
        });

        // Delete movie
        async function deleteMovie(movieId) {
            if (!confirm('Are you sure you want to delete this movie?')) return;

            try {
                const response = await fetch(`api/movies.php?action=delete&id=${movieId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Movie deleted successfully!', 'success');
                    loadMovies();
                } else {
                    showMessage('Failed to delete movie', 'error');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showMessage('Failed to delete movie', 'error');
            }
        }

        // Manage servers
        async function manageServers(movieId) {
            document.getElementById('serverMovieId').value = movieId;
            
            try {
                const response = await fetch(`api/movies.php?action=servers&movie_id=${movieId}`);
                const servers = await response.json();
                
                const existingDiv = document.getElementById('existingServers');
                if (servers.length > 0) {
                    existingDiv.innerHTML = `
                        <h3>Existing Servers</h3>
                        ${servers.map(server => `
                            <div class="server-item">
                                <div class="server-info">
                                    <div class="server-name">${server.server_name}</div>
                                    <div class="server-details">${server.server_type} • ${server.quality || 'N/A'} • ${server.language || 'N/A'}</div>
                                </div>
                                <button class="btn btn-small btn-danger" onclick="deleteServer(${server.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `).join('')}
                    `;
                } else {
                    existingDiv.innerHTML = '<p>No servers found</p>';
                }

                document.getElementById('serverModal').style.display = 'block';
            } catch (error) {
                console.error('Error loading servers:', error);
            }
        }

        // Server form submission
        document.getElementById('serverForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const movieId = document.getElementById('serverMovieId').value;
            const formData = {
                server_name: document.getElementById('serverName').value,
                server_url: document.getElementById('serverUrl').value,
                server_type: document.getElementById('serverType').value,
                quality: document.getElementById('serverQuality').value,
                language: document.getElementById('serverLanguage').value,
                embed_enabled: document.getElementById('serverEmbedEnabled').checked
            };

            try {
                const response = await fetch(`api/servers.php?action=add&movie_id=${movieId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Server added successfully!', 'success');
                    this.reset();
                    manageServers(movieId); // Refresh server list
                } else {
                    showMessage('Failed to add server', 'error');
                }
            } catch (error) {
                console.error('Add server error:', error);
                showMessage('Failed to add server', 'error');
            }
        });

        // Delete server
        async function deleteServer(serverId) {
            if (!confirm('Are you sure you want to delete this server?')) return;

            try {
                const response = await fetch(`api/servers.php?action=delete&id=${serverId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                if (result.success) {
                    showMessage('Server deleted successfully!', 'success');
                    const movieId = document.getElementById('serverMovieId').value;
                    manageServers(movieId); // Refresh server list
                } else {
                    showMessage('Failed to delete server', 'error');
                }
            } catch (error) {
                console.error('Delete server error:', error);
                showMessage('Failed to delete server', 'error');
            }
        }

        // Modal controls
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function closeServerModal() {
            document.getElementById('serverModal').style.display = 'none';
        }

        // Utility functions
        function refreshMovies() {
            loadMovies();
        }

        function saveSettings() {
            showMessage('Settings saved successfully!', 'success');
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `${type}-message`;
            messageDiv.textContent = message;
            messageDiv.style.position = 'fixed';
            messageDiv.style.top = '20px';
            messageDiv.style.right = '20px';
            messageDiv.style.zIndex = '9999';
            messageDiv.style.padding = '1rem';
            messageDiv.style.borderRadius = '8px';
            messageDiv.style.maxWidth = '300px';

            document.body.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Change password
        function changePassword() {
            const currentPassword = prompt('Enter current password:');
            if (!currentPassword) return;

            const newPassword = prompt('Enter new password:');
            if (!newPassword) return;

            // In a real application, you'd implement proper password change functionality
            alert('Password change functionality needs to be implemented with proper security measures.');
        }

        // Logout
        async function logout() {
            try {
                await fetch('api/auth.php?action=logout', { method: 'POST' });
                window.location.href = 'index.php';
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = 'index.php';
            }
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            const editModal = document.getElementById('editModal');
            if (e.target === editModal) {
                closeEditModal();
            }
            
            const serverModal = document.getElementById('serverModal');
            if (e.target === serverModal) {
                closeServerModal();
            }
        });
    </script>
</body>
</html>