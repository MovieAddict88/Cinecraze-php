</div>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
            <div class="nav-item active" onclick="switchTab('tmdb-generator')" role="button" tabindex="0" aria-label="TMDB Generator">
                <div class="nav-icon">🎭</div>
                <div class="nav-label">TMDB</div>
            </div>
            <div class="nav-item" onclick="switchTab('manual-input')" role="button" tabindex="0" aria-label="Manual Input">
                <div class="nav-icon">✏️</div>
                <div class="nav-label">Manual</div>
            </div>
            <div class="nav-item" onclick="switchTab('data-management')" role="button" tabindex="0" aria-label="Data Management">
                <div class="nav-icon">🗂️</div>
                <div class="nav-label">Data</div>
            </div>
        </div>
    </nav>

    <!-- Edit Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Content</h2>
            <div id="edit-form"></div>
            <button class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>
    <script>
        // Debounce function
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        // Tab switching
        function switchTab(tabName) {
            // Remove active class from all nav items and tab contents
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');

            // Show corresponding tab content
            document.getElementById(tabName).classList.add('active');

            // Update accessibility attributes
            document.querySelectorAll('.nav-item').forEach(nav => nav.setAttribute('aria-selected', 'false'));
            event.target.closest('.nav-item').setAttribute('aria-selected', 'true');
        }

        async function generateFromTMDB(type, tmdbId = null) {
            const id = tmdbId || document.getElementById(`${type}-tmdb-id`).value;
            if (!id) {
                alert('Please enter a TMDB ID');
                return;
            }

            const apiKeyIndex = document.getElementById('api-key-select').value;
            const loadingSpinner = document.getElementById(`${type}-loading`);
            loadingSpinner.style.display = 'inline-block';

            try {
                const response = await fetch(`api/tmdb.php?type=${type}&id=${id}&api_key_index=${apiKeyIndex}`);
                const result = await response.json();

                if (result.status === 'success') {
                    alert(result.message);
                    // Here you would typically update the UI with the new data
                    // For now, we just log it to the console.
                    console.log(result.data);
                    updatePreview(); // Refresh the preview after adding content
                } else {
                    alert(`Error: ${result.message}`);
                }
            } catch (error) {
                alert('An error occurred while communicating with the server.');
                console.error(error);
            } finally {
                loadingSpinner.style.display = 'none';
            }
        }

        const debouncedUpdatePreview = debounce(() => {
            updatePreview();
        }, 300);

        async function updatePreview() {
            const previewGrid = document.getElementById('content-preview');
            previewGrid.innerHTML = '<div class="loading"></div>';

            try {
                const response = await fetch('api/get_content.php');
                const result = await response.json();

                if (result.status === 'success') {
                    previewGrid.innerHTML = '';
                    const { movies, series } = result.data;

                    const filter = document.getElementById('preview-filter').value;
                    const searchTerm = document.getElementById('preview-search').value.toLowerCase();

                    const itemsToRender = [];

                    if (filter === 'all' || filter === 'movie') {
                        movies.forEach(movie => {
                            if (movie.title.toLowerCase().includes(searchTerm)) {
                                itemsToRender.push(movie);
                            }
                        });
                    }

                    if (filter === 'all' || filter === 'series') {
                        series.forEach(item => {
                            if (item.title.toLowerCase().includes(searchTerm)) {
                                itemsToRender.push(item);
                            }
                        });
                    }

                    itemsToRender.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        const itemType = item.tmdb_id ? (item.first_air_date ? 'series' : 'movie') : 'live';

                        div.innerHTML = `
                            <img src="https://image.tmdb.org/t/p/w500${item.poster_path}" alt="${item.title}" loading="lazy">
                            <div class="info">
                                <div class="title">${item.title}</div>
                                <div class="meta">${item.release_date || item.first_air_date} • ${itemType.toUpperCase()}</div>
                            </div>
                        `;
                        previewGrid.appendChild(div);
                    });

                } else {
                    previewGrid.innerHTML = `<div class="status error">${result.message}</div>`;
                }
            } catch (error) {
                previewGrid.innerHTML = `<div class="status error">Failed to load content.</div>`;
                console.error(error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });

        function addServer(containerId) {
            const container = document.getElementById(containerId);
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';

            const isSeries = containerId === 'series-servers';
            const urlPlaceholder = isSeries ?
                'Video URL Template (use {season} {episode})' :
                'Video URL';

            serverItem.innerHTML = `
                <input type="text" placeholder="Server Name" class="server-name">
                <input type="url" placeholder="${urlPlaceholder}" class="server-url">
                <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
            `;
            container.appendChild(serverItem);
        }

        function removeServer(button) {
            button.parentElement.remove();
        }

        async function pasteFromClipboard(button) {
            try {
                const text = await navigator.clipboard.readText();
                const serverItem = button.closest('.server-item');
                const urlInput = serverItem.querySelector('input[type="url"], .server-url, .source-url');

                if (urlInput) {
                    urlInput.value = text;
                    urlInput.focus();
                }
            } catch (error) {
                alert('Failed to paste from clipboard. Please paste manually.');
                console.error('Paste error:', error);
            }
        }

        function toggleManualFields() {
            const type = document.getElementById('manual-type').value;
            const seriesFields = document.getElementById('series-fields');
            seriesFields.style.display = type === 'series' ? 'block' : 'none';
        }

        function addManualSource() {
            const container = document.getElementById('manual-sources');
            const sourceItem = document.createElement('div');
            sourceItem.className = 'server-item';
            sourceItem.innerHTML = `
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
            `;
            container.appendChild(sourceItem);
        }

        function generateSeasonFields() {
            const seasonCount = document.getElementById('manual-seasons').value;
            const container = document.getElementById('season-container');
            container.innerHTML = '';

            for (let i = 1; i <= seasonCount; i++) {
                const seasonGroup = document.createElement('div');
                seasonGroup.className = 'season-group';
                seasonGroup.innerHTML = `
                    <h4>Season ${i}</h4>
                    <div class="form-group">
                        <label>Number of Episodes</label>
                        <input type="number" class="manual-episodes" min="1" data-season="${i}" onchange="generateEpisodeFields(this)">
                    </div>
                    <div id="episode-container-${i}"></div>
                `;
                container.appendChild(seasonGroup);
            }
        }

        function generateEpisodeFields(input) {
            const season = input.dataset.season;
            const episodeCount = input.value;
            const container = document.getElementById(`episode-container-${season}`);
            container.innerHTML = '';

            for (let i = 1; i <= episodeCount; i++) {
                const episodeGroup = document.createElement('div');
                episodeGroup.className = 'episode-group';
                episodeGroup.innerHTML = `
                    <h5>Episode ${i}</h5>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="manual-episode-title" placeholder="Episode ${i} title">
                    </div>
                    <div class="form-group">
                        <label>Servers</label>
                        <div class="server-list">
                            <div class="server-item">
                                <input type="text" placeholder="Server Name" class="source-name">
                                <input type="url" placeholder="Video URL" class="source-url">
                                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-small" onclick="addEpisodeServer(this)">+ Add Server</button>
                    </div>
                `;
                container.appendChild(episodeGroup);
            }
        }

        function addEpisodeServer(button) {
            const serverList = button.previousElementSibling;
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';
            serverItem.innerHTML = `
                <input type="text" placeholder="Server Name" class="source-name">
                <input type="url" placeholder="Video URL" class="source-url">
                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
            `;
            serverList.appendChild(serverItem);
        }

        async function addManualContent() {
            const type = document.getElementById('manual-type').value;
            const title = document.getElementById('manual-title').value;
            const subcategory = document.getElementById('manual-subcategory').value;
            const country = document.getElementById('manual-country').value;
            const description = document.getElementById('manual-description').value;
            const image = document.getElementById('manual-image').value;
            const year = document.getElementById('manual-year').value;
            const rating = document.getElementById('manual-rating').value;
            const parental_rating = document.getElementById('manual-parental-rating').value;

            const servers = [];
            document.querySelectorAll('#manual-sources .server-item').forEach(item => {
                const name = item.querySelector('.source-name').value;
                const url = item.querySelector('.source-url').value;
                if (name && url) {
                    servers.push({ name, url });
                }
            });

            const data = {
                type, title, subcategory, country, description, image, year, rating, parental_rating, servers,
                seasons: []
            };

            if (type === 'series') {
                document.querySelectorAll('.season-group').forEach((seasonGroup, seasonIndex) => {
                    const seasonData = {
                        season_number: seasonIndex + 1,
                        episodes: []
                    };
                    seasonGroup.querySelectorAll('.episode-group').forEach((episodeGroup, episodeIndex) => {
                        const episodeData = {
                            episode_number: episodeIndex + 1,
                            title: episodeGroup.querySelector('.manual-episode-title').value,
                            servers: []
                        };
                        episodeGroup.querySelectorAll('.server-item').forEach(serverItem => {
                            const name = serverItem.querySelector('.source-name').value;
                            const url = serverItem.querySelector('.source-url').value;
                            if (name && url) {
                                episodeData.servers.push({ name, url });
                            }
                        });
                        seasonData.episodes.push(episodeData);
                    });
                    data.seasons.push(seasonData);
                });
            }

            try {
                const response = await fetch('api/add_manual_content.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                alert(result.message);
                if (result.status === 'success') {
                    updatePreview();
                }
            } catch (error) {
                alert('An error occurred.');
                console.error(error);
            }
        }

        async function searchTMDB() {
            const query = document.getElementById('tmdb-search').value || document.getElementById('tmdb-search-2').value;
            const type = document.getElementById('search-subtype').value;
            if (!query) {
                alert('Please enter a search query');
                return;
            }

            const apiKeyIndex = document.getElementById('api-key-select').value;
            const loadingSpinner = document.getElementById('search-loading');
            loadingSpinner.style.display = 'inline-block';
            const resultsGrid = document.getElementById('search-results');
            resultsGrid.innerHTML = '';

            try {
                const response = await fetch(`api/search_tmdb.php?type=${type}&query=${query}&api_key_index=${apiKeyIndex}`);
                const result = await response.json();

                if (result.status === 'success' && result.data.results) {
                    result.data.results.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        const itemType = item.media_type || (item.title ? 'movie' : 'tv');
                        const title = item.title || item.name;
                        const release_date = item.release_date || item.first_air_date;

                        div.innerHTML = `
                            <img src="https://image.tmdb.org/t/p/w500${item.poster_path}" alt="${title}" loading="lazy">
                            <div class="info">
                                <div class="title">${title}</div>
                                <div class="meta">${release_date} • ${itemType.toUpperCase()}</div>
                                <button class="btn btn-primary btn-small" onclick="generateFromTMDB('${itemType === 'tv' ? 'series' : 'movie'}', ${item.id})">Add</button>
                            </div>
                        `;
                        resultsGrid.appendChild(div);
                    });
                } else {
                    resultsGrid.innerHTML = `<div class="status error">${result.message}</div>`;
                }
            } catch (error) {
                resultsGrid.innerHTML = `<div class="status error">Failed to perform search.</div>`;
                console.error(error);
            } finally {
                loadingSpinner.style.display = 'none';
            }
        }
    </script>
</body>
</html>
