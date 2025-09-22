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

        // Configuration - Multiple API Keys for Backup
        const TMDB_API_KEYS = {
            'primary': 'ec926176bf467b3f7735e3154238c161',
            'backup1': 'bb51e18edb221e87a05f90c2eb456069',
            'backup2': '4a1f2e8c9d3b5a7e6f9c2d1e8b4a5c3f',
            'backup3': '7d9a2b1e4f6c8e5a3b7d9f2e1c4a6b8d'
        };

        let currentApiKey = 'primary'; // Default to primary key
        const TMDB_BASE_URL = 'https://api.themoviedb.org/3';
        const TMDB_IMAGE_BASE = 'https://image.tmdb.org/t/p/w500';
        const VIDSRC_BASE = 'https://vidsrc.net/embed';
        const VIDJOY_BASE = 'https://vidjoy.pro/embed';
        const MULTIEMBED_BASE = 'https://multiembed.mov/directstream.php';
        const EMBEDSU_BASE = 'https://embed.su/embed';
        const VIDSRCME_BASE = 'https://vidsrc.me/embed';
        const AUTOEMBED_BASE = 'https://player.autoembed.cc/embed';
        const SMASHYSTREAM_BASE = 'https://vidsrc.win';
        const VIDSRCTO_BASE = 'https://vidsrc.to/embed';
        const VIDSRCXYZ_BASE = 'https://vidsrc.xyz/embed';
        const EMBEDSOAP_BASE = 'https://www.embedsoap.com/embed';
        const MOVIESAPI_BASE = 'https://moviesapi.club/movie';
        const SUPEREMBED_BASE = 'https://multiembed.mov/directstream.php';
        const DBGO_BASE = 'https://dbgo.fun/movie';
        const NONTONFILM_BASE = 'https://tv.nontonguru.info/embed';
        const FLIXHQ_BASE = 'https://flixhq.to/watch';
        const GOMOVIES_BASE = 'https://gomovies.sx/watch';
        const SHOWBOX_BASE = 'https://www.showbox.media/embed';
        const PRIMEWIRE_BASE = 'https://primewire.mx/embed';
        const CATAZ_BASE = 'https://cataz.net/embed';
        const HDTODAY_BASE = 'https://hdtoday.tv/embed';
        const STREAMLARE_BASE = 'https://streamlare.com/e';
        const VIDEASY_MOVIE_BASE = 'https://player.videasy.net/movie';
        const VIDEASY_TV_BASE = 'https://player.videasy.net/tv';
        const STREAMHUB_BASE = 'https://streamhub.to/e';
        const VIDCLOUD_BASE = 'https://vidcloud.to/embed';
        const STREAMWISH_BASE = 'https://streamwish.to/e';
        const DOODSTREAM_BASE = 'https://doodstream.com/e';
        const UPSTREAM_BASE = 'https://vidfast.pro';
        const STREAMTAPE_BASE = 'https://player.vidplus.to/embed';
        const MIXDROP_BASE = 'https://www.2embed.stream/embed';
const GODRIVEPLAYER_BASE = 'https://godriveplayer.com/embed';
const TWOTWOEMBED_BASE = 'https://2embed.cc/embed';

        // Get current TMDB API key
        function getTMDBApiKey() {
            return TMDB_API_KEYS[currentApiKey] || TMDB_API_KEYS.primary;
        }

        // Regional drama configurations
        const REGIONAL_CONFIGS = {
            hollywood: {
                name: 'Hollywood',
                origin_country: 'US',
                language: 'en',
                genres: [], // All genres
                keywords: ['hollywood', 'american movie']
            },
            anime: {
                name: 'Anime',
                origin_country: 'JP',
                language: 'ja',
                genres: [16], // Animation genre ID
                keywords: ['anime', 'japanese animation']
            },
            animation: {
                name: 'Animation',
                origin_country: '', // No specific country
                language: '', // Any language
                genres: [16], // Animation genre ID
                keywords: ['animation', 'cartoon']
            },
            kids: {
                name: 'Kids / Family',
                origin_country: '', // No specific country
                language: '', // Any language
                genres: [10751, 10762], // Family and Kids genre IDs
                keywords: ['kids', 'family', 'children']
            },
            kdrama: {
                name: 'K-Drama',
                origin_country: 'KR',
                language: 'ko',
                genres: [18], // Drama genre ID
                keywords: ['korean drama', 'k-drama', 'kdrama']
            },
            cdrama: {
                name: 'C-Drama',
                origin_country: 'CN',
                language: 'zh',
                genres: [18],
                keywords: ['chinese drama', 'c-drama', 'cdrama']
            },
            jdrama: {
                name: 'J-Drama',
                origin_country: 'JP',
                language: 'ja',
                genres: [18],
                keywords: ['japanese drama', 'j-drama', 'jdrama']
            },
            pinoy: {
                name: 'Pinoy Series',
                origin_country: 'PH',
                language: 'tl',
                genres: [18],
                keywords: ['filipino series', 'pinoy series', 'philippine drama']
            },
            thai: {
                name: 'Thai Drama',
                origin_country: 'TH',
                language: 'th',
                genres: [18],
                keywords: ['thai drama', 'thai series']
            },
            indian: {
                name: 'Indian Series',
                origin_country: 'IN',
                language: 'hi',
                genres: [18],
                keywords: ['indian series', 'hindi series', 'bollywood series']
            },
            turkish: {
                name: 'Turkish Drama',
                origin_country: 'TR',
                language: 'tr',
                genres: [18],
                keywords: ['turkish drama', 'turkish series']
            },
            'korean-variety': {
                name: 'Korean Variety',
                origin_country: 'KR',
                language: 'ko',
                genres: [10764], // Reality TV genre ID
                keywords: ['korean variety', 'variety show', 'korean entertainment']
            }
        };

        // Switch API key
        function switchApiKey() {
            const select = document.getElementById('api-key-select');
            currentApiKey = select.value;
            updateApiDropdown();
            showStatus('info', `Switched to API key: ${currentApiKey}`);
        }

        // Update API dropdown display
        function updateApiDropdown() {
            const select = document.getElementById('api-key-select');
            const status = document.getElementById('current-api-status');

            if (select) {
                select.value = currentApiKey;
            }

            if (status) {
                const keyName = currentApiKey.charAt(0).toUpperCase() + currentApiKey.slice(1);
                status.textContent = `${keyName} (Active)`;
            }
        }

        // Toggle all embed servers on/off
        function toggleAllEmbedServers(enable) {
            const checkboxes = document.querySelectorAll('.auto-embed-config input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = enable;
            });
            updateAutoEmbedStatus();
            showStatus('info', enable ? 'All embed servers enabled' : 'All embed servers disabled');
        }

        // Enable only recommended servers (high reliability and speed)
        function enableRecommendedServers() {
            // First disable all
            toggleAllEmbedServers(false);

            // Enable recommended servers
            const recommended = [
                'auto-vidsrc', 'auto-vidsrcto', 'auto-embedsu', 'auto-vidsrcme',
                'auto-multiembed', 'auto-flixhq', 'auto-hdtoday', 'auto-vidcloud',
                'auto-streamwish', 'auto-mixdrop', 'auto-videasy', 'auto-vidlink'
            ];

            recommended.forEach(id => {
                const checkbox = document.getElementById(id);
                if (checkbox) checkbox.checked = true;
            });

            updateAutoEmbedStatus();
            showStatus('success', 'Recommended embed servers enabled');
        }

        // Global data storage
        let currentData = {
            movies: [],
            series: []
        };
        let currentPage = 1;
        const itemsPerPage = 50;

        const debouncedUpdatePreview = debounce(() => {
            currentPage = 1; // Reset to first page on new search
            updatePreview();
        }, 300);

        function changePage(direction) {
            currentPage += direction;
            updatePreview();
        }

        async function saveTmdbApiKey(event) {
            event.preventDefault();
            const apiKey = document.getElementById('tmdb-api-key').value;
            if (!apiKey) {
                showStatus('error', 'API Key cannot be empty.');
                return;
            }

            const formData = new FormData();
            formData.append('api_key', apiKey);
            showStatus('info', 'Saving API Key...');

            try {
                const response = await fetch('../api/save_tmdb_key.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    showStatus('success', result.message);
                } else {
                    showStatus('error', result.message);
                }
            } catch (error) {
                showStatus('error', `An error occurred: ${error.message}`);
            }
        }

        async function loadInitialSettings() {
            try {
                const response = await fetch('../api/get_tmdb_key.php');
                const result = await response.json();
                if (result.success && result.api_key && result.api_key !== 'YOUR_TMDB_API_KEY') {
                    document.getElementById('tmdb-api-key').value = result.api_key;
                }
            } catch (error) {
                console.error('Could not load TMDB API key:', error);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', async function() {
            showStatus('info', 'Loading content from database...');
            await loadContentFromDB();
            await loadInitialSettings();
            // updateDataStats(); // This will be updated later
            updatePreview();
            updateAutoEmbedStatus();
            updateApiDropdown();
            handleSearchTypeChange();
            refreshContentCheckboxes();
            showStatus('success', 'Application loaded successfully!');
        });

        async function loadContentFromDB() {
            try {
                const response = await fetch('../api/get_content.php');
                if (!response.ok) {
                    throw new Error(`API error: ${response.statusText}`);
                }
                const data = await response.json();
                if (data.success) {
                    currentData.movies = data.movies || [];
                    currentData.series = data.series || [];
                    console.log('✅ Content loaded from database');
                } else {
                    throw new Error(data.message || 'API returned an error');
                }
            } catch (error) {
                console.error('Error loading content from DB:', error);
                showStatus('error', `Could not load content: ${error.message}`);
            }
        }

        async function generateFromTMDB(type) {
            const idInput = document.getElementById(type === 'movie' ? 'movie-tmdb-id' : 'series-tmdb-id');
            const tmdbId = idInput.value.trim();
            const loadingSpinner = document.getElementById(type === 'movie' ? 'movie-loading' : 'series-loading');

            if (!tmdbId) {
                showStatus('error', 'Please enter a TMDB ID.');
                return;
            }

            loadingSpinner.style.display = 'inline-block';
            showStatus('info', `Generating ${type} with TMDB ID: ${tmdbId}...`);

            const formData = new FormData();
            formData.append('tmdb_id', tmdbId);

            const apiUrl = type === 'movie' ? '../api/generate_movie.php' : '../api/generate_series.php';

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showStatus('success', result.message);
                    idInput.value = ''; // Clear input on success
                    await loadContentFromDB(); // Reload data from the database
                    updatePreview(); // Refresh the preview grid
                } else {
                    showStatus('error', result.message || 'An unknown error occurred.');
                }
            } catch (error) {
                showStatus('error', `Failed to communicate with the server: ${error.message}`);
            } finally {
                loadingSpinner.style.display = 'none';
            }
        }

        function closeModal() {
            const modal = document.getElementById('edit-modal');
            modal.style.display = 'none';
        }

        async function editContent(id, type) {
            showStatus('info', `Fetching details for ${type} ID: ${id}...`);
            try {
                const response = await fetch(`../api/get_item.php?id=${id}&type=${type}`);
                const result = await response.json();

                if (result.success) {
                    const item = result.data;
                    // Populate modal fields
                    document.getElementById('edit-id').value = item.id;
                    document.getElementById('edit-type').value = type;
                    document.getElementById('edit-title').value = item.title;
                    document.getElementById('edit-overview').value = item.overview;
                    document.getElementById('edit-poster-url').value = item.poster_url;
                    document.getElementById('edit-backdrop-url').value = item.backdrop_url;
                    document.getElementById('edit-year').value = item.release_year;

                    // Show the modal
                    const modal = document.getElementById('edit-modal');
                    modal.style.display = 'block';
                } else {
                    showStatus('error', result.message);
                }
            } catch (error) {
                showStatus('error', `Failed to fetch item details: ${error.message}`);
            }
        }

        async function saveChanges(event) {
            event.preventDefault();
            showStatus('info', 'Saving changes...');

            const id = document.getElementById('edit-id').value;
            const type = document.getElementById('edit-type').value;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('type', type);
            formData.append('title', document.getElementById('edit-title').value);
            formData.append('overview', document.getElementById('edit-overview').value);
            formData.append('poster_url', document.getElementById('edit-poster-url').value);
            formData.append('backdrop_url', document.getElementById('edit-backdrop-url').value);
            formData.append('release_year', document.getElementById('edit-year').value);

            try {
                const response = await fetch('../api/edit_content.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    closeModal();
                    showStatus('success', result.message);
                    await loadContentFromDB();
                    updatePreview();
                } else {
                    showStatus('error', result.message || 'An unknown error occurred while saving.');
                }
            } catch (error) {
                showStatus('error', `Failed to communicate with the server: ${error.message}`);
            }
        }

        async function deleteContent(id, type) {
            if (!confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
                return;
            }

            showStatus('info', `Deleting ${type}...`);

            const formData = new FormData();
            formData.append('id', id);
            formData.append('type', type);

            try {
                const response = await fetch('../api/delete_content.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showStatus('success', result.message);
                    await loadContentFromDB(); // Reload data from the database
                    updatePreview(); // Refresh the preview grid
                } else {
                    showStatus('error', result.message || 'An unknown error occurred during deletion.');
                }
            } catch (error) {
                showStatus('error', `Failed to communicate with the server: ${error.message}`);
            }
        }

        async function importData() {
            const fileInput = document.getElementById('import-file');
            const file = fileInput.files[0];

            if (!file) {
                showStatus('error', 'Please select a JSON file to import.');
                return;
            }

            showStatus('info', 'Importing file... This may take a moment.');
            const loadingSpinner = document.getElementById('import-loading');
            loadingSpinner.style.display = 'inline-block';

            const formData = new FormData();
            formData.append('importFile', file);

            try {
                const response = await fetch('../api/import_json.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showStatus('success', result.message);
                    fileInput.value = ''; // Clear the file input
                    await loadContentFromDB();
                    updatePreview();
                } else {
                    showStatus('error', `Import failed: ${result.message}`);
                }
            } catch (error) {
                showStatus('error', `An error occurred during import: ${error.message}`);
            } finally {
                loadingSpinner.style.display = 'none';
            }
        }

        async function changePassword(event) {
            event.preventDefault();

            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                showStatus('error', 'New passwords do not match.');
                return;
            }

            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);

            showStatus('info', 'Changing password...');

            try {
                const response = await fetch('../api/change_password.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    showStatus('success', result.message);
                    document.getElementById('change-password-form').reset();
                } else {
                    showStatus('error', result.message);
                }
            } catch (error) {
                showStatus('error', `An error occurred: ${error.message}`);
            }
        }

        function updatePreview() {
            const filter = document.getElementById('preview-filter')?.value || 'all';
            const searchTerm = document.getElementById('preview-search')?.value.toLowerCase() || '';
            const container = document.getElementById('content-preview');

            if (!container) return;

            let allItems = [];

            // Process movies
            if (filter === 'all' || filter === 'movie') {
                currentData.movies.forEach(movie => {
                    if (movie.title.toLowerCase().includes(searchTerm)) {
                        allItems.push({
                            ...movie,
                            type: 'movie',
                            category: 'Movies',
                            image: movie.poster_url,
                            // sources are not directly available, will need to fetch on edit
                        });
                    }
                });
            }

            // Process series
            if (filter === 'all' || filter === 'series') {
                currentData.series.forEach(series => {
                    if (series.title.toLowerCase().includes(searchTerm)) {
                        allItems.push({
                            ...series,
                            type: 'series',
                            category: 'TV Series',
                            image: series.poster_url,
                            // sources are not directly available, will need to fetch on edit
                        });
                    }
                });
            }

            const totalPages = Math.ceil(allItems.length / itemsPerPage);
            currentPage = Math.max(1, Math.min(currentPage, totalPages));

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const itemsToRender = allItems.slice(startIndex, endIndex);

            container.innerHTML = '';
            const fragment = document.createDocumentFragment();

            itemsToRender.forEach(item => {
                const div = document.createElement('div');
                div.className = 'preview-item';

                div.innerHTML = `
                    <img src="${item.image || 'https://via.placeholder.com/300x450?text=No+Image'}"
                         alt="${item.title}" loading="lazy">
                    <div class="info">
                        <div class="title">${item.title}</div>
                        <div class="meta">${item.year || 'Unknown'} • ${item.parental_rating || 'N/A'} • ${item.type?.toUpperCase()} • Rating: ${item.rating || 'N/A'}</div>
                        <div class="meta">Genres: ${item.genres || 'N/A'}</div>
                        <div style="margin-top: 10px;">
                            <button class="btn btn-secondary btn-small" onclick="editContent('${item.id}', '${item.type}')">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteContent('${item.id}', '${item.type}')">Delete</button>
                        </div>
                    </div>
                `;

                fragment.appendChild(div);
            });

            container.appendChild(fragment);

            // Update pagination controls
            document.getElementById('page-info').textContent = `Page ${currentPage} of ${totalPages || 1}`;
            document.getElementById('prev-page').disabled = currentPage === 1;
            document.getElementById('next-page').disabled = currentPage === totalPages || totalPages === 0;
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

        // Keyboard navigation support
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });
            });
        });
    </script>
</body>
</html>
