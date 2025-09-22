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
            Categories: [
                {
                    MainCategory: "Live TV",
                    SubCategories: ["Entertainment"],
                    Entries: []
                },
                {
                    MainCategory: "Movies", 
                    SubCategories: ["Action", "Comedy", "Drama", "Horror", "Sci-Fi"],
                    Entries: []
                },
                {
                    MainCategory: "TV Series",
                    SubCategories: ["Anime", "Action", "Comedy", "Drama"],
                    Entries: []
                }
            ]
        };

        let nextId = 1;
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

                         // Initialize
        document.addEventListener('DOMContentLoaded', async function() {
            showStatus('info', 'Loading saved data...');
            await loadSavedData();
                        updateDataStats();
            updatePreview();
            updateAutoEmbedStatus();
            updateApiDropdown(); // Initialize API dropdown
            handleSearchTypeChange(); // Initialize search type
            refreshContentCheckboxes();
            showStatus('success', 'Application loaded successfully!');
             
                         // Add event listeners for auto-embed configuration
            const autoEmbedElements = [
                'auto-vidsrc', 'vidsrc-quality',
                'auto-vidjoy', 'vidjoy-quality', 
                'auto-multiembed', 'multiembed-quality',
                'auto-embedsu', 'embedsu-quality',
                'auto-vidsrcme', 'vidsrcme-quality',
                'auto-autoembed', 'autoembed-quality',
                'auto-smashystream', 'smashystream-quality',
                'auto-vidsrcto', 'vidsrcto-quality',
                'auto-vidsrcxyz', 'vidsrcxyz-quality',
                'auto-embedsoap', 'embedsoap-quality',
                'auto-moviesapi', 'moviesapi-quality',
                'auto-dbgo', 'dbgo-quality',
                'auto-flixhq', 'flixhq-quality',
                'auto-gomovies', 'gomovies-quality',
                'auto-showbox', 'showbox-quality',
                'auto-primewire', 'primewire-quality',
                'auto-hdtoday', 'hdtoday-quality',
                'auto-vidcloud', 'vidcloud-quality',
                'auto-streamwish', 'streamwish-quality',
                'auto-doodstream', 'doodstream-quality',
                'auto-streamtape', 'streamtape-quality',
                'auto-mixdrop', 'mixdrop-quality',
                'auto-videasy', 'videasy-quality',
                'auto-upstream', 'upstream-quality',
                'auto-vidlink', 'vidlink-quality'
            ];
             
             autoEmbedElements.forEach(id => {
                 const element = document.getElementById(id);
                 if (element) {
                     element.addEventListener('change', updateAutoEmbedStatus);
                 }
             });
             
                         // Add test functions to window for debugging
            window.testUnityVerification = function() {
                console.log('🧪 Testing Unity Verification...');
                console.log('📊 Current data structure:');
                console.log('Movies category:', currentData.Categories.find(c => c.MainCategory === "Movies")?.Entries.length || 0, 'entries');
                console.log('TV Series category:', currentData.Categories.find(c => c.MainCategory === "TV Series")?.Entries.length || 0, 'entries');
                console.log('Live TV category:', currentData.Categories.find(c => c.MainCategory === "Live TV")?.Entries.length || 0, 'entries');
                
                console.log('\n🎬 Test movie generation: Fight Club (ID: 550)');
                generateFromTMDB('movie', 550);
                
                console.log('📺 Test series generation: Game of Thrones (ID: 1399)');
                generateFromTMDB('series', 1399);
                
                console.log('\n✅ Check console logs above to verify:');
                console.log('1. Movies should go to Movies category');
                console.log('2. Series should go to TV Series category');
                console.log('3. Season numbers should be 1, 2, 3... not concatenated');
                console.log('4. Generate buttons in search results should work');
            };
            
            window.testSourceGeneration = function() {
                 console.log('Testing source generation...');
                 console.log('VIDSRC_BASE:', VIDSRC_BASE);
                 console.log('VIDJOY_BASE:', VIDJOY_BASE);
                 
                 // Test movie source generation with multiple quality options (separate sources)
                 const testMovieId = 550; // Fight Club
                 const movieSources = [
                     {
                         id: 1,
                         type: "embed",
                         title: "VidSrc Server 1080p",
                         quality: "1080p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/movie/${testMovieId}`
                     },
                     {
                         id: 2,
                         type: "embed",
                         title: "VidSrc Server 720p",
                         quality: "720p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/movie/${testMovieId}`
                     },
                     {
                         id: 3,
                         type: "embed",
                         title: "VidSrc Server 480p",
                         quality: "480p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/movie/${testMovieId}`
                     },
                     {
                         id: 4,
                         type: "embed", 
                         title: "VidJoy Server 1080p",
                         quality: "1080p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/movie/${testMovieId}`
                     },
                     {
                         id: 5,
                         type: "embed", 
                         title: "VidJoy Server 720p",
                         quality: "720p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/movie/${testMovieId}`
                     },
                     {
                         id: 6,
                         type: "embed", 
                         title: "VidJoy Server 480p",
                         quality: "480p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/movie/${testMovieId}`
                     }
                 ];
                 
                 console.log('Test movie sources (separate servers):', movieSources);
                 
                 // Test series source generation with multiple quality options (separate sources)
                 const testSeriesId = 1399; // Game of Thrones
                 const seriesSources = [
                     {
                         id: 7,
                         type: "embed",
                         title: "VidSrc Server 1080p",
                         quality: "1080p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/tv/${testSeriesId}/1/1`
                     },
                     {
                         id: 8,
                         type: "embed",
                         title: "VidSrc Server 720p",
                         quality: "720p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/tv/${testSeriesId}/1/1`
                     },
                     {
                         id: 9,
                         type: "embed",
                         title: "VidSrc Server 480p",
                         quality: "480p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDSRC_BASE}/tv/${testSeriesId}/1/1`
                     },
                     {
                         id: 10,
                         type: "embed", 
                         title: "VidJoy Server 1080p",
                         quality: "1080p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/tv/${testSeriesId}/1/1`
                     },
                     {
                         id: 11,
                         type: "embed", 
                         title: "VidJoy Server 720p",
                         quality: "720p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/tv/${testSeriesId}/1/1`
                     },
                     {
                         id: 12,
                         type: "embed", 
                         title: "VidJoy Server 480p",
                         quality: "480p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: `${VIDJOY_BASE}/tv/${testSeriesId}/1/1`
                     }
                 ];
                 
                 console.log('Test series sources (separate servers):', seriesSources);
                 return { movieSources, seriesSources };
             };
             
             console.log('✅ CineMax Enhanced API Manager loaded!');
             console.log('🎬 Multi-Server Sources: VidSrc.net + VidJoy.pro (Matching existing app structure)');
             console.log('🔧 Fixed: Root-level movies array for GenreActivity & TV Series');
             console.log('🎯 Fixed: Source kind values - "play" for embeds/live TV, "both" for direct links');
             console.log('📺 Enhanced: Multiple quality options per server (1080p + 720p + 480p)');
             console.log('🆕 NEW: Server editing in Data Management tab');
             console.log('🆕 NEW: Add server functionality for movies and TV series episodes');
             console.log('🆕 NEW: Paste button for each server input field');
             console.log('🆕 NEW: Quick add server to content and episodes');
             console.log('🧪 Test with: testSourceGeneration()');
             
             // Show info about the fix
             setTimeout(() => {
                 showStatus('success', 'ENHANCED: Multi-server sources matching existing app structure! No CineMax changes needed!');
             }, 2000);
         });

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

        // Handle Enter key in TMDB ID input
        function handleTmdbEnterKey(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const selectElement = document.getElementById('auto-embed-content-select');
                if (selectElement.value) {
                    applyAutoEmbedToSelected();
                } else {
                    showStatus('warning', 'Please select content first before updating TMDB ID');
                }
            }
        }

        // TMDB API functions
        async function fetchTMDB(endpoint, params = {}) {
            const url = new URL(`${TMDB_BASE_URL}${endpoint}`);
            url.searchParams.append('api_key', getTMDBApiKey());
            
            Object.entries(params).forEach(([key, value]) => {
                url.searchParams.append(key, value);
            });

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    // If rate limited or API key issue, try switching to backup
                    if (response.status === 429 || response.status === 401) {
                        return await retryWithBackupApi(endpoint, params);
                    }
                    throw new Error(`TMDB API error: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('TMDB fetch error:', error);
                showStatus('error', `TMDB API Error: ${error.message}`);
                return null;
            }
        }
        
        // Retry with backup API keys
        async function retryWithBackupApi(endpoint, params = {}) {
            const apiKeys = Object.keys(TMDB_API_KEYS);
            const currentIndex = apiKeys.indexOf(currentApiKey);
            
            for (let i = 1; i < apiKeys.length; i++) {
                const nextIndex = (currentIndex + i) % apiKeys.length;
                const nextKey = apiKeys[nextIndex];
                
                try {
                    const url = new URL(`${TMDB_BASE_URL}${endpoint}`);
                    url.searchParams.append('api_key', TMDB_API_KEYS[nextKey]);
                    
                    Object.entries(params).forEach(([key, value]) => {
                        url.searchParams.append(key, value);
                    });
                    
                    const response = await fetch(url);
                    if (response.ok) {
                        currentApiKey = nextKey; // Switch to working key
                        updateApiDropdown();
                        showStatus('success', `Switched to backup API key: ${nextKey}`);
                        return await response.json();
                    }
                } catch (error) {
                    continue; // Try next key
                }
            }
            
            throw new Error('All API keys exhausted');
        }

        // Handle search type change
        function handleSearchTypeChange() {
            const searchType = document.getElementById('search-type').value;
            const searchInputGroup = document.getElementById('search-input-group');
            const regionalBrowseGroup = document.getElementById('regional-browse-group');
            const yearFilter = document.getElementById('year-filter');
            
            if (searchType === 'search') {
                // Show search mode
                searchInputGroup.style.display = 'block';
                regionalBrowseGroup.style.display = 'none';
                clearSearchResults();
            } else if (REGIONAL_CONFIGS[searchType]) {
                // Show regional browse mode
                searchInputGroup.style.display = 'none';
                regionalBrowseGroup.style.display = 'block';
                
                // Reset year selection and clear results
                yearFilter.value = '';
                clearSearchResults();
                
                // Show instruction to select year
                const resultsContainer = document.getElementById('search-results');
                if (resultsContainer) {
                    resultsContainer.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            <h3>🎭 ${REGIONAL_CONFIGS[searchType].name} Browser</h3>
                            <p>Please select a year above to browse ${REGIONAL_CONFIGS[searchType].name} content from that period.</p>
                            <small>Individual years (1995-2025) or comprehensive decade collections available.<br>
                            No limits - all available content will be shown!</small>
                        </div>
                    `;
                }
            }
        }
        
        // Load all content for a specific region
        async function loadAllRegionalContent(regionType) {
            const config = REGIONAL_CONFIGS[regionType];
            if (!config) return;
            
            showSearchLoading(true);
            clearSearchResults();
            
            try {
                let allResults = [];
                
                // Fetch content from recent years (2015-2025)
                for (let year = 2025; year >= 2015; year--) {
                    const yearResults = await fetchRegionalContentByYear(regionType, year);
                    if (yearResults && yearResults.length > 0) {
                        allResults = [...allResults, ...yearResults];
                    }
                    
                    // Add small delay to avoid rate limiting
                    await new Promise(resolve => setTimeout(resolve, 100));
                }
                
                if (allResults.length > 0) {
                    displaySearchResults(allResults, config.name);
                    showStatus('success', `Found ${allResults.length} ${config.name} titles`);
                } else {
                    showStatus('info', `No ${config.name} content found`);
                }
            } catch (error) {
                console.error('Error loading regional content:', error);
                showStatus('error', `Error loading ${config.name} content`);
            } finally {
                showSearchLoading(false);
            }
        }
        
        // Load content for specific region and year
        async function loadRegionalContent() {
            const searchType = document.getElementById('search-type').value;
            const year = document.getElementById('year-filter').value;
            const contentType = document.getElementById('regional-content-type').value;
            
            if (!year || !REGIONAL_CONFIGS[searchType]) return;
            
            showLoading('search-loading', true);
            clearSearchResults();
            
            try {
                let results = [];
                let titleSuffix = '';
                const contentLabel = contentType === 'tv' ? 'Series/Dramas' : 
                                  contentType === 'movie' ? 'Movies' : 'Movies & Series';
                
                if (year === 'all-recent') {
                    // Load content from 2020-2025
                    titleSuffix = `${contentLabel} (2020-2025)`;
                    for (let y = 2025; y >= 2020; y--) {
                        const yearResults = await fetchRegionalContentByYear(searchType, y, contentType);
                        if (yearResults && yearResults.length > 0) {
                            results = [...results, ...yearResults];
                        }
                        // Small delay to avoid rate limiting
                        await new Promise(resolve => setTimeout(resolve, 200));
                    }
                } else if (year === 'all-2010s') {
                    // Load content from 2010-2019
                    titleSuffix = `${contentLabel} (2010-2019)`;
                    for (let y = 2019; y >= 2010; y--) {
                        const yearResults = await fetchRegionalContentByYear(searchType, y, contentType);
                        if (yearResults && yearResults.length > 0) {
                            results = [...results, ...yearResults];
                        }
                        await new Promise(resolve => setTimeout(resolve, 150));
                    }
                } else if (year === 'all-2000s') {
                    // Load content from 2000-2009
                    titleSuffix = `${contentLabel} (2000-2009)`;
                    for (let y = 2009; y >= 2000; y--) {
                        const yearResults = await fetchRegionalContentByYear(searchType, y, contentType);
                        if (yearResults && yearResults.length > 0) {
                            results = [...results, ...yearResults];
                        }
                        await new Promise(resolve => setTimeout(resolve, 150));
                    }
                } else if (year === 'all-classic') {
                    // Load content from 1990-1999
                    titleSuffix = `${contentLabel} (1990-1999)`;
                    for (let y = 1999; y >= 1990; y--) {
                        const yearResults = await fetchRegionalContentByYear(searchType, y, contentType);
                        if (yearResults && yearResults.length > 0) {
                            results = [...results, ...yearResults];
                        }
                        await new Promise(resolve => setTimeout(resolve, 150));
                    }
                } else if (year === 'all-time') {
                    // Load ALL content from 1990-2025
                    titleSuffix = `${contentLabel} (All Time 1990-2025)`;
                    for (let y = 2025; y >= 1990; y--) {
                        const yearResults = await fetchRegionalContentByYear(searchType, y, contentType);
                        if (yearResults && yearResults.length > 0) {
                            results = [...results, ...yearResults];
                        }
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                } else {
                    // Load content for specific year
                    titleSuffix = `${contentLabel} (${year})`;
                    results = await fetchRegionalContentByYear(searchType, year, contentType);
                }
                
                if (results && results.length > 0) {
                    // Remove duplicates based on TMDB ID
                    const uniqueResults = results.filter((item, index, self) => 
                        index === self.findIndex(t => t.id === item.id)
                    );
                    
                    // Sort by popularity and first air date
                    uniqueResults.sort((a, b) => {
                        const dateA = new Date(a.first_air_date || '1900-01-01');
                        const dateB = new Date(b.first_air_date || '1900-01-01');
                        // Primary sort by year (newest first), secondary by popularity
                        if (dateB.getFullYear() !== dateA.getFullYear()) {
                            return dateB.getFullYear() - dateA.getFullYear();
                        }
                        return (b.popularity || 0) - (a.popularity || 0);
                    });
                    
                    displaySearchResults(uniqueResults, `${REGIONAL_CONFIGS[searchType].name} ${titleSuffix}`);
                    showStatus('success', `Found ${uniqueResults.length} ${REGIONAL_CONFIGS[searchType].name} titles ${titleSuffix}`);
                } else {
                    showStatus('info', `No ${REGIONAL_CONFIGS[searchType].name} content found ${titleSuffix}`);
                }
            } catch (error) {
                console.error('Error loading regional content:', error);
                showStatus('error', 'Error loading content');
            } finally {
                showLoading('search-loading', false);
            }
        }
        
        // Fetch regional content by year - comprehensive search across multiple pages
        async function fetchRegionalContentByYear(regionType, year, contentType = 'tv') {
            const config = REGIONAL_CONFIGS[regionType];
            if (!config) return [];
            
            try {
                let allResults = [];
                
                // Fetch both movies and TV shows if contentType is 'both'
                const typesToFetch = contentType === 'both' ? ['tv', 'movie'] : [contentType];
                
                for (const type of typesToFetch) {
                    let currentPage = 1;
                    let totalPages = 1;
                    
                    // Fetch all pages of results for this content type
                    do {
                        const params = {
                            with_origin_country: config.origin_country,
                            with_original_language: config.language,
                            sort_by: 'popularity.desc',
                            page: currentPage
                        };
                        
                        // Use appropriate date field for movies vs TV shows
                        if (type === 'movie') {
                            params.primary_release_year = year;
                        } else {
                            params.first_air_date_year = year;
                        }
                        
                        // Add genre filter if specified
                        if (config.genres && config.genres.length > 0) {
                            params.with_genres = config.genres.join(',');
                        }
                        
                        const data = await fetchTMDB(`/discover/${type}`, params);
                        
                        if (data && data.results) {
                            // Add content type to each result for identification
                            const resultsWithType = data.results.map(item => ({
                                ...item,
                                content_type: type
                            }));
                            
                            allResults = [...allResults, ...resultsWithType];
                            totalPages = data.total_pages || 1;
                            currentPage++;
                            
                            // Small delay between requests to be nice to the API
                            if (currentPage <= totalPages) {
                                await new Promise(resolve => setTimeout(resolve, 100));
                            }
                        } else {
                            break;
                        }
                        
                        // Fetch up to 500 pages to get comprehensive results
                    } while (currentPage <= totalPages && currentPage <= 500);
                    
                    // Small delay between content types
                    if (typesToFetch.length > 1) {
                        await new Promise(resolve => setTimeout(resolve, 200));
                    }
                }
                
                if (allResults.length > 0) {
                    // Sort by release date (newest first), then by popularity
                    return allResults.sort((a, b) => {
                        const dateA = new Date(a.first_air_date || a.release_date || '1900-01-01');
                        const dateB = new Date(b.first_air_date || b.release_date || '1900-01-01');
                        
                        // Primary sort by date
                        if (dateB.getTime() !== dateA.getTime()) {
                            return dateB - dateA;
                        }
                        
                        // Secondary sort by popularity
                        return (b.popularity || 0) - (a.popularity || 0);
                    });
                }
                
                return [];
            } catch (error) {
                console.error(`Error fetching ${regionType} ${contentType} content for ${year}:`, error);
                return [];
            }
        }
        async function searchTMDB() {
            const searchType = document.getElementById('search-type').value;
            
            // Only handle search mode here
            if (searchType !== 'search') {
                return;
            }
            
            const query = document.getElementById('tmdb-search').value.trim();
            const type = document.getElementById('search-subtype').value;
            
            if (!query) {
                showStatus('warning', 'Please enter a search query');
                return;
            }

            showLoading('search-loading', true);
            
            try {
                const endpoint = type === 'multi' ? '/search/multi' : `/search/${type}`;
                const results = await fetchTMDB(endpoint, { query });
                
                if (results && results.results && results.results.length > 0) {
                    displaySearchResults(results.results, `Search Results for "${query}"`);
                    showStatus('success', `Found ${results.results.length} results for "${query}"`);
                } else {
                    showStatus('warning', 'No results found');
                }
            } catch (error) {
                console.error('Search error:', error);
                showStatus('error', 'Search failed. Please try again.');
            } finally {
                showLoading('search-loading', false);
            }
        }
        
        // Helper functions for search UI
        function clearSearchResults() {
            const resultsContainer = document.getElementById('search-results');
            if (resultsContainer) {
                resultsContainer.innerHTML = '';
            }
        }

        function displaySearchResults(results, title = 'Search Results') {
            const container = document.getElementById('search-results');
            container.innerHTML = `<h3>${title}</h3>`;

            // Show total results count
            const totalResults = results.length;
            const resultsInfo = document.createElement('div');
            resultsInfo.className = 'results-info';
            resultsInfo.innerHTML = `<p><strong>${totalResults}</strong> results found. Showing all results.</p>`;
            container.appendChild(resultsInfo);

            // Display all results instead of limiting to 20
            results.forEach(item => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                
                // Determine media type with robust detection
                let mediaType;
                if (item.content_type) {
                    // Regional search provides content_type
                    mediaType = item.content_type;
                } else if (item.media_type) {
                    // Multi search provides media_type
                    mediaType = item.media_type;
                } else {
                    // Specific searches don't provide media_type, detect by properties
                    mediaType = item.title ? 'movie' : 'tv';
                }
                
                // Convert to generator type (our functions expect 'series' not 'tv')
                const generatorType = mediaType === 'tv' ? 'series' : 'movie';
                
                const title = item.title || item.name;
                
                // Debug logging (can be removed in production)
                console.log(`🔍 Search result: ${title} → ${mediaType} → ${generatorType}`);
                const year = (item.release_date || item.first_air_date || '').substring(0, 4);
                const poster = item.poster_path ? `${TMDB_IMAGE_BASE}${item.poster_path}` : 'https://via.placeholder.com/300x450?text=No+Image';

                div.innerHTML = `
                    <img src="${poster}" alt="${title}" loading="lazy">
                    <div class="info">
                        <div class="title">${title}</div>
                        <div class="meta">${year} • ${mediaType === 'movie' ? '🎬' : '📺'} ${mediaType.toUpperCase()} • ID: ${item.id}</div>
                        <button class="btn btn-primary btn-small" onclick="generateFromTMDB('${generatorType}', ${item.id})">
                            Generate
                        </button>
                    </div>
                `;
                
                container.appendChild(div);
            });
        }

                 async function generateFromTMDB(type, tmdbId = null) {
             console.log(`🎬 generateFromTMDB called with type: '${type}', tmdbId: ${tmdbId}`);
             
             const id = tmdbId || document.getElementById(`${type}-tmdb-id`).value;
             
             if (!id) {
                 showStatus('warning', 'Please enter a TMDB ID');
                 return;
             }

             showLoading(`${type}-loading`, true);
             showStatus('info', `Generating ${type} with automatic VidSrc & VidJoy sources...`);
             
             try {
                 if (type === 'movie') {
                     console.log(`🎞️ Generating movie with ID: ${id}`);
                     await generateMovie(id);
                 } else if (type === 'series') {
                     console.log(`📺 Generating series with ID: ${id}`);
                     await generateSeries(id);
                 } else {
                     throw new Error(`Unknown content type: '${type}'. Expected 'movie' or 'series'.`);
                 }
                 
                 updateDataStats();
                 updatePreview();
                 showStatus('success', `${type} generated successfully with automatic video sources!`);
             } catch (error) {
                 showStatus('error', `Error generating ${type}: ${error.message}`);
             }
             
             showLoading(`${type}-loading`, false);
         }

                 async function generateMovie(tmdbId) {
             const movieData = await fetchTMDB(`/movie/${tmdbId}`);
             const credits = await fetchTMDB(`/movie/${tmdbId}/credits`);
             const videos = await fetchTMDB(`/movie/${tmdbId}/videos`);
             const releaseDates = await fetchTMDB(`/movie/${tmdbId}/release_dates`);
             
             if (!movieData) throw new Error('Failed to fetch movie data');

             // Get additional servers
             const serverInputs = document.querySelectorAll('#movie-servers .server-item');
             const additionalSources = [];
             
             serverInputs.forEach(item => {
                 const name = item.querySelector('.server-name').value.trim();
                 const url = item.querySelector('.server-url').value.trim();
                 if (name && url) {
                     additionalSources.push({
                         id: nextId++,
                         type: getSourceType(url),
                         title: name,
                         quality: "Auto",
                         size: "Unknown",
                         kind: getSourceKind(url),
                         premium: "false",
                         external: false,
                         url: url
                     });
                 }
             });

                         // Auto-generate embed sources based on configuration
            const autoSources = generateEmbedSources(tmdbId, 'movie');

             console.log('Auto-generated movie sources:', autoSources);
             console.log('Additional sources:', additionalSources);

             const movie = {
                 id: nextId++,
                 title: movieData.title,
                 type: "movie",
                 label: movieData.genres[0]?.name || "Movie",
                 sublabel: `Released ${movieData.release_date?.substring(0, 4) || 'Unknown'}`,
                 imdb: movieData.vote_average?.toString() || "0",
                 downloadas: `${movieData.title.toLowerCase().replace(/\s+/g, '-')}.mp4`,
                 comment: true,
                 playas: "video",
                 description: movieData.overview || "No description available",
                 parentalRating: getMovieCertification(releaseDates),
                 year: movieData.release_date?.substring(0, 4) || "Unknown",
                 duration: formatDuration(movieData.runtime),
                 rating: movieData.vote_average || 0,
                 image: movieData.poster_path ? `${TMDB_IMAGE_BASE}${movieData.poster_path}` : null,
                 cover: movieData.backdrop_path ? `${TMDB_IMAGE_BASE}${movieData.backdrop_path}` : null,
                 genres: movieData.genres?.map(g => ({ id: g.id, title: g.name })) || [],
                 sources: [...autoSources, ...additionalSources],
                 trailer: getTrailer(videos),
                 actors: getActors(credits),
                 subtitles: await getSubtitles(tmdbId, 'movie'),
                 views: Math.floor(Math.random() * 10000) + 1000,
                 created_at: new Date().toISOString().split('T')[0]
             };

             console.log('Generated movie with sources:', movie.sources);

            // Convert to Categories structure
            const movieEntry = {
                Title: movie.title,
                SubCategory: movie.genres?.[0]?.title || "Action",
                Country: "",
                Description: movie.description,
                Poster: movie.image,
                Thumbnail: movie.image,
                Rating: Math.round(movie.rating),
                Duration: movie.duration,
                Year: parseInt(movie.year),
                 parentalRating: movie.parentalRating,
                Servers: movie.sources.map(source => ({
                    name: source.name || source.title,
                    url: source.url
                }))
            };

            // Add to Movies category
            const moviesCategory = currentData.Categories.find(cat => cat.MainCategory === "Movies");
            if (moviesCategory) {
                // Add genre to subcategories if not exists
                const genre = movie.genres?.[0]?.title || "Action";
                if (!moviesCategory.SubCategories.includes(genre)) {
                    moviesCategory.SubCategories.push(genre);
                }
                moviesCategory.Entries.push(movieEntry);
                console.log(`✅ Movie '${movieEntry.Title}' added to Movies category with ${movieEntry.Servers.length} servers`);
            } else {
                console.error('❌ Movies category not found!');
            }
            
            await saveData();
        }

        // Missing Content Detection and Auto-Generation
        async function detectAndGenerateMissingContent() {
            const seriesCategory = currentData.Categories.find(cat => cat.MainCategory === "TV Series");
            if (!seriesCategory) {
                showStatus('error', 'TV Series category not found');
                return;
            }

            let totalMissingGenerated = 0;
            const processingStatus = document.getElementById('auto-embed-status') || createStatusElement();
            
            processingStatus.style.display = 'block';
            processingStatus.className = 'status info';
            processingStatus.innerHTML = 'Detecting missing seasons and episodes...';

            for (const entry of seriesCategory.Entries) {
                try {
                    // Extract TMDB ID from existing sources (if available)
                    const tmdbId = extractTMDBIdFromEntry(entry);
                    if (!tmdbId) {
                        console.log(`Skipping ${entry.Title}: No TMDB ID found`);
                        continue;
                    }

                    processingStatus.innerHTML = `Processing: ${entry.Title}...`;
                    
                    // Fetch complete series data from TMDB
                    const seriesData = await fetchTMDB(`/tv/${tmdbId}`);
                    if (!seriesData) continue;

                    // Get all seasons from TMDB (including season 0 for specials)
                    const allSeasons = seriesData.seasons || [];
                    const existingSeasons = new Set(entry.Seasons?.map(s => s.Season) || []);
                    
                    let missingGenerated = 0;

                    for (const tmdbSeason of allSeasons) {
                        if (!existingSeasons.has(tmdbSeason.season_number)) {
                            // Missing season - generate it
                            console.log(`Generating missing season ${tmdbSeason.season_number} for ${entry.Title}`);
                            const newSeason = await generateMissingSeason(tmdbId, tmdbSeason.season_number, entry);
                            if (newSeason) {
                                if (!entry.Seasons) entry.Seasons = [];
                                entry.Seasons.push(newSeason);
                                missingGenerated++;
                            }
                        } else {
                            // Season exists, check for missing episodes
                            const existingSeason = entry.Seasons.find(s => s.Season === tmdbSeason.season_number);
                            if (existingSeason) {
                                const seasonData = await fetchTMDB(`/tv/${tmdbId}/season/${tmdbSeason.season_number}`);
                                if (seasonData) {
                                    const allEpisodes = seasonData.episodes || [];
                                    const existingEpisodes = new Set(existingSeason.Episodes?.map(e => e.Episode) || []);
                                    
                                    for (const tmdbEpisode of allEpisodes) {
                                        if (!existingEpisodes.has(tmdbEpisode.episode_number)) {
                                            // Missing episode - generate it
                                            console.log(`Generating missing episode S${tmdbSeason.season_number}E${tmdbEpisode.episode_number} for ${entry.Title}`);
                                            const newEpisode = await generateMissingEpisode(tmdbId, tmdbSeason.season_number, tmdbEpisode, entry);
                                            if (newEpisode) {
                                                if (!existingSeason.Episodes) existingSeason.Episodes = [];
                                                existingSeason.Episodes.push(newEpisode);
                                                // Sort episodes by episode number
                                                existingSeason.Episodes.sort((a, b) => a.Episode - b.Episode);
                                                missingGenerated++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    // Sort seasons by season number
                    if (entry.Seasons) {
                        entry.Seasons.sort((a, b) => a.Season - b.Season);
                    }
                    
                    totalMissingGenerated += missingGenerated;
                    
                    // Small delay to prevent API rate limiting
                    await new Promise(resolve => setTimeout(resolve, 100));
                    
                } catch (error) {
                    console.error(`Error processing ${entry.Title}:`, error);
                    continue;
                }
            }

            await saveData();
            
            processingStatus.className = 'status success';
            processingStatus.innerHTML = `✅ Auto-generation complete! Generated ${totalMissingGenerated} missing seasons/episodes`;
            
            setTimeout(() => {
                processingStatus.style.display = 'none';
            }, 5000);

            showStatus('success', `Generated ${totalMissingGenerated} missing seasons and episodes`);
        }

        // Extract TMDB ID from entry sources
        function extractTMDBIdFromEntry(entry) {
            // Look for TMDB ID in VidSrc, VidJoy, MultiEmbed, and other embed URLs
            for (const season of entry.Seasons || []) {
                for (const episode of season.Episodes || []) {
                    for (const server of episode.Servers || []) {
                        const url = server.url;
                        if (!url) continue;
                        
                        // VidSrc format: https://vidsrc.net/embed/tv/TMDB_ID/season/episode
                        let match = url.match(/vidsrc\.net\/embed\/tv\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidJoy format: https://vidjoy.pro/embed/tv/TMDB_ID/season/episode
                        match = url.match(/vidjoy\.pro\/embed\/tv\/(\d+)/);
                        if (match) return match[1];
                        
                        // MultiEmbed format: video_id=TMDB_ID
                        match = url.match(/video_id=(\d+)/);
                        if (match) return match[1];
                        
                        // Embed.su format: https://embed.su/embed/tv/TMDB_ID/season/episode
                        match = url.match(/embed\.su\/embed\/tv\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidSrc.me format: https://vidsrc.me/embed/tv/TMDB_ID/season/episode
                        match = url.match(/vidsrc\.me\/embed\/tv\/(\d+)/);
                        if (match) return match[1];
                        
                        // AutoEmbed format: https://player.autoembed.cc/embed/tv/TMDB_ID/season/episode
                        match = url.match(/autoembed\.cc\/embed\/tv\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidSrc.win formats
                        match = url.match(/vidsrc\.win\/tv\?id=(\d+)&s=\d+&e=\d+/);
                        if (match) return match[1];
                        match = url.match(/vidsrc\.win\/movie\.html\?id=(\d+)/);
                        if (match) return match[1];
                        
                        // VidSrc.to format: https://vidsrc.to/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/vidsrc\.to\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidSrc.xyz format: https://vidsrc.xyz/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/vidsrc\.xyz\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // EmbedSoap format: https://www.embedsoap.com/embed/movie/TMDB_ID
                        match = url.match(/embedsoap\.com\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // MoviesAPI format: https://moviesapi.club/movie/TMDB_ID
                        match = url.match(/moviesapi\.club\/movie\/(\d+)/);
                        if (match) return match[1];
                        
                        // DBGO format: https://dbgo.fun/movie/TMDB_ID
                        match = url.match(/dbgo\.fun\/movie\/(\d+)/);
                        if (match) return match[1];
                        
                        // FlixHQ format: https://flixhq.to/watch/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/flixhq\.to\/watch\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // GoMovies format: https://gomovies.sx/watch/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/gomovies\.sx\/watch\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // ShowBox format: https://www.showbox.media/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/showbox\.media\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // PrimeWire format: https://primewire.mx/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/primewire\.mx\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // HDToday format: https://hdtoday.tv/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/hdtoday\.tv\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidCloud format: https://vidcloud.to/embed/movie/TMDB_ID or tv/TMDB_ID
                        match = url.match(/vidcloud\.to\/embed\/(?:movie|tv)\/(\d+)/);
                        if (match) return match[1];
                        
                        // StreamWish format: https://streamwish.to/e/TMDB_ID
                        match = url.match(/streamwish\.to\/e\/(\d+)/);
                        if (match) return match[1];
                        
                        // DoodStream format: https://doodstream.com/e/TMDB_ID
                        match = url.match(/doodstream\.com\/e\/(\d+)/);
                        if (match) return match[1];
                        
                        // StreamTape format: https://streamtape.com/e/TMDB_ID
                        match = url.match(/streamtape\.com\/e\/(\d+)/);
                        if (match) return match[1];
                        
                        // MixDrop format: https://mixdrop.co/e/TMDB_ID
                        match = url.match(/mixdrop\.co\/e\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidEasy format: https://player.videasy.net/movie/TMDB_ID
                        match = url.match(/player\.videasy\.net\/movie\/(\d+)/);
                        if (match) return match[1];
                        // VidEasy TV format: https://player.videasy.net/tv/TMDB_ID/SEASON/EPISODE
                        match = url.match(/player\.videasy\.net\/tv\/(\d+)\/(\d+)\/(\d+)/);
                        if (match) return match[1];
                        
                        // VidFast format: https://vidfast.pro/movie/TMDB_ID or tv/TMDB_ID/SEASON/EPISODE
                        match = url.match(/vidfast\.pro\/tv\/(\d+)\/(\d+)\/(\d+)/);
                        if (match) return match[1];
                        match = url.match(/vidfast\.pro\/movie\/(\d+)/);
                        if (match) return match[1];
                    }
                }
            }
            return null;
        }

        // Generate missing season
        async function generateMissingSeason(tmdbId, seasonNumber, parentEntry) {
            try {
                const seasonData = await fetchTMDB(`/tv/${tmdbId}/season/${seasonNumber}`);
                if (!seasonData) return null;

                const episodes = [];
                
                for (const episodeData of seasonData.episodes || []) {
                    const newEpisode = await generateMissingEpisode(tmdbId, seasonNumber, episodeData, parentEntry);
                    if (newEpisode) {
                        episodes.push(newEpisode);
                    }
                }

                // Determine season title
                let seasonTitle = seasonData.name || `Season ${seasonNumber}`;
                if (seasonNumber === 0) {
                    seasonTitle = seasonData.name || 'Specials';
                }

                return {
                    Season: seasonNumber,
                    SeasonPoster: parentEntry.Poster || null,
                    Episodes: episodes
                };
            } catch (error) {
                console.error(`Error generating season ${seasonNumber}:`, error);
                return null;
            }
        }

        // Generate missing episode
        async function generateMissingEpisode(tmdbId, seasonNumber, episodeData, parentEntry) {
            try {
                // Auto-generate embed sources (excluding vidjoy, vidsrc, multiembed as requested)
                const autoSources = generateEmbedSources(tmdbId, 'tv', seasonNumber, episodeData.episode_number);
                
                // Generate additional metadata-based sources
                const metadataSources = generateMetadataBasedSources(tmdbId, seasonNumber, episodeData.episode_number, parentEntry.Title);

                const allSources = [...autoSources, ...metadataSources];

                // Determine episode type and title
                let episodeTitle = episodeData.name || `Episode ${episodeData.episode_number}`;
                let episodeType = '';
                
                if (seasonNumber === 0) {
                    // Special episodes (Season 0)
                    if (episodeData.name) {
                        if (episodeData.name.toLowerCase().includes('ova')) {
                            episodeType = ' (OVA)';
                        } else if (episodeData.name.toLowerCase().includes('special')) {
                            episodeType = ' (Special)';
                        } else if (episodeData.name.toLowerCase().includes('movie')) {
                            episodeType = ' (Movie)';
                        } else {
                            episodeType = ' (Special)';
                        }
                    } else {
                        episodeType = ' (Special)';
                    }
                    episodeTitle += episodeType;
                }

                return {
                    Episode: episodeData.episode_number,
                    Title: episodeTitle,
                    Duration: formatDuration(episodeData.runtime) || "00:45:00",
                    Description: episodeData.overview || "Auto-generated episode",
                    Thumbnail: episodeData.still_path ? `${TMDB_IMAGE_BASE}${episodeData.still_path}` : parentEntry.Poster,
                    Servers: allSources.map(source => ({
                        name: source.name || source.title,
                        url: source.url
                    }))
                };
            } catch (error) {
                console.error(`Error generating episode S${seasonNumber}E${episodeData.episode_number}:`, error);
                return null;
            }
        }

        // Generate additional metadata-based sources
        function generateMetadataBasedSources(tmdbId, seasonNumber, episodeNumber, seriesTitle) {
            const sources = [];
            const cleanTitle = seriesTitle.toLowerCase().replace(/[^a-z0-9]/g, '-');
            
            // Additional streaming sources (beyond the main auto-embed servers)
            const additionalSources = [
                {
                    name: 'FileLions 1080p',
                    url: `https://filelions.to/v/${cleanTitle}-s${seasonNumber}-e${episodeNumber}`
                },
                {
                    name: 'StreamLare HD',
                    url: `https://streamlare.com/e/${tmdbId}_s${seasonNumber}e${episodeNumber}`
                },
                {
                    name: 'StreamHub',
                    url: `https://streamhub.to/e/${tmdbId}-s${seasonNumber}-e${episodeNumber}`
                },
                {
                    name: 'VidLink Pro',
                    url: `https://vidlink.pro/tv/${tmdbId}/${seasonNumber}/${episodeNumber}`
                },
                {
                    name: 'Cataz.net',
                    url: `https://cataz.net/embed/tv/${tmdbId}/${seasonNumber}/${episodeNumber}`
                },
                {
                    name: 'NontonGuru',
                    url: `https://tv.nontonguru.info/embed/tv/${tmdbId}/${seasonNumber}/${episodeNumber}`
                },
                {
                    name: 'Warezcdn',
                    url: `https://embed.warezcdn.com/v/${tmdbId}-s${seasonNumber}e${episodeNumber}`
                },
                {
                    name: 'Fembed',
                    url: `https://fembed.com/v/${cleanTitle}_${seasonNumber}_${episodeNumber}`
                },
                {
                    name: 'Streamzz',
                    url: `https://streamzz.to/v/${tmdbId}s${seasonNumber}e${episodeNumber}`
                },
                {
                    name: 'Supervideo',
                    url: `https://supervideo.tv/e/${tmdbId}_s${seasonNumber}_e${episodeNumber}`
                }
            ];

            return additionalSources;
        }

        function createStatusElement() {
            const element = document.createElement('div');
            element.id = 'auto-embed-status';
            element.className = 'status info';
            element.style.display = 'none';
            
            // Find a suitable parent element to append to
            const autoEmbedSection = document.querySelector('.auto-embed-config');
            if (autoEmbedSection && autoEmbedSection.parentNode) {
                autoEmbedSection.parentNode.appendChild(element);
            }
            
            return element;
        }

        async function generateSeries(tmdbId) {
            const seriesData = await fetchTMDB(`/tv/${tmdbId}`);
            const credits = await fetchTMDB(`/tv/${tmdbId}/credits`);
            const videos = await fetchTMDB(`/tv/${tmdbId}/videos`);
            const contentRatings = await fetchTMDB(`/tv/${tmdbId}/content_ratings`);
            
            if (!seriesData) throw new Error('Failed to fetch series data');

            // Get seasons to include
            const seasonsInput = document.getElementById('series-seasons').value.trim();
            const seasonsToInclude = seasonsInput ? 
                seasonsInput.split(',').map(s => parseInt(s.trim())).filter(s => !isNaN(s)) :
                seriesData.seasons?.map(s => s.season_number).filter(s => s > 0) || [];

            // Get additional servers
            const serverInputs = document.querySelectorAll('#series-servers .server-item');
            const additionalServerTemplates = [];
            
            serverInputs.forEach(item => {
                const name = item.querySelector('.server-name').value.trim();
                const urlTemplate = item.querySelector('.server-url').value.trim();
                if (name && urlTemplate) {
                    additionalServerTemplates.push({ name, urlTemplate });
                }
            });

            const seasons = [];
            
            for (const seasonNum of seasonsToInclude) {
                const seasonData = await fetchTMDB(`/tv/${tmdbId}/season/${seasonNum}`);
                if (!seasonData) continue;

                const episodes = [];
                
                                 for (const episodeData of seasonData.episodes || []) {
                     // Auto-generate embed sources based on configuration for each episode
                     const episodeSources = generateEmbedSources(tmdbId, 'tv', seasonNum, episodeData.episode_number);

                     console.log(`Auto-generated sources for S${seasonNum}E${episodeData.episode_number}:`, episodeSources);

                    // Add additional sources
                    additionalServerTemplates.forEach(server => {
                        const url = server.urlTemplate
                            .replace('{season}', seasonNum)
                            .replace('{episode}', episodeData.episode_number);
                        
                        episodeSources.push({
                            id: nextId++,
                            type: getSourceType(url),
                            title: server.name,
                            quality: "Auto",
                            size: "Unknown",
                            kind: getSourceKind(url),
                            premium: "false",
                            external: false,
                            url: url
                        });
                    });

                    episodes.push({
                        id: nextId++,
                        episode_number: episodeData.episode_number,
                        title: episodeData.name || `Episode ${episodeData.episode_number}`,
                        description: episodeData.overview || "No description available",
                        downloadas: `${seriesData.name.toLowerCase().replace(/\s+/g, '-')}-s${seasonNum}e${episodeData.episode_number}.mp4`,
                        playas: "video",
                        duration: formatDuration(episodeData.runtime),
                        image: episodeData.still_path ? `${TMDB_IMAGE_BASE}${episodeData.still_path}` : null,
                        sources: episodeSources
                    });
                }

                seasons.push({
                    id: seasonNum,
                    title: seasonData.name || `Season ${seasonNum}`,
                    episodes: episodes
                });
            }

            const series = {
                id: nextId++,
                title: seriesData.name,
                type: "series",
                label: seriesData.genres[0]?.name || "Series",
                sublabel: `${seasons.length} Season${seasons.length !== 1 ? 's' : ''}`,
                imdb: seriesData.vote_average?.toString() || "0",
                downloadas: seriesData.name.toLowerCase().replace(/\s+/g, '-'),
                comment: true,
                playas: "video",
                description: seriesData.overview || "No description available",
                 parentalRating: getTVCertification(contentRatings),
                year: seriesData.first_air_date?.substring(0, 4) || "Unknown",
                duration: formatDuration(seriesData.episode_run_time?.[0]),
                rating: seriesData.vote_average || 0,
                image: seriesData.poster_path ? `${TMDB_IMAGE_BASE}${seriesData.poster_path}` : null,
                cover: seriesData.backdrop_path ? `${TMDB_IMAGE_BASE}${seriesData.backdrop_path}` : null,
                genres: seriesData.genres?.map(g => ({ id: g.id, title: g.name })) || [],
                sources: [], // Series don't have direct sources
                trailer: getTrailer(videos),
                actors: getActors(credits),
                subtitles: [],
                seasons: seasons,
                views: Math.floor(Math.random() * 10000) + 1000,
                created_at: new Date().toISOString().split('T')[0]
            };

            // Convert to Categories structure
            const seriesEntry = {
                Title: series.title,
                SubCategory: series.genres?.[0]?.title || "Action",
                Country: "",
                Description: series.description,
                Poster: series.image,
                Thumbnail: series.image,
                Rating: Math.round(series.rating),
                Year: parseInt(series.year),
                 parentalRating: series.parentalRating,
                Seasons: seasons.map(season => ({
                    Season: season.id,
                    SeasonPoster: series.image,
                    Episodes: season.episodes.map(episode => ({
                        Episode: episode.episode_number,
                        Title: episode.title,
                        Duration: episode.duration || "00:45:00",
                        Description: episode.description || "",
                        Thumbnail: episode.image || series.image,
                        Servers: episode.sources?.map(source => ({
                            name: source.name || source.title,
                            url: source.url
                        })) || []
                    }))
                }))
            };

            // Add to TV Series category
            const seriesCategory = currentData.Categories.find(cat => cat.MainCategory === "TV Series");
            if (seriesCategory) {
                // Add genre to subcategories if not exists
                const genre = series.genres?.[0]?.title || "Action";
                if (!seriesCategory.SubCategories.includes(genre)) {
                    seriesCategory.SubCategories.push(genre);
                }
                seriesCategory.Entries.push(seriesEntry);
                console.log(`✅ Series '${seriesEntry.Title}' added to TV Series category with ${seriesEntry.Seasons.length} seasons`);
            } else {
                console.error('❌ TV Series category not found!');
            }
            
            await saveData();
        }

        // Helper functions
        function getSourceType(url) {
            if (url.includes('youtube.com') || url.includes('youtu.be')) return 'youtube';
            if (url.includes('embed') || url.includes('iframe')) return 'embed';
            if (url.includes('.m3u8')) return 'm3u8';
            if (url.includes('.mpd')) return 'dash';
            return 'video';
        }

        function getSourceKind(url) {
            const type = getSourceType(url);
            if (type === 'youtube') return 'youtube';
            if (type === 'embed') return 'play';     // Embed sources = play only
            if (type === 'm3u8' || type === 'dash') return 'play';  // Live TV/streams = play only
            if (type === 'video') return 'both';     // Direct video files = both downloadable & playable
            return 'both';  // Default for direct links
        }

        function getTVCertification(contentRatings) {
            if (!contentRatings || !contentRatings.results) return 'N/A';
            const usRating = contentRatings.results.find(r => r.iso_3166_1 === 'US');
            if (usRating && usRating.rating) {
                return usRating.rating;
            }
            if (contentRatings.results.length > 0 && contentRatings.results[0].rating) {
                return contentRatings.results[0].rating;
            }
            return 'N/A';
        }

        function getMovieCertification(releaseDates) {
            if (!releaseDates || !releaseDates.results) return 'N/A';
            const usRelease = releaseDates.results.find(r => r.iso_3166_1 === 'US');
            if (usRelease && usRelease.release_dates) {
                const theatrical = usRelease.release_dates.find(rd => rd.type === 3 || rd.type === 4);
                if (theatrical && theatrical.certification) {
                    return theatrical.certification;
                }
            }
            for (const result of releaseDates.results) {
                if (result.release_dates) {
                    for (const rd of result.release_dates) {
                        if (rd.certification) {
                            return rd.certification;
                        }
                    }
                }
            }
            return 'N/A';
        }

        function getRatingClassification(adult) {
            return adult ? 'R' : 'PG-13';
        }

        function formatDuration(minutes) {
            if (!minutes) return "Unknown";
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours}:${mins.toString().padStart(2, '0')}` : `${mins}:00`;
        }

        function getTrailer(videos) {
            if (!videos?.results) return null;
            
            const trailer = videos.results.find(v => 
                v.type === 'Trailer' && v.site === 'YouTube'
            ) || videos.results[0];
            
            if (trailer && trailer.site === 'YouTube') {
                return {
                    id: nextId++,
                    type: "youtube",
                    title: `${trailer.name} Trailer`,
                    url: `https://www.youtube.com/watch?v=${trailer.key}`
                };
            }
            
            return null;
        }

        function getActors(credits) {
            if (!credits?.cast) return [];
            
            return credits.cast.slice(0, 10).map(actor => ({
                id: nextId++,
                name: actor.name,
                type: actor.gender === 1 ? "actress" : "actor",
                role: actor.character || "Unknown Role",
                image: actor.profile_path ? `${TMDB_IMAGE_BASE}${actor.profile_path}` : null,
                bio: "Actor information from TMDB"
            }));
        }
                 async function getSubtitles(tmdbId, type) {
             // This would integrate with a subtitle API
             // For now, return empty array
             return [];
         }

         // Helper functions for generating root-level arrays
         function generateActorsFromContent() {
             const actorMap = new Map();
             
             // Process both slides and featuredMovies to ensure all actors are captured
             const allContent = [
                 ...(currentData.home.slides || []).map(slide => slide.poster || slide),
                 ...(currentData.home.featuredMovies || [])
             ];
             
             allContent.forEach(content => {
                 if (content && content.actors && Array.isArray(content.actors)) {
                     content.actors.forEach(actor => {
                         if (actor.name && !actorMap.has(actor.name)) {
                             actorMap.set(actor.name, {
                                 id: actor.id || nextId++,
                                 name: actor.name,
                                 type: actor.type || 'actor',
                                 role: actor.role || 'Actor',
                                 image: actor.image || '',
                                 born: actor.born || '',
                                 height: actor.height || '',
                                 bio: actor.bio || 'Actor information from TMDB',
                                 movies: []
                             });
                         }
                         
                         // Add movie to actor's filmography
                         if (actorMap.has(actor.name)) {
                             const actorData = actorMap.get(actor.name);
                             if (!actorData.movies.some(m => m.id === content.id)) {
                                 actorData.movies.push({
                                     id: content.id,
                                     title: content.title,
                                     image: content.image,
                                     year: content.year
                                 });
                             }
                         }
                     });
                 }
             });
             
             return Array.from(actorMap.values());
         }

         function generateGenresFromContent() {
             const genreMap = new Map();
             
             // Process both slides and featuredMovies to ensure all genres are captured
             const allContent = [
                 ...(currentData.home.slides || []).map(slide => slide.poster || slide),
                 ...(currentData.home.featuredMovies || [])
             ];
             
             allContent.forEach(content => {
                 if (content && content.genres && Array.isArray(content.genres)) {
                     content.genres.forEach(genre => {
                         const genreTitle = genre.title || genre.name;
                         if (genreTitle && !genreMap.has(genreTitle)) {
                             genreMap.set(genreTitle, {
                                 id: genre.id || nextId++,
                                 title: genreTitle,
                                 posters: []
                             });
                         }
                         
                         // Add content to genre's posters
                         if (genreMap.has(genreTitle)) {
                             const genreData = genreMap.get(genreTitle);
                             if (!genreData.posters.some(p => p.id === content.id)) {
                                 genreData.posters.push({
                                     id: content.id,
                                     title: content.title,
                                     type: content.type,
                                     label: content.label,
                                     sublabel: content.sublabel,
                                     imdb: content.imdb,
                                     downloadas: content.downloadas,
                                     comment: content.comment,
                                     playas: content.playas,
                                     description: content.description,
                                     classification: content.classification,
                                     year: content.year,
                                     duration: content.duration,
                                     rating: content.rating,
                                     image: content.image,
                                     cover: content.cover,
                                     genres: content.genres,
                                     actors: content.actors,
                                     views: content.views,
                                     created_at: content.created_at,
                                     sources: content.sources,
                                     trailer: content.trailer,
                                     subtitles: content.subtitles,
                                     ...(content.type === 'series' && { seasons: content.seasons })
                                 });
                             }
                         }
                     });
                 }
             });
             
             return Array.from(genreMap.values());
         }

        // Manual input functions
        function toggleManualFields() {
            const type = document.getElementById('manual-type').value;
            const seriesFields = document.getElementById('series-fields');
            
            if (type === 'series') {
                seriesFields.style.display = 'block';
            } else {
                seriesFields.style.display = 'none';
            }
        }

        function addServer(containerId) {
            const container = document.getElementById(containerId);
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';
            
            // Determine placeholder based on container
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

        function removeServer(button) {
            button.parentElement.remove();
        }

        // Paste functionality
        async function pasteFromClipboard(button) {
            try {
                const text = await navigator.clipboard.readText();
                const serverItem = button.closest('.server-item');
                const urlInput = serverItem.querySelector('input[type="url"], .server-url, .source-url');
                
                if (urlInput) {
                    urlInput.value = text;
                    urlInput.focus();
                    showStatus('success', 'URL pasted successfully!');
                }
            } catch (error) {
                showStatus('error', 'Failed to paste from clipboard. Please paste manually.');
                console.error('Paste error:', error);
            }
        }

        async function addManualContent() {
            const type = document.getElementById('manual-type').value;
            const title = document.getElementById('manual-title').value.trim();
            const subcategory = document.getElementById('manual-subcategory').value;
            const country = document.getElementById('manual-country').value.trim();
            const parentalRating = document.getElementById('manual-parental-rating').value.trim();
            
            if (!title) {
                showStatus('warning', 'Please enter a title');
                return;
            }

            // Get servers
            const sourceInputs = document.querySelectorAll('#manual-sources .server-item');
            const servers = [];
            
            sourceInputs.forEach(item => {
                const name = item.querySelector('.source-name').value.trim();
                const url = item.querySelector('.source-url').value.trim();
                
                if (name && url) {
                    servers.push({
                        name: name,
                        url: url
                    });
                }
            });

            if (servers.length === 0) {
                showStatus('warning', 'Please add at least one server');
                return;
            }

            // Create entry object matching JSON structure
            const entry = {
                Title: title,
                SubCategory: subcategory,
                Country: country,
                Description: document.getElementById('manual-description').value || 'No description available',
                Poster: document.getElementById('manual-image').value || '',
                Thumbnail: document.getElementById('manual-image').value || '',
                Rating: parseInt(document.getElementById('manual-rating').value) || 0,
                parentalRating: parentalRating,
                Servers: servers
            };

            // Add additional fields based on type
            if (type === 'movie' || type === 'series') {
                entry.Year = parseInt(document.getElementById('manual-year').value) || new Date().getFullYear();
            }
            
            if (type === 'movie') {
                entry.Duration = "2:00:00"; // Default duration
            }
            
            if (type === 'series') {
                entry.Seasons = []; // Will be populated when seasons are added
            }

            // Find the appropriate category and add the entry
            let mainCategory = '';
            if (type === 'movie') mainCategory = 'Movies';
            else if (type === 'series') mainCategory = 'TV Series';
            else if (type === 'live') mainCategory = 'Live TV';

            const category = currentData.Categories.find(cat => cat.MainCategory === mainCategory);
            if (category) {
                // Add subcategory if it doesn't exist
                if (!category.SubCategories.includes(subcategory)) {
                    category.SubCategories.push(subcategory);
                }
                
                category.Entries.push(entry);
            } else {
                // Create new category if it doesn't exist
                currentData.Categories.push({
                    MainCategory: mainCategory,
                    SubCategories: [subcategory],
                    Entries: [entry]
                });
            }

            await saveData();
            updateDataStats();
            updatePreview();
            showStatus('success', `${title} added to ${mainCategory} successfully!`);
            
            // Clear form
            document.getElementById('manual-title').value = '';
            document.getElementById('manual-description').value = '';
            document.getElementById('manual-image').value = '';
            document.getElementById('manual-year').value = '';
            document.getElementById('manual-rating').value = '';
            document.getElementById('manual-country').value = '';
            document.getElementById('manual-parental-rating').value = '';
            
            // Reset sources to one empty item
            document.getElementById('manual-sources').innerHTML = `
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
            `;
        }

        // Bulk operations
        async function bulkGenerate() {
            const type = document.getElementById('bulk-type').value;
            const year = document.getElementById('bulk-year').value;
            const pages = parseInt(document.getElementById('bulk-pages').value);
            const skipDuplicates = document.getElementById('bulk-skip-duplicates').checked;
            
            showLoading('bulk-loading', true);
            
            let generated = 0;
            let skipped = 0;
            
            for (let page = 1; page <= pages; page++) {
                const results = await fetchTMDB(`/discover/${type}`, {
                    primary_release_year: type === 'movie' ? year : undefined,
                    first_air_date_year: type === 'tv' ? year : undefined,
                    page: page,
                    sort_by: 'popularity.desc'
                });
                
                if (!results?.results) break;
                
                for (const item of results.results) {
                    // Check for duplicates
                    if (skipDuplicates && isDuplicate(item.id, type)) {
                        skipped++;
                        continue;
                    }
                    
                    try {
                        if (type === 'movie') {
                            await generateMovie(item.id);
                        } else {
                            await generateSeries(item.id);
                        }
                        generated++;
                    } catch (error) {
                        console.error(`Error generating ${type} ${item.id}:`, error);
                    }
                }
                
                // Update progress
                const progress = (page / pages) * 100;
                document.getElementById('bulk-progress').style.width = `${progress}%`;
                document.getElementById('bulk-status').innerHTML = `
                    <div class="status info">
                        Page ${page}/${pages} - Generated: ${generated}, Skipped: ${skipped}
                    </div>
                `;
                
                // Small delay to prevent API rate limiting
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            
                         showLoading('bulk-loading', false);
             updateDataStats();
             updatePreview();
             showStatus('success', `Bulk generation complete! Generated: ${generated} items with multi-server sources (VidSrc + VidJoy) matching existing app structure, Skipped: ${skipped}`);
        }

        async function generateByGenre() {
            const genreId = document.getElementById('genre-select').value;
            const count = parseInt(document.getElementById('genre-count').value);
            const contentType = document.getElementById('content-type-select').value;
            const year = document.getElementById('year-select').value;
            
            console.log('🎬 Starting genre generation:', { genreId, count, contentType, year });
            console.log('📊 Current data structure:', currentData);
            
            // Show loading and progress
            showLoading('genre-loading', true);
            document.getElementById('genre-progress').style.display = 'block';
            document.getElementById('genre-progress-fill').style.width = '0%';
            document.getElementById('genre-progress-text').textContent = 'Fetching content...';
            
            try {
                let totalGenerated = 0;
                const contentTypes = contentType === 'both' ? ['movie', 'tv'] : [contentType];
                
                for (let i = 0; i < contentTypes.length; i++) {
                    const type = contentTypes[i];
                    const endpoint = type === 'movie' ? '/discover/movie' : '/discover/tv';
                    
                    // Calculate how many items we need for this content type
                    const itemsNeeded = contentType === 'both' ? Math.ceil(count / 2) : count;
                    console.log(`🎯 Need ${itemsNeeded} ${type} items`);
                    
                    // Fetch multiple pages if needed to get enough results
                    let allResults = [];
                    let currentPage = 1;
                    const maxPages = Math.ceil(itemsNeeded / 20); // TMDB returns 20 items per page
                    
                    while (allResults.length < itemsNeeded && currentPage <= maxPages && currentPage <= 500) {
                        // Build query parameters
                        const params = {
                            with_genres: genreId,
                            sort_by: 'popularity.desc',
                            page: currentPage
                        };
                        
                        // Add year filter if specified
                        if (year) {
                            if (type === 'movie') {
                                params.primary_release_year = year;
                            } else {
                                params.first_air_date_year = year;
                            }
                        }
                        
                        console.log(`🔍 Fetching ${type} data from:`, endpoint, params, `(page ${currentPage})`);
                        const results = await fetchTMDB(endpoint, params);
                        console.log(`📥 Received ${type} results from page ${currentPage}:`, results?.results?.length || 0, 'items');
                        
                        if (!results?.results || results.results.length === 0) {
                            console.log(`📄 No more results on page ${currentPage}, stopping pagination`);
                            break;
                        }
                        
                        allResults = [...allResults, ...results.results];
                        currentPage++;
                        
                        // Small delay between page requests to be nice to the API
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                    
                    if (allResults.length === 0) {
                        console.error(`❌ Failed to fetch ${type} data`);
                        showStatus('error', `Failed to fetch ${type} data from TMDB`);
                        continue;
                    }
                    
                    // Take only the number of items we need
                    const itemsToProcess = allResults.slice(0, itemsNeeded);
                    console.log(`🎯 Processing ${itemsToProcess.length} ${type} items:`, itemsToProcess.map(item => item.title || item.name));
                    let generated = 0;
                    
                    for (let j = 0; j < itemsToProcess.length; j++) {
                        const item = itemsToProcess[j];
                        const totalItemsToProcess = contentTypes.reduce((acc, _, idx) => {
                            return acc + (contentType === 'both' ? Math.ceil(count / 2) : count);
                        }, 0);
                        const currentItemIndex = i * (contentType === 'both' ? Math.ceil(count / 2) : count) + j + 1;
                        const progressPercent = (currentItemIndex / totalItemsToProcess) * 100;
                        
                        // Update progress
                        document.getElementById('genre-progress-fill').style.width = `${progressPercent}%`;
                        document.getElementById('genre-progress-text').textContent = 
                            `Processing ${type === 'movie' ? 'movies' : 'TV series'}: ${j + 1}/${itemsToProcess.length}`;
                        
                        try {
                            // Check for duplicate by title instead of ID
                            const itemTitle = item.title || item.name;
                            if (!isDuplicateByTitle(itemTitle, type)) {
                                if (type === 'movie') {
                                    await generateMovie(item.id);
                                } else {
                                    await generateSeries(item.id);
                                }
                                generated++;
                                totalGenerated++;
                                console.log(`✅ Generated ${type}: ${itemTitle}`);
                            } else {
                                console.log(`⚠️ Skipped duplicate ${type}: ${itemTitle}`);
                            }
                        } catch (error) {
                            console.error(`❌ Error generating ${type} ${item.id}:`, error);
                        }
                        
                        // Small delay to prevent rate limiting
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                }
                
                // Complete progress
                document.getElementById('genre-progress-fill').style.width = '100%';
                document.getElementById('genre-progress-text').textContent = 'Complete!';
                
                console.log('🎉 Genre generation complete! Total generated:', totalGenerated);
                console.log('📊 Final data structure:', currentData);
                console.log('🎬 Movies count:', currentData.Categories.find(c => c.MainCategory === 'Movies')?.Entries.length || 0);
                console.log('📺 TV Series count:', currentData.Categories.find(c => c.MainCategory === 'TV Series')?.Entries.length || 0);
                
                updateDataStats();
                updatePreview();
                
                const typeText = contentType === 'both' ? 'movies and TV series' : 
                                contentType === 'movie' ? 'movies' : 'TV series';
                const yearText = year ? ` from ${year}` : '';
                showStatus('success', `Generated ${totalGenerated} ${typeText} from selected genre${yearText}!`);
                
            } catch (error) {
                console.error('Genre generation failed:', error);
                showStatus('error', 'Genre generation failed: ' + error.message);
            } finally {
                // Hide loading and progress after a delay
                setTimeout(() => {
                    showLoading('genre-loading', false);
                    document.getElementById('genre-progress').style.display = 'none';
                }, 2000);
            }
        }

        function isDuplicate(tmdbId, type) {
            const categoryName = type === 'movie' ? 'Movies' : 'TV Series';
            const category = currentData.Categories.find(cat => cat.MainCategory === categoryName);
            if (!category) return false;
            
            // Check for duplicate by TMDB ID in the entry's metadata or description
            // Since we don't store TMDB ID directly, we'll check if the title already exists
            // after fetching the actual title from TMDB
            return false; // For now, let the generation function handle duplicates after fetching title
        }

        function isDuplicateByTitle(title, type) {
            const categoryName = type === 'movie' ? 'Movies' : 'TV Series';
            const category = currentData.Categories.find(cat => cat.MainCategory === categoryName);
            if (!category) return false;
            
            return category.Entries.some(entry => 
                entry.Title.toLowerCase() === title.toLowerCase()
            );
        }

        async function testGeneration() {
            console.log('🧪 Starting test generation...');
            console.log('📊 Current data before test:', currentData);
            
            try {
                // Test generating a popular movie (The Dark Knight - TMDB ID: 155)
                showStatus('info', 'Testing movie generation...');
                await generateMovie(155);
                
                console.log('🎬 After movie generation:', currentData);
                console.log('Movies count:', currentData.Categories.find(c => c.MainCategory === 'Movies')?.Entries.length || 0);
                
                updateDataStats();
                updatePreview();
                showStatus('success', 'Test generation completed! Check console for details.');
                
            } catch (error) {
                console.error('❌ Test generation failed:', error);
                showStatus('error', 'Test generation failed: ' + error.message);
            }
        }

        // Data management functions
        let importCancelled = false;
        let importStartTime = 0;
        
        function importData() {
            const fileInput = document.getElementById('import-file');
            const file = fileInput.files[0];
            
            if (!file) {
                showStatus('warning', 'Please select a file to import');
                return;
            }
            
            // Reset cancellation flag
            importCancelled = false;
            importStartTime = Date.now();
            
            // Show progress section and loading
            document.getElementById('import-progress-section').style.display = 'block';
            document.getElementById('cancel-import-btn').style.display = 'inline-block';
            showLoading('import-loading', true);
            
            // Update initial progress
            updateImportProgress(0, 0, 'Reading file...', '-');
            
            const reader = new FileReader();
            
            reader.onprogress = function(e) {
                if (e.lengthComputable && !importCancelled) {
                    const percentLoaded = Math.round((e.loaded / e.total) * 30); // File reading is 30% of total
                    updateImportProgress(percentLoaded, 0, `Reading file... ${Math.round(e.loaded / 1024 / 1024 * 10) / 10}MB / ${Math.round(e.total / 1024 / 1024 * 10) / 10}MB`, 'Loading file');
                }
            };
            
            reader.onload = function(e) {
                if (importCancelled) return;
                
                try {
                    updateImportProgress(30, 0, 'Parsing JSON data...', 'Validating');
                    
                    const jsonText = e.target.result;
                    console.log('📄 File loaded, size:', (jsonText.length / 1024 / 1024).toFixed(1), 'MB');
                    
                    const importedData = JSON.parse(jsonText);
                    console.log('✅ JSON parsed successfully');
                    
                    // Validate data structure for Categories format
                    if (!importedData.Categories || !Array.isArray(importedData.Categories)) {
                        console.error('❌ Invalid data structure:', {
                            hasCategories: !!importedData.Categories,
                            categoriesType: typeof importedData.Categories,
                            isArray: Array.isArray(importedData.Categories),
                            keys: Object.keys(importedData).slice(0, 10)
                        });
                        throw new Error(`Invalid data format - expected Categories array. Found: ${typeof importedData.Categories}`);
                    }
                    
                    updateImportProgress(40, 0, 'Validating data structure...', 'Validating');
                    console.log('📊 Found', importedData.Categories.length, 'categories');
                    
                    // Count total entries for progress tracking
                    let totalEntries = 0;
                    importedData.Categories.forEach((category, index) => {
                        if (!category.MainCategory || !Array.isArray(category.SubCategories) || !Array.isArray(category.Entries)) {
                            console.error(`❌ Invalid category structure at index ${index}:`, {
                                mainCategory: category.MainCategory,
                                hasSubCategories: !!category.SubCategories,
                                subCategoriesType: typeof category.SubCategories,
                                hasEntries: !!category.Entries,
                                entriesType: typeof category.Entries
                            });
                            throw new Error(`Invalid category structure at index ${index}: ${category.MainCategory || 'Unknown'}`);
                        }
                        totalEntries += category.Entries.length;
                        console.log(`📂 Category "${category.MainCategory}": ${category.Entries.length} entries`);
                    });
                    
                    updateImportProgress(50, 0, `Found ${totalEntries.toLocaleString()} entries in ${importedData.Categories.length} categories`, 'Processing');
                    console.log('🎯 Total entries to process:', totalEntries);
                    
                    // Process data with progress tracking
                    processImportData(importedData, totalEntries);
                    
                } catch (error) {
                    console.error('💥 Import error:', error);
                    hideImportProgress();
                    
                    let errorMessage = error.message;
                    if (error instanceof SyntaxError) {
                        errorMessage = `JSON Syntax Error: ${error.message}. Check the console for details.`;
                    }
                    
                    showStatus('error', `Import failed: ${errorMessage}`);
                    showLoading('import-loading', false);
                }
            };
            
            reader.onerror = function() {
                hideImportProgress();
                showStatus('error', 'Failed to read file');
                showLoading('import-loading', false);
            };
            
            reader.readAsText(file);
        }
        
        async function processImportData(importedData, totalEntries) {
            let processedEntries = 0;
            const batchSize = 100; // Process in batches to avoid blocking UI
            const startTime = Date.now();
            
            // Update total count
            document.getElementById('import-total').textContent = totalEntries.toLocaleString();
            
            try {
                for (let catIndex = 0; catIndex < importedData.Categories.length; catIndex++) {
                    if (importCancelled) {
                        showStatus('warning', 'Import cancelled by user');
                        return;
                    }
                    
                    const category = importedData.Categories[catIndex];
                    updateImportProgress(
                        50 + Math.round((processedEntries / totalEntries) * 50),
                        processedEntries,
                        `Processing ${category.MainCategory}...`,
                        category.MainCategory
                    );
                    
                    // Process entries in batches
                    for (let i = 0; i < category.Entries.length; i += batchSize) {
                        if (importCancelled) {
                            showStatus('warning', 'Import cancelled by user');
                            return;
                        }
                        
                        const batch = category.Entries.slice(i, Math.min(i + batchSize, category.Entries.length));
                        
                        // Process batch (simulate processing time for large datasets)
                        await new Promise(resolve => {
                            setTimeout(() => {
                                processedEntries += batch.length;
                                
                                // Update progress
                                const progressPercent = 50 + Math.round((processedEntries / totalEntries) * 50);
                                const elapsed = Date.now() - startTime;
                                const speed = processedEntries / (elapsed / 1000);
                                const remaining = totalEntries - processedEntries;
                                const eta = remaining > 0 ? Math.round(remaining / speed) : 0;
                                
                                updateImportProgress(
                                    progressPercent,
                                    processedEntries,
                                    `Processing ${category.MainCategory}... (${processedEntries.toLocaleString()}/${totalEntries.toLocaleString()})`,
                                    category.MainCategory,
                                    speed,
                                    eta
                                );
                                
                                resolve();
                            }, 1); // Small delay to keep UI responsive
                        });
                    }
                }
                
                                 if (!importCancelled) {
                     try {
                         // Finalize import
                         updateImportProgress(95, processedEntries, 'Finalizing import...', 'Saving');
                         console.log('💾 Starting finalization process...');
                         
                         // Step 1: Assign data
                         console.log('📝 Assigning imported data...');
                         currentData = importedData;
                         console.log('✅ Data assigned successfully');
                         
                         // Step 2: Save to localStorage (this might fail with large data)
                         updateImportProgress(96, processedEntries, 'Saving to localStorage...', 'Saving');
                         console.log('💾 Saving to localStorage...');
                         
                         try {
                             saveData();
                             console.log('✅ Data saved to localStorage successfully');
                         } catch (saveError) {
                             console.error('⚠️ localStorage save failed:', saveError);
                             console.log('📊 Data size:', JSON.stringify(currentData).length, 'characters');
                             
                             // Try to save without localStorage for now
                             console.log('⚡ Continuing without localStorage save...');
                             showStatus('warning', 'Data imported but could not save to localStorage (data too large). Data is active in current session.');
                         }
                         
                         updateImportProgress(98, processedEntries, 'Updating interface...', 'UI Update');
                         
                         // Step 3: Update UI components
                         console.log('🔄 Updating UI components...');
                         
                         try {
                             console.log('📊 Updating data stats...');
                             updateDataStats();
                             console.log('✅ Data stats updated');
                         } catch (statsError) {
                             console.error('⚠️ Data stats update failed:', statsError);
                         }
                         
                         try {
                             console.log('👁️ Updating preview...');
                             updatePreview();
                             console.log('✅ Preview updated');
                         } catch (previewError) {
                             console.error('⚠️ Preview update failed:', previewError);
                         }
                         
                                                 try {
                            console.log('📋 Refreshing content checkboxes...');
                            refreshContentCheckboxes();
                            console.log('✅ Content checkboxes refreshed');
                        } catch (dropdownError) {
                            console.error('⚠️ Checkbox refresh failed:', dropdownError);
                        }
                         
                         updateImportProgress(100, processedEntries, 'Import completed successfully!', 'Complete');
                         console.log('✨ Import process completed successfully!');
                         
                         // Hide progress after a short delay
                         setTimeout(() => {
                             hideImportProgress();
                             showStatus('success', `Successfully imported ${processedEntries.toLocaleString()} entries from ${importedData.Categories.length} categories!`);
                         }, 2000);
                         
                     } catch (finalizationError) {
                         console.error('💥 Finalization error:', finalizationError);
                         hideImportProgress();
                         showStatus('error', `Import data processed but finalization failed: ${finalizationError.message}. Check console for details.`);
                     }
                 }
                
            } catch (error) {
                hideImportProgress();
                showStatus('error', `Import processing failed: ${error.message}`);
            }
            
            showLoading('import-loading', false);
        }
        
        function updateImportProgress(percent, processed, message, category, speed = 0, eta = 0) {
            // Update progress bar
            document.getElementById('import-progress-fill').style.width = `${percent}%`;
            
            // Update progress text
            document.getElementById('import-progress-text').textContent = `${percent}% - ${message}`;
            
            // Update stats
            document.getElementById('import-processed').textContent = processed.toLocaleString();
            document.getElementById('import-current-category').textContent = category;
            
            if (speed > 0) {
                document.getElementById('import-speed').textContent = Math.round(speed);
                
                if (eta > 0) {
                    const minutes = Math.floor(eta / 60);
                    const seconds = eta % 60;
                    document.getElementById('import-eta').textContent = 
                        minutes > 0 ? `${minutes}m ${seconds}s` : `${seconds}s`;
                } else {
                    document.getElementById('import-eta').textContent = 'Almost done!';
                }
            }
        }
        function cancelImport() {
            importCancelled = true;
            hideImportProgress();
            showLoading('import-loading', false);
            showStatus('warning', 'Import cancelled');
        }
        function hideImportProgress() {
            document.getElementById('import-progress-section').style.display = 'none';
            document.getElementById('cancel-import-btn').style.display = 'none';
        }
        
        // Chunked Import - Alternative approach for large files
        function importDataChunked() {
            const fileInput = document.getElementById('import-file');
            const file = fileInput.files[0];
            
            if (!file) {
                showStatus('warning', 'Please select a file to import');
                return;
            }
            
            // Reset cancellation flag
            importCancelled = false;
            importStartTime = Date.now();
            
            // Show progress section and loading
            document.getElementById('import-progress-section').style.display = 'block';
            document.getElementById('cancel-import-btn').style.display = 'inline-block';
            showLoading('import-chunked-loading', true);
            
            // Update initial progress
            updateImportProgress(0, 0, 'Reading file for chunked processing...', 'Loading');
            
            const reader = new FileReader();
            
            reader.onprogress = function(e) {
                if (e.lengthComputable && !importCancelled) {
                    const percentLoaded = Math.round((e.loaded / e.total) * 20); // File reading is 20% of total
                    updateImportProgress(percentLoaded, 0, `Reading file... ${Math.round(e.loaded / 1024 / 1024 * 10) / 10}MB / ${Math.round(e.total / 1024 / 1024 * 10) / 10}MB`, 'Loading file');
                }
            };
            
            reader.onload = function(e) {
                if (importCancelled) return;
                
                try {
                    updateImportProgress(20, 0, 'Parsing JSON data...', 'Parsing');
                    
                    const jsonText = e.target.result;
                    console.log('📄 File loaded for chunked processing, size:', (jsonText.length / 1024 / 1024).toFixed(1), 'MB');
                    
                    const importedData = JSON.parse(jsonText);
                    console.log('✅ JSON parsed successfully for chunked import');
                    
                    // Validate data structure
                    if (!importedData.Categories || !Array.isArray(importedData.Categories)) {
                        throw new Error(`Invalid data format - expected Categories array. Found: ${typeof importedData.Categories}`);
                    }
                    
                    updateImportProgress(30, 0, 'Preparing chunked processing...', 'Preparing');
                    
                    // Process with chunked approach
                    processImportDataChunked(importedData);
                    
                } catch (error) {
                    console.error('💥 Chunked import error:', error);
                    hideImportProgress();
                    showStatus('error', `Chunked import failed: ${error.message}`);
                    showLoading('import-chunked-loading', false);
                }
            };
            
            reader.onerror = function() {
                hideImportProgress();
                showStatus('error', 'Failed to read file');
                showLoading('import-chunked-loading', false);
            };
            
            reader.readAsText(file);
        }
        
        async function processImportDataChunked(importedData) {
            console.log('🔄 Starting chunked processing...');
            
            try {
                // Count total entries
                let totalEntries = 0;
                importedData.Categories.forEach(category => {
                    totalEntries += category.Entries.length;
                });
                
                console.log(`📊 Total entries to process: ${totalEntries.toLocaleString()}`);
                document.getElementById('import-total').textContent = totalEntries.toLocaleString();
                
                updateImportProgress(35, 0, `Processing ${totalEntries.toLocaleString()} entries in chunks...`, 'Processing');
                
                // Initialize empty structure
                const newData = {
                    Categories: importedData.Categories.map(cat => ({
                        MainCategory: cat.MainCategory,
                        SubCategories: [...cat.SubCategories],
                        Entries: []
                    }))
                };
                
                let processedEntries = 0;
                const chunkSize = 50; // Much smaller chunks to prevent crashes
                const startTime = Date.now();
                
                // Process each category
                for (let catIndex = 0; catIndex < importedData.Categories.length; catIndex++) {
                    if (importCancelled) {
                        showStatus('warning', 'Chunked import cancelled by user');
                        return;
                    }
                    
                    const category = importedData.Categories[catIndex];
                    const targetCategory = newData.Categories[catIndex];
                    
                    console.log(`📂 Processing category: ${category.MainCategory} (${category.Entries.length} entries)`);
                    
                    // Process entries in very small chunks
                    for (let i = 0; i < category.Entries.length; i += chunkSize) {
                        if (importCancelled) {
                            showStatus('warning', 'Chunked import cancelled by user');
                            return;
                        }
                        
                        const chunk = category.Entries.slice(i, Math.min(i + chunkSize, category.Entries.length));
                        
                        // Process chunk with longer delay to prevent crashes
                        await new Promise(resolve => {
                            setTimeout(() => {
                                // Add entries to target category
                                targetCategory.Entries.push(...chunk);
                                processedEntries += chunk.length;
                                
                                // Update progress
                                const progressPercent = 35 + Math.round((processedEntries / totalEntries) * 60); // 35-95%
                                const elapsed = Date.now() - startTime;
                                const speed = processedEntries / (elapsed / 1000);
                                const remaining = totalEntries - processedEntries;
                                const eta = remaining > 0 ? Math.round(remaining / speed) : 0;
                                
                                updateImportProgress(
                                    progressPercent,
                                    processedEntries,
                                    `Processing ${category.MainCategory}... (Chunk ${Math.ceil((i + chunkSize) / chunkSize)}/${Math.ceil(category.Entries.length / chunkSize)})`,
                                    category.MainCategory,
                                    speed,
                                    eta
                                );
                                
                                console.log(`✅ Processed chunk: ${processedEntries}/${totalEntries} entries`);
                                resolve();
                            }, 10); // Longer delay to prevent crashes
                        });
                        
                        // Additional safety: Force garbage collection hint
                        if (processedEntries % 200 === 0) {
                            await new Promise(resolve => setTimeout(resolve, 50));
                        }
                    }
                }
                
                if (!importCancelled) {
                    console.log('🎯 All entries processed, finalizing...');
                    await finalizeChunkedImport(newData, processedEntries);
                }
                
            } catch (error) {
                console.error('💥 Chunked processing error:', error);
                hideImportProgress();
                showStatus('error', `Chunked processing failed: ${error.message}`);
            }
            
            showLoading('import-chunked-loading', false);
        }
        
        async function finalizeChunkedImport(newData, processedEntries) {
            try {
                updateImportProgress(95, processedEntries, 'Finalizing chunked import...', 'Finalizing');
                console.log('💾 Starting chunked import finalization...');
                
                // Assign data
                currentData = newData;
                console.log('✅ Data assigned successfully');
                
                // Try to save (might fail due to size)
                updateImportProgress(96, processedEntries, 'Attempting to save...', 'Saving');
                try {
                    saveData();
                    console.log('✅ Data saved to localStorage');
                } catch (saveError) {
                    console.warn('⚠️ Could not save to localStorage:', saveError.message);
                    showStatus('warning', 'Data imported successfully but too large for browser storage. Will work in current session only.');
                }
                
                // Update UI components safely
                updateImportProgress(98, processedEntries, 'Updating interface...', 'UI Update');
                
                await new Promise(resolve => {
                    setTimeout(() => {
                        try {
                            updateDataStats();
                            console.log('✅ Data stats updated');
                        } catch (e) {
                            console.warn('⚠️ Data stats update failed:', e);
                        }
                        resolve();
                    }, 100);
                });
                
                await new Promise(resolve => {
                    setTimeout(() => {
                        try {
                            // Skip preview update for large datasets to prevent crashes
                            if (processedEntries < 5000) {
                                updatePreview();
                                console.log('✅ Preview updated');
                            } else {
                                console.log('⚠️ Skipping preview update (dataset too large)');
                            }
                        } catch (e) {
                            console.warn('⚠️ Preview update failed:', e);
                        }
                        resolve();
                    }, 100);
                });
                
                await new Promise(resolve => {
                    setTimeout(() => {
                        try {
                            refreshContentCheckboxes();
                            console.log('✅ Content checkboxes refreshed');
                        } catch (e) {
                            console.warn('⚠️ Checkbox refresh failed:', e);
                        }
                        resolve();
                    }, 100);
                });
                
                updateImportProgress(100, processedEntries, 'Chunked import completed!', 'Complete');
                console.log('🎉 Chunked import completed successfully!');
                
                setTimeout(() => {
                    hideImportProgress();
                    showStatus('success', `🚀 Successfully imported ${processedEntries.toLocaleString()} entries using chunked processing! Data is ready to use.`);
                }, 2000);
                
            } catch (error) {
                console.error('💥 Chunked finalization error:', error);
                hideImportProgress();
                showStatus('error', `Chunked import processed data but finalization failed: ${error.message}`);
            }
        }

                                 function exportData() {
            try {
                // Export the Categories structure directly
                const dataStr = JSON.stringify(currentData, null, 2);
                const dataSizeMB = (dataStr.length / 1024 / 1024).toFixed(2);
                
                console.log(`📤 Exporting ${dataSizeMB}MB of data`);
                
                // For large data, use chunked download approach
                if (dataStr.length > 50 * 1024 * 1024) { // > 50MB
                    showStatus('info', `Preparing large export (${dataSizeMB}MB)...`);
                    exportLargeData(dataStr);
                    return;
                }
                
                // Standard export for smaller data
                const dataBlob = new Blob([dataStr], { type: 'application/json' });
                
                // Check if blob was created successfully
                if (!dataBlob || dataBlob.size === 0) {
                    throw new Error('Failed to create export file blob');
                }
                
                const link = document.createElement('a');
                const url = URL.createObjectURL(dataBlob);
                
                if (!url) {
                    throw new Error('Failed to create download URL');
                }
                
                link.href = url;
                link.download = `playlist-${new Date().toISOString().split('T')[0]}.json`;
                
                // Add link to document temporarily to ensure it works
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Clean up the object URL after download
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                }, 1000);
                
                showStatus('success', `Data exported successfully! File size: ${dataSizeMB}MB`);
                
            } catch (error) {
                console.error('Export error:', error);
                showStatus('error', `Export failed: ${error.message}`);
                
                // Fallback: try to copy to clipboard for large data
                if (error.message.includes('large') || error.message.includes('blob')) {
                    try {
                        const dataStr = JSON.stringify(currentData, null, 2);
                        navigator.clipboard.writeText(dataStr).then(() => {
                            showStatus('warning', 'Export failed, but data copied to clipboard. Paste into a text file and save as .json');
                        }).catch(() => {
                            showStatus('error', 'Export failed and clipboard unavailable. Try using GitHub upload instead.');
                        });
                    } catch (clipboardError) {
                        showStatus('error', 'Export failed. Try using GitHub upload for large datasets.');
                    }
                }
            }
        }
        
        // Handle large data exports with chunked approach
        function exportLargeData(dataStr) {
            try {
                // Split data into smaller chunks for processing
                const chunkSize = 10 * 1024 * 1024; // 10MB chunks
                const chunks = [];
                
                for (let i = 0; i < dataStr.length; i += chunkSize) {
                    chunks.push(dataStr.slice(i, i + chunkSize));
                }
                
                showStatus('info', `Processing ${chunks.length} chunks...`);
                
                // Create blob from chunks
                const dataBlob = new Blob(chunks, { type: 'application/json' });
                
                const link = document.createElement('a');
                const url = URL.createObjectURL(dataBlob);
                
                link.href = url;
                link.download = `playlist-large-${new Date().toISOString().split('T')[0]}.json`;
                
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                setTimeout(() => {
                    URL.revokeObjectURL(url);
                }, 2000);
                
                const sizeMB = (dataStr.length / 1024 / 1024).toFixed(2);
                showStatus('success', `Large dataset (${sizeMB}MB) exported successfully in ${chunks.length} chunks!`);
                
            } catch (error) {
                console.error('Large export error:', error);
                showStatus('error', `Large export failed: ${error.message}. Try GitHub upload instead.`);
            }
        }

                 function exportSample() {
             const sampleMovie = {
                 id: 1,
                 title: "Sample Movie",
                 type: "movie",
                 label: "Action",
                 sublabel: "Released 2023",
                 imdb: "8.5",
                 downloadas: "sample-movie.mp4",
                 comment: true,
                 playas: "video",
                 description: "This is a sample movie entry showing the correct CineMax structure",
                 classification: "PG-13",
                 year: "2023",
                 duration: "2:30",
                 rating: 8.5,
                 image: "https://example.com/poster.jpg",
                 cover: "https://example.com/cover.jpg",
                 genres: [
                     {
                         id: 28,
                         title: "Action"
                     }
                 ],
                 sources: [
                     {
                         id: 1,
                         type: "embed",
                         title: "VidSrc Server 1080p",
                         quality: "1080p",
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: "https://vidsrc.net/embed/movie/123"
                     },
                     {
                         id: 2,
                         type: "embed",
                         title: "VidJoy Server 1080p",
                         quality: "1080p", 
                         size: "Auto",
                         kind: "play",
                         premium: "false",
                         external: false,
                         url: "https://vidjoy.pro/embed/movie/123"
                     }
                 ],
                 trailer: {
                     id: 3,
                     type: "youtube",
                     title: "Sample Movie Trailer",
                     url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                 },
                 actors: [
                     {
                         id: 1,
                         name: "John Doe",
                         type: "actor",
                         role: "Lead Actor",
                         image: "https://example.com/actor.jpg",
                         bio: "Sample actor"
                     }
                 ],
                 subtitles: [],
                 views: 5000,
                 created_at: new Date().toISOString().split('T')[0]
             };

             const sampleData = {
                 api_info: {
                     version: "2.0",
                     description: "Sample CineMax API Format with Complete Structure",
                     last_updated: new Date().toISOString().split('T')[0],
                     total_movies: 1,
                     total_series: 0,
                     total_channels: 0
                 },
                 home: {
                     slides: [
                         {
                             id: 1,
                             title: "Sample Movie",
                             type: "movie",
                             image: "https://example.com/poster.jpg",
                             url: "movies/1",
                             poster: sampleMovie
                         }
                     ],
                     featuredMovies: [sampleMovie],
                     channels: []
                 },
                 // Root-level movies array for GenreActivity and other fragments
                 movies: [sampleMovie],
                 actors: [
                     {
                         id: 1,
                         name: "John Doe",
                         type: "actor",
                         role: "Lead Actor",
                         image: "https://example.com/actor.jpg",
                         born: "1980-01-01",
                         height: "6'0\"",
                         bio: "Sample actor biography",
                         movies: [
                             {
                                 id: 1,
                                 title: "Sample Movie",
                                 image: "https://example.com/poster.jpg",
                                 year: "2023"
                             }
                         ]
                     }
                 ],
                 genres: [
                     {
                         id: 28,
                         title: "Action",
                         posters: [sampleMovie]
                     }
                 ],
                 channels: []
             };
            
            const dataStr = JSON.stringify(sampleData, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = 'cinemax-sample-format.json';
            link.click();
            
            showStatus('success', 'Sample format exported!');
        }

        async function clearAllData() {
            if (confirm('Are you sure you want to clear all data? This cannot be undone.')) {
                currentData = {
                    Categories: [
                        {
                            MainCategory: "Live TV",
                            SubCategories: ["Entertainment"],
                            Entries: []
                        },
                        {
                            MainCategory: "Movies", 
                            SubCategories: ["Action", "Comedy", "Drama", "Horror", "Sci-Fi"],
                            Entries: []
                        },
                        {
                            MainCategory: "TV Series",
                            SubCategories: ["Anime", "Action", "Comedy", "Drama"],
                            Entries: []
                        }
                    ]
                };
                
                await saveData();
                updateDataStats();
                updatePreview();
                showStatus('success', 'All data cleared!');
            }
        }

        async function removeDuplicates() {
            let originalCount = 0;
            let removedCount = 0;
            
            // Remove duplicates from each category
            currentData.Categories.forEach(category => {
                originalCount += category.Entries.length;
                
                const seen = new Set();
                const originalEntries = [...category.Entries];
                category.Entries = category.Entries.filter(entry => {
                    const key = `${entry.Title}-${entry.Year || 'Unknown'}`;
                    if (seen.has(key)) {
                        return false;
                    }
                    seen.add(key);
                    return true;
                });
                
                removedCount += originalEntries.length - category.Entries.length;
            });
            
            await saveData();
            updateDataStats();
            updatePreview();
            showStatus('success', `Removed ${removedCount} duplicate items!`);
        }

        // Storage management functions
        async function migrateStorage() {
            if (!confirm('This will migrate your data between localStorage and IndexedDB. Continue?')) {
                return;
            }

            try {
                showStatus('info', 'Migrating storage...');
                
                if (useIndexedDB) {
                    // Migrate from IndexedDB to localStorage
                    const dataString = JSON.stringify(currentData);
                    if (dataString.length < 4 * 1024 * 1024) { // Less than 4MB
                        const compressed = compressData(currentData);
                        localStorage.setItem('playlist-data', compressed);
                        localStorage.setItem('playlist-data-compressed', 'true');
                        localStorage.removeItem('playlist-data-indexeddb');
                        useIndexedDB = false;
                        showStatus('success', 'Data migrated to localStorage with compression!');
                    } else {
                        showStatus('warning', 'Data too large for localStorage. Keeping in IndexedDB.');
                    }
                } else {
                    // Migrate from localStorage to IndexedDB
                    await saveToIndexedDB(currentData);
                    localStorage.removeItem('playlist-data');
                    localStorage.removeItem('playlist-data-compressed');
                    localStorage.setItem('playlist-data-indexeddb', 'true');
                    useIndexedDB = true;
                    showStatus('success', 'Data migrated to IndexedDB!');
                }
                
                updateDataStats();
            } catch (error) {
                console.error('Migration failed:', error);
                showStatus('error', 'Migration failed: ' + error.message);
            }
        }

        async function optimizeStorage() {
            // Show detailed warning before proceeding
            const warningMessage = `⚠️ STORAGE OPTIMIZATION WARNING ⚠️

This will remove entries that appear to have:
• Empty or missing titles
• No server links
• Malformed data structures

This operation may accidentally delete valid TV series or movies if their data structure is unexpected.

RECOMMENDATION: Create a backup first (Export your data)

Are you sure you want to continue?`;

            if (!confirm(warningMessage)) {
                return;
            }
            
            try {
                showStatus('info', 'Analyzing entries for optimization...');
                
                let totalOptimized = 0;
                let analysisReport = [];
                
                currentData.Categories.forEach(category => {
                    const originalLength = category.Entries.length;
                    const toRemove = [];
                    
                    // Analyze what would be removed
                    category.Entries.forEach((entry, index) => {
                        const issues = [];
                        
                        if (!entry.Title || entry.Title.trim() === '') {
                            issues.push('missing title');
                        }
                        if (!entry.Servers || entry.Servers.length === 0) {
                            issues.push('no servers');
                        }
                        if (!entry.Image || entry.Image.trim() === '') {
                            issues.push('missing image');
                        }
                        
                        if (issues.length > 0) {
                            toRemove.push({
                                index,
                                title: entry.Title || 'Untitled',
                                issues: issues.join(', ')
                            });
                        }
                    });
                    
                    if (toRemove.length > 0) {
                        analysisReport.push(`${category.MainCategory}: ${toRemove.length} entries to remove`);
                        
                        // Remove entries in reverse order to maintain indices
                        toRemove.reverse().forEach(item => {
                            category.Entries.splice(item.index, 1);
                        });
                        
                        totalOptimized += toRemove.length;
                    }
                });
                
                if (totalOptimized === 0) {
                    showStatus('success', 'No optimization needed - all entries appear to be valid!');
                    return;
                }
                
                // Show what was found before final confirmation
                const finalConfirm = `Found ${totalOptimized} entries to remove:
${analysisReport.join('\n')}
Proceed with removal?`;
                
                if (!confirm(finalConfirm)) {
                    // Reload original data
                    await loadData();
                    showStatus('info', 'Optimization cancelled - no changes made');
                    return;
                }
                
                // Save optimized data
                await saveData();
                updateDataStats();
                updatePreview();
                
                showStatus('success', `Storage optimized! Removed ${totalOptimized} problematic entries. Check the results and restore from backup if needed.`);
                
            } catch (error) {
                console.error('Optimization failed:', error);
                showStatus('error', 'Optimization failed: ' + error.message);
                // Try to reload original data
                await loadData();
            }
        }

        // Preview and management functions
        function updatePreview() {
            const filter = document.getElementById('preview-filter')?.value || 'all';
            const searchTerm = document.getElementById('preview-search')?.value.toLowerCase() || '';
            const container = document.getElementById('content-preview');
            
            if (!container) return;
            
            let allItems = [];
            
            // Collect items from all categories based on filter
            currentData.Categories.forEach(category => {
                category.Entries.forEach(entry => {
                    let itemType = '';
                    if (category.MainCategory === "Movies") itemType = 'movie';
                    else if (category.MainCategory === "TV Series") itemType = 'series';
                    else if (category.MainCategory === "Live TV") itemType = 'live';
                    
                    const typeMatch = (filter === 'all' || filter === itemType);
                    const searchMatch = (entry.Title.toLowerCase().includes(searchTerm));

                    if (typeMatch && searchMatch) {
                        allItems.push({
                            ...entry,
                            type: itemType,
                            category: category.MainCategory,
                            image: entry.Poster || entry.Thumbnail,
                            title: entry.Title,
                            description: entry.Description,
                            year: entry.Year,
                            rating: entry.Rating,
                            sources: entry.Servers || []
                        });
                    }
                });
            });

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
                        <div class="meta">${item.year || 'Unknown'} • ${item.parentalRating || 'N/A'} • ${item.type?.toUpperCase()} • Rating: ${item.rating || 'N/A'}</div>
                        <div class="meta">Category: ${item.category} • SubCategory: ${item.SubCategory || 'N/A'}</div>
                        <div class="meta">Servers: ${item.sources?.length || 0}</div>
                        <div style="margin-top: 10px;">
                            <button class="btn btn-secondary btn-small" onclick="editContent('${item.Title}', '${item.category}')">Edit</button>
                            <button class="btn btn-warning btn-small" onclick="addServerToContent('${item.Title}', '${item.category}')">Add Server</button>
                            <button class="btn btn-danger btn-small" onclick="deleteContent('${item.Title}', '${item.category}')">Delete</button>
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

        function editContent(title, category) {
            // Find the content
            let content = null;
            let foundCategory = null;
            
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === category) {
                    const entry = cat.Entries.find(e => e.Title === title);
                    if (entry) {
                        content = entry;
                        foundCategory = cat;
                    }
                }
            });
            
            if (!content) {
                showStatus('error', 'Content not found');
                return;
            }
            
            // Show edit modal (simplified version)
            const modal = document.getElementById('edit-modal');
            if (!modal) {
                // Create modal if it doesn't exist
                const modalHTML = `
                    <div id="edit-modal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeEditModal()">&times;</span>
                            <h2>Edit Content</h2>
                            <div id="edit-form"></div>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <button class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
                                <button class="btn btn-secondary" onclick="exportData()">💾 Export as Backup</button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            const form = document.getElementById('edit-form');
            
            // Build servers HTML
            let serversHTML = '';
            if (content.Servers && content.Servers.length > 0) {
                content.Servers.forEach((server, index) => {
                    serversHTML += `
                        <div class="server-item">
                            <input type="text" placeholder="Server Name" class="edit-server-name" value="${server.name || ''}">
                            <input type="url" placeholder="Video URL" class="edit-server-url" value="${server.url || ''}">
                            <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                            <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                        </div>
                    `;
                });
            } else {
                serversHTML = `
                    <div class="server-item">
                        <input type="text" placeholder="Server Name" class="edit-server-name">
                        <input type="url" placeholder="Video URL" class="edit-server-url">
                        <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                        <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                    </div>
                `;
            }
            
            // Build seasons HTML for TV Series
            let seasonsHTML = '';
            if (content.Seasons && content.Seasons.length > 0) {
                content.Seasons.forEach((season, seasonIndex) => {
                    seasonsHTML += `
                        <div class="season-group">
                            <h4>Season ${season.Season}</h4>
                            ${season.Episodes.map((episode, episodeIndex) => `
                                <div class="episode-group">
                                    <h5>Episode ${episode.Episode}: ${episode.Title}</h5>
                                    <div class="episode-servers">
                                        ${episode.Servers && episode.Servers.length > 0 ? 
                                            episode.Servers.map(server => `
                                                <div class="server-item">
                                                    <input type="text" placeholder="Server Name" class="edit-episode-server-name" value="${server.name || ''}" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                                                    <input type="url" placeholder="Video URL" class="edit-episode-server-url" value="${server.url || ''}" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                                                    <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                                                    <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                                                </div>
                                            `).join('') : 
                                            `<div class="server-item">
                                                <input type="text" placeholder="Server Name" class="edit-episode-server-name" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                                                <input type="url" placeholder="Video URL" class="edit-episode-server-url" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                                                <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                                                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
                                            </div>`
                                        }
                                        <button class="btn btn-secondary btn-small" onclick="addEpisodeServer(${seasonIndex}, ${episodeIndex})">+ Add Server</button>
                                        <button class="btn btn-warning btn-small" onclick="addServerToEpisode('${content.Title}', ${seasonIndex}, ${episodeIndex})">Quick Add</button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                });
            }
            
            form.innerHTML = `
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" id="edit-title" value="${content.Title}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="edit-description" rows="4">${content.Description || ''}</textarea>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <input type="text" id="edit-year" value="${content.Year || ''}">
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <input type="number" id="edit-rating" min="0" max="10" step="0.1" value="${content.Rating || ''}">
                </div>
                <div class="form-group">
                    <label>Parental Rating</label>
                    <input type="text" id="edit-parental-rating" placeholder="e.g., PG-13" value="${content.parentalRating || ''}">
                </div>
                <div class="form-group">
                    <label>Servers</label>
                    <div id="edit-servers" class="server-list">
                        ${serversHTML}
                    </div>
                    <button class="btn btn-secondary btn-small" onclick="addEditServer()">+ Add Server</button>
                </div>
                ${content.Seasons ? `
                <div class="form-group">
                    <label>Seasons & Episodes</label>
                    <div id="edit-seasons">
                        ${seasonsHTML}
                    </div>
                </div>
                ` : ''}
                <input type="hidden" id="edit-original-title" value="${title}">
                <input type="hidden" id="edit-category" value="${category}">
            `;
            
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function addEditServer() {
            const container = document.getElementById('edit-servers');
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';
            serverItem.innerHTML = `
                <input type="text" placeholder="Server Name" class="edit-server-name">
                <input type="url" placeholder="Video URL" class="edit-server-url">
                <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
            `;
            container.appendChild(serverItem);
        }

        function addEpisodeServer(seasonIndex, episodeIndex) {
            const episodeGroup = document.querySelector(`[data-season="${seasonIndex}"][data-episode="${episodeIndex}"]`).closest('.episode-group');
            const serversContainer = episodeGroup.querySelector('.episode-servers');
            
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';
            serverItem.innerHTML = `
                <input type="text" placeholder="Server Name" class="edit-episode-server-name" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                <input type="url" placeholder="Video URL" class="edit-episode-server-url" data-season="${seasonIndex}" data-episode="${episodeIndex}">
                <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                <button class="btn btn-danger btn-small" onclick="removeServer(this)">Remove</button>
            `;
            serversContainer.appendChild(serverItem);
        }

        async function saveEdit() {
            const originalTitle = document.getElementById('edit-original-title').value;
            const category = document.getElementById('edit-category').value;
            const newTitle = document.getElementById('edit-title').value;
            const description = document.getElementById('edit-description').value;
            const year = document.getElementById('edit-year').value;
            const rating = parseInt(document.getElementById('edit-rating').value);
            const parentalRating = document.getElementById('edit-parental-rating').value;
            
            // Find and update content
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === category) {
                    const entry = cat.Entries.find(e => e.Title === originalTitle);
                    if (entry) {
                        entry.Title = newTitle;
                        entry.Description = description;
                        if (year) entry.Year = parseInt(year);
                        entry.Rating = rating;
                        entry.parentalRating = parentalRating;
                        
                        // Update servers
                        const serverInputs = document.querySelectorAll('#edit-servers .server-item');
                        const servers = [];
                        serverInputs.forEach(item => {
                            const name = item.querySelector('.edit-server-name').value.trim();
                            const url = item.querySelector('.edit-server-url').value.trim();
                            if (name && url) {
                                servers.push({ name, url });
                            }
                        });
                        entry.Servers = dedupeServers([...(entry.Servers || []), ...servers]);
                        
                        // Update episode servers for TV Series
                        if (entry.Seasons) {
                            entry.Seasons.forEach((season, seasonIndex) => {
                                season.Episodes.forEach((episode, episodeIndex) => {
                                    const episodeServers = [];
                                    const episodeServerInputs = document.querySelectorAll(`.edit-episode-server-name[data-season="${seasonIndex}"][data-episode="${episodeIndex}"]`);
                                    
                                    episodeServerInputs.forEach(input => {
                                        const serverItem = input.closest('.server-item');
                                        const name = input.value.trim();
                                        const url = serverItem.querySelector('.edit-episode-server-url').value.trim();
                                        if (name && url) {
                                            episodeServers.push({ name, url });
                                        }
                                    });
                                    
                                    episode.Servers = dedupeServers([...(episode.Servers || []), ...episodeServers]);
                                });
                            });
                        }
                    }
                }
            });
            
            try {
                await saveData();
                updatePreview();
                closeEditModal();
                showStatus('success', 'Content updated successfully with server changes!');
            } catch (error) {
                console.error('Save failed:', error);
                showStatus('error', 'Failed to save changes: ' + error.message + '. Your changes are preserved in memory but not saved to storage.');
                // Keep the modal open so user can try again or export data
            }
        }

        function deleteContent(title, category) {
            if (confirm(`Are you sure you want to delete "${title}"?`)) {
                // Remove from the appropriate category
                currentData.Categories.forEach(cat => {
                    if (cat.MainCategory === category) {
                        cat.Entries = cat.Entries.filter(entry => entry.Title !== title);
                    }
                });
                
                saveData();
                updateDataStats();
                updatePreview();
                showStatus('success', 'Content deleted successfully!');
            }
        }

        function addServerToContent(title, category) {
            // Find the content
            let content = null;
            let foundCategory = null;
            
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === category) {
                    const entry = cat.Entries.find(e => e.Title === title);
                    if (entry) {
                        content = entry;
                        foundCategory = cat;
                    }
                }
            });
            
            if (!content) {
                showStatus('error', 'Content not found');
                return;
            }
            
            // Show quick add server modal
            const modal = document.getElementById('edit-modal');
            if (!modal) {
                const modalHTML = `
                    <div id="edit-modal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeEditModal()">&times;</span>
                            <h2>Add Server to ${content.Title}</h2>
                            <div id="edit-form"></div>
                            <button class="btn btn-primary" onclick="saveQuickServer()">Add Server</button>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            const form = document.getElementById('edit-form');
            form.innerHTML = `
                <div class="form-group">
                    <label>Server Name</label>
                                            <input type="text" id="quick-server-name" placeholder="e.g., VidJoy 1080p">
                </div>
                <div class="form-group">
                    <label>Server URL</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="url" id="quick-server-url" placeholder="https://..." style="flex: 1;">
                        <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                    </div>
                </div>
                <input type="hidden" id="quick-content-title" value="${title}">
                <input type="hidden" id="quick-content-category" value="${category}">
            `;
            
            document.getElementById('edit-modal').style.display = 'block';
        }

        function saveQuickServer() {
            const title = document.getElementById('quick-content-title').value;
            const category = document.getElementById('quick-content-category').value;
            const serverName = document.getElementById('quick-server-name').value.trim();
            const serverUrl = document.getElementById('quick-server-url').value.trim();
            
            if (!serverName || !serverUrl) {
                showStatus('warning', 'Please enter both server name and URL');
                return;
            }
            
            // Find and update content
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === category) {
                    const entry = cat.Entries.find(e => e.Title === title);
                    if (entry) {
                        if (!entry.Servers) {
                            entry.Servers = [];
                        }
                        entry.Servers.push({ name: serverName, url: serverUrl });
                    }
                }
            });
            
            saveData();
            updatePreview();
            closeEditModal();
            showStatus('success', `Server "${serverName}" added to "${title}" successfully!`);
        }

        function addServerToEpisode(title, seasonIndex, episodeIndex) {
            // Find the content
            let content = null;
            
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === "TV Series") {
                    const entry = cat.Entries.find(e => e.Title === title);
                    if (entry) {
                        content = entry;
                    }
                }
            });
            
            if (!content || !content.Seasons || !content.Seasons[seasonIndex] || !content.Seasons[seasonIndex].Episodes[episodeIndex]) {
                showStatus('error', 'Episode not found');
                return;
            }
            
            const episode = content.Seasons[seasonIndex].Episodes[episodeIndex];
            
            // Show quick add server modal for episode
            const modal = document.getElementById('edit-modal');
            if (!modal) {
                const modalHTML = `
                    <div id="edit-modal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeEditModal()">&times;</span>
                            <h2>Add Server to Episode</h2>
                            <div id="edit-form"></div>
                            <button class="btn btn-primary" onclick="saveQuickEpisodeServer()">Add Server</button>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }
            
            const form = document.getElementById('edit-form');
            form.innerHTML = `
                <div class="form-group">
                    <label>Server Name</label>
                                            <input type="text" id="quick-episode-server-name" placeholder="e.g., VidJoy 1080p">
                </div>
                <div class="form-group">
                    <label>Server URL</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="url" id="quick-episode-server-url" placeholder="https://..." style="flex: 1;">
                        <button class="paste-btn" onclick="pasteFromClipboard(this)">📋 Paste</button>
                    </div>
                </div>
                <input type="hidden" id="quick-episode-title" value="${title}">
                <input type="hidden" id="quick-episode-season" value="${seasonIndex}">
                <input type="hidden" id="quick-episode-episode" value="${episodeIndex}">
            `;
            
            document.getElementById('edit-modal').style.display = 'block';
        }

        function saveQuickEpisodeServer() {
            const title = document.getElementById('quick-episode-title').value;
            const seasonIndex = parseInt(document.getElementById('quick-episode-season').value);
            const episodeIndex = parseInt(document.getElementById('quick-episode-episode').value);
            const serverName = document.getElementById('quick-episode-server-name').value.trim();
            const serverUrl = document.getElementById('quick-episode-server-url').value.trim();
            
            if (!serverName || !serverUrl) {
                showStatus('warning', 'Please enter both server name and URL');
                return;
            }
            
            // Find and update content
            currentData.Categories.forEach(cat => {
                if (cat.MainCategory === "TV Series") {
                    const entry = cat.Entries.find(e => e.Title === title);
                    if (entry && entry.Seasons && entry.Seasons[seasonIndex] && entry.Seasons[seasonIndex].Episodes[episodeIndex]) {
                        if (!entry.Seasons[seasonIndex].Episodes[episodeIndex].Servers) {
                            entry.Seasons[seasonIndex].Episodes[episodeIndex].Servers = [];
                        }
                        entry.Seasons[seasonIndex].Episodes[episodeIndex].Servers.push({ name: serverName, url: serverUrl });
                    }
                }
            });
            
            saveData();
            updatePreview();
            closeEditModal();
            showStatus('success', `Server "${serverName}" added to episode successfully!`);
        }

        // Utility functions
        function updateDataStats() {
            let movieCount = 0;
            let seriesCount = 0;
            let channelCount = 0;
            
            // Count entries in each category
            currentData.Categories.forEach(category => {
                if (category.MainCategory === "Movies") {
                    movieCount += category.Entries.length;
                } else if (category.MainCategory === "TV Series") {
                    seriesCount += category.Entries.length;
                } else if (category.MainCategory === "Live TV") {
                    channelCount += category.Entries.length;
                }
            });
            
            // Calculate data size
            const dataString = JSON.stringify(currentData);
            const dataSizeKB = (dataString.length / 1024).toFixed(1);
            const dataSizeMB = (dataString.length / 1024 / 1024).toFixed(2);
            const displaySize = dataSizeKB > 1024 ? `${dataSizeMB} MB` : `${dataSizeKB} KB`;
            
            // Update display
            if (document.getElementById('movie-count')) {
                document.getElementById('movie-count').textContent = movieCount;
                document.getElementById('series-count').textContent = seriesCount;
                document.getElementById('channel-count').textContent = channelCount;
                document.getElementById('total-count').textContent = movieCount + seriesCount + channelCount;
                
                // Update storage info
                if (document.getElementById('storage-type')) {
                    document.getElementById('storage-type').textContent = useIndexedDB ? 'IndexedDB' : 'localStorage';
                    document.getElementById('data-size').textContent = displaySize;
                    
                    const isCompressed = localStorage.getItem('playlist-data-compressed') === 'true';
                    document.getElementById('compression-status').textContent = isCompressed ? 'Enabled' : 'None';
                    
                    // Update storage info styling based on size
                    const storageInfo = document.getElementById('storage-info');
                    if (dataSizeKB > 5120) { // > 5MB
                        storageInfo.className = 'status warning';
                    } else if (dataSizeKB > 10240) { // > 10MB
                        storageInfo.className = 'status error';
                    } else {
                        storageInfo.className = 'status info';
                    }
                }
            }
        }

        function showStatus(type, message) {
            // Create or update status element
            let statusEl = document.getElementById('global-status');
            if (!statusEl) {
                statusEl = document.createElement('div');
                statusEl.id = 'global-status';
                statusEl.style.position = 'fixed';
                statusEl.style.top = '20px';
                statusEl.style.right = '20px';
                statusEl.style.zIndex = '9999';
                statusEl.style.maxWidth = '400px';
                document.body.appendChild(statusEl);
            }
            
            statusEl.innerHTML = `<div class="status ${type}">${message}</div>`;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (statusEl.parentNode) {
                    statusEl.parentNode.removeChild(statusEl);
                }
            }, 5000);
        }

        function showLoading(elementId, show) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = show ? 'inline-block' : 'none';
            }
        }

                                 // Enhanced storage system with IndexedDB fallback and compression
        let useIndexedDB = false;
        let db = null;

        // Initialize IndexedDB
        async function initIndexedDB() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('PlaylistManager', 1);
                
                request.onerror = () => reject(request.error);
                request.onsuccess = () => {
                    db = request.result;
                    resolve(db);
                };
                
                request.onupgradeneeded = (event) => {
                    const database = event.target.result;
                    if (!database.objectStoreNames.contains('data')) {
                        database.createObjectStore('data', { keyPath: 'id' });
                    }
                };
            });
        }

        // Save data to IndexedDB
        async function saveToIndexedDB(data) {
            if (!db) {
                await initIndexedDB();
            }
            
            return new Promise((resolve, reject) => {
                const transaction = db.transaction(['data'], 'readwrite');
                const store = transaction.objectStore('data');
                const request = store.put({ id: 'playlist-data', data: data, timestamp: Date.now() });
                
                request.onerror = () => reject(request.error);
                request.onsuccess = () => resolve();
            });
        }

        // Load data from IndexedDB
        async function loadFromIndexedDB() {
            if (!db) {
                await initIndexedDB();
            }
            
            return new Promise((resolve, reject) => {
                const transaction = db.transaction(['data'], 'readonly');
                const store = transaction.objectStore('data');
                const request = store.get('playlist-data');
                
                request.onerror = () => reject(request.error);
                request.onsuccess = () => {
                    const result = request.result;
                    resolve(result ? result.data : null);
                };
            });
        }

        // Simple compression using LZ-string-like algorithm
        function compressData(data) {
            try {
                const jsonString = JSON.stringify(data);
                // Simple compression by removing whitespace and common patterns
                let compressed = jsonString
                    .replace(/\s+/g, '')
                    .replace(/"MainCategory"/g, '"MC"')
                    .replace(/"SubCategories"/g, '"SC"')
                    .replace(/"Entries"/g, '"E"')
                    .replace(/"Title"/g, '"T"')
                    .replace(/"Description"/g, '"D"')
                    .replace(/"Poster"/g, '"P"')
                    .replace(/"Thumbnail"/g, '"Th"')
                    .replace(/"Rating"/g, '"R"')
                    .replace(/"Year"/g, '"Y"')
                    .replace(/"Servers"/g, '"S"')
                    .replace(/"Seasons"/g, '"Se"')
                    .replace(/"Episodes"/g, '"Ep"')
                    .replace(/"Episode"/g, '"En"')
                    .replace(/"Season"/g, '"Sn"')
                    .replace(/"Duration"/g, '"Du"')
                    .replace(/"SubCategory"/g, '"SuC"')
                    .replace(/"Country"/g, '"Co"');
                
                return compressed;
            } catch (error) {
                console.warn('Compression failed, using original data:', error);
                return JSON.stringify(data);
            }
        }

        // Decompress data
        function decompressData(compressedString) {
            try {
                let decompressed = compressedString
                    .replace(/"MC"/g, '"MainCategory"')
                    .replace(/"SC"/g, '"SubCategories"')
                    .replace(/"E"/g, '"Entries"')
                    .replace(/"T"/g, '"Title"')
                    .replace(/"D"/g, '"Description"')
                    .replace(/"P"/g, '"Poster"')
                    .replace(/"Th"/g, '"Thumbnail"')
                    .replace(/"R"/g, '"Rating"')
                    .replace(/"Y"/g, '"Year"')
                    .replace(/"S"/g, '"Servers"')
                    .replace(/"Se"/g, '"Seasons"')
                    .replace(/"Ep"/g, '"Episodes"')
                    .replace(/"En"/g, '"Episode"')
                    .replace(/"Sn"/g, '"Season"')
                    .replace(/"Du"/g, '"Duration"')
                    .replace(/"SuC"/g, '"SubCategory"')
                    .replace(/"Co"/g, '"Country"');
                
                return JSON.parse(decompressed);
            } catch (error) {
                console.warn('Decompression failed, trying original parse:', error);
                return JSON.parse(compressedString);
            }
        }

        // Enhanced save function with multiple strategies
        async function saveData() {
            try {
                const dataString = JSON.stringify(currentData);
                const dataSizeMB = (dataString.length / 1024 / 1024).toFixed(2);
                console.log(`💾 Attempting to save ${dataSizeMB}MB of data`);
                
                // Strategy 1: Try localStorage with compression for smaller datasets
                if (dataString.length < 4 * 1024 * 1024) { // Less than 4MB
                    try {
                        const compressed = compressData(currentData);
                        const compressionRatio = ((dataString.length - compressed.length) / dataString.length * 100).toFixed(1);
                        console.log(`🗜️ Compression saved ${compressionRatio}% space`);
                        
                        // Test localStorage availability
                        const testKey = 'storage-test-' + Date.now();
                        localStorage.setItem(testKey, 'test');
                        localStorage.removeItem(testKey);
                        
                        localStorage.setItem('playlist-data', compressed);
                        localStorage.setItem('playlist-data-compressed', 'true');
                        console.log('✅ Data saved to localStorage with compression');
                        useIndexedDB = false;
                        return;
                    } catch (localStorageError) {
                        console.warn('⚠️ localStorage failed, trying IndexedDB:', localStorageError.message);
                    }
                }
                
                // Strategy 2: Use IndexedDB for large datasets
                console.log('📦 Using IndexedDB for large dataset...');
                await saveToIndexedDB(currentData);
                useIndexedDB = true;
                
                // Clear localStorage to free up space
                try {
                    localStorage.removeItem('playlist-data');
                    localStorage.removeItem('playlist-data-compressed');
                    localStorage.setItem('playlist-data-indexeddb', 'true');
                } catch (e) {
                    console.warn('Could not clear localStorage:', e);
                }
                
                console.log('✅ Data saved to IndexedDB successfully');
                showStatus('success', `Large dataset (${dataSizeMB}MB) saved to IndexedDB successfully!`);
                
            } catch (error) {
                console.error('💥 All storage methods failed:', error);
                
                let errorMessage = 'Failed to save data: ';
                if (error.name === 'QuotaExceededError') {
                    errorMessage += 'Storage quota exceeded. Try clearing browser data or use export feature.';
                } else if (error.message.includes('not available')) {
                    errorMessage += 'Storage not available. Data will only persist in current session.';
                } else {
                    errorMessage += error.message;
                }
                
                showStatus('error', errorMessage);
                throw new Error(errorMessage);
            }
        }
        async function loadSavedData() {
            try {
                // Check if data is stored in IndexedDB
                if (localStorage.getItem('playlist-data-indexeddb') === 'true') {
                    console.log('📦 Loading data from IndexedDB...');
                    try {
                        const data = await loadFromIndexedDB();
                        if (data && data.Categories && Array.isArray(data.Categories)) {
                            currentData = data;
                            useIndexedDB = true;
                            console.log('✅ Data loaded from IndexedDB successfully');
                            return;
                        }
                    } catch (indexedDBError) {
                        console.warn('⚠️ IndexedDB loading failed:', indexedDBError);
                        localStorage.removeItem('playlist-data-indexeddb');
                    }
                }
                
                // Try localStorage with compression check
                let saved = localStorage.getItem('playlist-data');
                if (saved) {
                    try {
                        let data;
                        
                        // Check if data is compressed
                        if (localStorage.getItem('playlist-data-compressed') === 'true') {
                            console.log('🗜️ Decompressing data from localStorage...');
                            data = decompressData(saved);
                        } else {
                            data = JSON.parse(saved);
                        }
                        
                        // Check if it's the new Categories format
                        if (data.Categories && Array.isArray(data.Categories)) {
                            currentData = data;
                            useIndexedDB = false;
                            console.log('✅ Data loaded from localStorage successfully');
                            return;
                        }
                    } catch (parseError) {
                        console.warn('⚠️ Failed to parse localStorage data:', parseError);
                        localStorage.removeItem('playlist-data');
                        localStorage.removeItem('playlist-data-compressed');
                    }
                }
                
                // Fallback to old cinemax-data format
                saved = localStorage.getItem('cinemax-data');
                if (saved) {
                    try {
                        const data = JSON.parse(saved);
                        console.log('🔄 Converting old format to Categories structure...');
                        // For now, just use the default structure
                        currentData = {
                            Categories: [
                                {
                                    MainCategory: "Live TV",
                                    SubCategories: ["Entertainment"],
                                    Entries: []
                                },
                                {
                                    MainCategory: "Movies", 
                                    SubCategories: ["Action", "Comedy", "Drama", "Horror", "Sci-Fi"],
                                    Entries: []
                                },
                                {
                                    MainCategory: "TV Series",
                                    SubCategories: ["Anime", "Action", "Comedy", "Drama"],
                                    Entries: []
                                }
                            ]
                        };
                        localStorage.removeItem('cinemax-data');
                        await saveData(); // Save in new format
                    } catch (error) {
                        console.error('Error converting old data:', error);
                    }
                }
                
            } catch (error) {
                console.error('Error loading saved data:', error);
                showStatus('warning', 'Could not load saved data. Starting with empty dataset.');
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        // Auto-Embed Functions
        function getAutoEmbedConfig() {
            return {
                vidsrc: {
                    enabled: document.getElementById('auto-vidsrc').checked,
                    quality: document.getElementById('vidsrc-quality').value
                },
                vidjoy: {
                    enabled: document.getElementById('auto-vidjoy').checked,
                    quality: document.getElementById('vidjoy-quality').value
                },
                multiembed: {
                    enabled: document.getElementById('auto-multiembed').checked,
                    quality: document.getElementById('multiembed-quality').value
                },
                embedsu: {
                    enabled: document.getElementById('auto-embedsu').checked,
                    quality: document.getElementById('embedsu-quality').value
                },
                vidsrcme: {
                    enabled: document.getElementById('auto-vidsrcme').checked,
                    quality: document.getElementById('vidsrcme-quality').value
                },
                autoembed: {
                    enabled: document.getElementById('auto-autoembed').checked,
                    quality: document.getElementById('autoembed-quality').value
                },
                smashystream: {
                    enabled: document.getElementById('auto-smashystream').checked,
                    quality: document.getElementById('smashystream-quality').value
                },
                vidsrcto: {
                    enabled: document.getElementById('auto-vidsrcto').checked,
                    quality: document.getElementById('vidsrcto-quality').value
                },
                vidsrcxyz: {
                    enabled: document.getElementById('auto-vidsrcxyz').checked,
                    quality: document.getElementById('vidsrcxyz-quality').value
                },
                embedsoap: {
                    enabled: document.getElementById('auto-embedsoap').checked,
                    quality: document.getElementById('embedsoap-quality').value
                },
                moviesapi: {
                    enabled: document.getElementById('auto-moviesapi').checked,
                    quality: document.getElementById('moviesapi-quality').value
                },
                dbgo: {
                    enabled: document.getElementById('auto-dbgo').checked,
                    quality: document.getElementById('dbgo-quality').value
                },
                flixhq: {
                    enabled: document.getElementById('auto-flixhq').checked,
                    quality: document.getElementById('flixhq-quality').value
                },
                gomovies: {
                    enabled: document.getElementById('auto-gomovies').checked,
                    quality: document.getElementById('gomovies-quality').value
                },
                showbox: {
                    enabled: document.getElementById('auto-showbox').checked,
                    quality: document.getElementById('showbox-quality').value
                },
                primewire: {
                    enabled: document.getElementById('auto-primewire').checked,
                    quality: document.getElementById('primewire-quality').value
                },
                hdtoday: {
                    enabled: document.getElementById('auto-hdtoday').checked,
                    quality: document.getElementById('hdtoday-quality').value
                },
                vidcloud: {
                    enabled: document.getElementById('auto-vidcloud').checked,
                    quality: document.getElementById('vidcloud-quality').value
                },
                streamwish: {
                    enabled: document.getElementById('auto-streamwish').checked,
                    quality: document.getElementById('streamwish-quality').value
                },
                doodstream: {
                    enabled: document.getElementById('auto-doodstream').checked,
                    quality: document.getElementById('doodstream-quality').value
                },
                streamtape: {
                    enabled: document.getElementById('auto-streamtape').checked,
                    quality: document.getElementById('streamtape-quality').value
                },
                mixdrop: {
                    enabled: document.getElementById('auto-mixdrop').checked,
                    quality: document.getElementById('mixdrop-quality').value
                },
                videasy: {
                    enabled: document.getElementById('auto-videasy').checked,
                    quality: document.getElementById('videasy-quality').value
                },
                upstream: {
                    enabled: document.getElementById('auto-upstream').checked,
                    quality: document.getElementById('upstream-quality').value
                },
                godriveplayer: {
                    enabled: document.getElementById('auto-godriveplayer').checked,
                    quality: document.getElementById('godriveplayer-quality').value
                },
                twotwoembed: {
                    enabled: document.getElementById('auto-2embed').checked,
                    quality: document.getElementById('2embed-quality').value
                },
                vidlink: {
                    enabled: document.getElementById('auto-vidlink').checked,
                    quality: document.getElementById('vidlink-quality').value
                }
            };
        }

        function generateEmbedSources(tmdbId, contentType = 'movie', seasonNum = null, episodeNum = null) {
            const config = getAutoEmbedConfig();
            const sources = [];

            if (config.vidsrc.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDSRC_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDSRC_BASE}/${contentType}/${tmdbId}`;
                }
                
                sources.push({
                    name: `VidSrc ${config.vidsrc.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.vidjoy.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDJOY_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDJOY_BASE}/${contentType}/${tmdbId}`;
                }
                
                sources.push({
                    name: `VidJoy ${config.vidjoy.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.multiembed.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${MULTIEMBED_BASE}?video_id=${tmdbId}&tmdb=1&s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${MULTIEMBED_BASE}?video_id=${tmdbId}&content_type=${contentType}`;
                }
                
                sources.push({
                    name: `MultiEmbed ${config.multiembed.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.embedsu.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${EMBEDSU_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${EMBEDSU_BASE}/${contentType}/${tmdbId}`;
                }
                
                sources.push({
                    name: `Embed.su ${config.embedsu.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.vidsrcme.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDSRCME_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDSRCME_BASE}/${contentType}/${tmdbId}`;
                }
                
                sources.push({
                    name: `VidSrc.me ${config.vidsrcme.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.autoembed.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${AUTOEMBED_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${AUTOEMBED_BASE}/${contentType}/${tmdbId}`;
                }
                
                sources.push({
                    name: `AutoEmbed ${config.autoembed.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            if (config.smashystream.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${SMASHYSTREAM_BASE}/tv?id=${tmdbId}&s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${SMASHYSTREAM_BASE}/movie.html?id=${tmdbId}`;
                }
                
                sources.push({
                    name: `VidSrc.win ${config.smashystream.quality}`,
                    subtitle1: "",
                    subtitle2: "",
                    url: url
                });
            }

            // VidSrc.to
            if (config.vidsrcto.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDSRCTO_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDSRCTO_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `VidSrc.to ${config.vidsrcto.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // VidSrc.xyz
            if (config.vidsrcxyz.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDSRCXYZ_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDSRCXYZ_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `VidSrc.xyz ${config.vidsrcxyz.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // EmbedSoap
            if (config.embedsoap.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${EMBEDSOAP_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${EMBEDSOAP_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `EmbedSoap ${config.embedsoap.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // MoviesAPI
            if (config.moviesapi.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${MOVIESAPI_BASE}/${tmdbId}?s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${MOVIESAPI_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `MoviesAPI ${config.moviesapi.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // DBGO
            if (config.dbgo.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${DBGO_BASE}/${tmdbId}?s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${DBGO_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `DBGO ${config.dbgo.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // FlixHQ
            if (config.flixhq.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${FLIXHQ_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${FLIXHQ_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `FlixHQ ${config.flixhq.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // GoMovies
            if (config.gomovies.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${GOMOVIES_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${GOMOVIES_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `GoMovies ${config.gomovies.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // ShowBox
            if (config.showbox.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${SHOWBOX_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${SHOWBOX_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `ShowBox ${config.showbox.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // PrimeWire
            if (config.primewire.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${PRIMEWIRE_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${PRIMEWIRE_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `PrimeWire ${config.primewire.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // HDToday
            if (config.hdtoday.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${HDTODAY_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${HDTODAY_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `HDToday ${config.hdtoday.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // VidCloud
            if (config.vidcloud.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDCLOUD_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDCLOUD_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `VidCloud ${config.vidcloud.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // StreamWish
            if (config.streamwish.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${STREAMWISH_BASE}/${tmdbId}_s${seasonNum}e${episodeNum}`;
                } else {
                    url = `${STREAMWISH_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `StreamWish ${config.streamwish.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // DoodStream
            if (config.doodstream.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${DOODSTREAM_BASE}/${tmdbId}_s${seasonNum}e${episodeNum}`;
                } else {
                    url = `${DOODSTREAM_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `DoodStream ${config.doodstream.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // StreamTape
            if (config.streamtape.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${STREAMTAPE_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}?autoplay=true`;
                } else {
                    url = `${STREAMTAPE_BASE}/movie/${tmdbId}?autoplay=true`;
                }
                sources.push({
                    name: `Vidplus ${config.streamtape.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // MixDrop
            if (config.mixdrop.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${MIXDROP_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${MIXDROP_BASE}/movie/${tmdbId}`;
                }
                sources.push({
                    name: `2embed.stream ${config.mixdrop.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // VidEasy
            if (config.videasy.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${VIDEASY_TV_BASE}/${tmdbId}/${seasonNum}/${episodeNum}`;
                } else {
                    url = `${VIDEASY_MOVIE_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `VidEasy ${config.videasy.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // VidFast
            if (config.upstream.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${UPSTREAM_BASE}/tv/${tmdbId}/${seasonNum}/${episodeNum}?autoPlay=true`;
                } else {
                    url = `${UPSTREAM_BASE}/movie/${tmdbId}?autoPlay=true`;
                }
                sources.push({
                    name: `VidFast ${config.upstream.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // GoDrivePlayer
            if (config.godriveplayer.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${GODRIVEPLAYER_BASE}/${tmdbId}?s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${GODRIVEPLAYER_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `GoDrivePlayer ${config.godriveplayer.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // 2Embed.cc
            if (config.twotwoembed.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `${TWOTWOEMBED_BASE}/${tmdbId}?s=${seasonNum}&e=${episodeNum}`;
                } else {
                    url = `${TWOTWOEMBED_BASE}/${tmdbId}`;
                }
                sources.push({
                    name: `2Embed.cc ${config.twotwoembed.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            // VidLink
            if (config.vidlink.enabled) {
                let url;
                if (contentType === 'tv' && seasonNum && episodeNum) {
                    url = `https://vidlink.pro/tv/${tmdbId}/${seasonNum}/${episodeNum}?autoplay=true`;
                } else {
                    url = `https://vidlink.pro/movie/${tmdbId}?autoplay=true`;
                }
                sources.push({
                    name: `VidLink.pro ${config.vidlink.quality}`,
                    subtitle1: "", subtitle2: "", url: url
                });
            }

            return sources;
        }

        function hasEmbedSources(entry) {
            if (!entry.Servers || !Array.isArray(entry.Servers)) return false;
            return entry.Servers.some(server => 
                server.url && (
                    server.url.includes('vidsrc.net/embed') ||
                    server.url.includes('vidjoy.pro/embed') ||
                    server.url.includes('multiembed.mov') ||
                    server.url.includes('embed.su/embed') ||
                    server.url.includes('vidsrc.me/embed') ||
                    server.url.includes('autoembed.cc/embed') ||
                    server.url.includes('vidsrc.win') ||
                    server.url.includes('vidsrc.to/embed') ||
                    server.url.includes('vidsrc.xyz/embed') ||
                    server.url.includes('embedsoap.com') ||
                    server.url.includes('moviesapi.club') ||
                    server.url.includes('dbgo.fun') ||
                    server.url.includes('flixhq.to') ||
                    server.url.includes('gomovies.sx') ||
                    server.url.includes('showbox.media') ||
                    server.url.includes('primewire.mx') ||
                    server.url.includes('hdtoday.tv') ||
                    server.url.includes('vidcloud.to') ||
                    server.url.includes('streamwish.to') ||
                    server.url.includes('doodstream.com') ||
                    server.url.includes('streamtape.com') ||
                    server.url.includes('mixdrop.co') ||
                    server.url.includes('player.videasy.net') ||
                    server.url.includes('vidfast.pro') ||
                    server.url.includes('godriveplayer.com') ||
                    server.url.includes('2embed.cc') ||
                    server.url.includes('vidlink.pro')
                ) ||
                server.name && (
                    server.name.includes('VidSrc') ||
                    server.name.includes('VidJoy') ||
                    server.name.includes('MultiEmbed') ||
                    server.name.includes('Embed.su') ||
                    server.name.includes('AutoEmbed') ||
                    server.name.includes('VidSrc.win') ||
                    server.name.includes('EmbedSoap') ||
                    server.name.includes('MoviesAPI') ||
                    server.name.includes('DBGO') ||
                    server.name.includes('FlixHQ') ||
                    server.name.includes('GoMovies') ||
                    server.name.includes('ShowBox') ||
                    server.name.includes('PrimeWire') ||
                    server.name.includes('HDToday') ||
                    server.name.includes('VidCloud') ||
                    server.name.includes('StreamWish') ||
                    server.name.includes('DoodStream') ||
                    server.name.includes('StreamTape') ||
                    server.name.includes('MixDrop') ||
                    server.name.includes('VidEasy') ||
                    server.name.includes('VidFast') ||
                    server.name.includes('GoDrivePlayer') ||
                    server.name.includes('2Embed') ||
                    server.name.includes('VidLink')
                )
            );
        }

        function hasDirectLinksOnly(entry) {
            if (!entry.Servers || !Array.isArray(entry.Servers)) return false;
            return entry.Servers.every(server => 
                server.url && !server.url.includes('embed') && 
                !server.url.includes('vidsrc.net') && 
                !server.url.includes('vidjoy.pro') && 
                !server.url.includes('multiembed.mov') &&
                !server.url.includes('embed.su') &&
                !server.url.includes('vidsrc.me') &&
                !server.url.includes('autoembed.cc') &&
                !server.url.includes('vidsrc.win') &&
                !server.url.includes('vidsrc.to') &&
                !server.url.includes('vidsrc.xyz') &&
                !server.url.includes('embedsoap.com') &&
                !server.url.includes('moviesapi.club') &&
                !server.url.includes('dbgo.fun') &&
                !server.url.includes('flixhq.to') &&
                !server.url.includes('gomovies.sx') &&
                !server.url.includes('showbox.media') &&
                !server.url.includes('primewire.mx') &&
                !server.url.includes('hdtoday.tv') &&
                !server.url.includes('vidcloud.to') &&
                !server.url.includes('streamwish.to') &&
                !server.url.includes('doodstream.com') &&
                !server.url.includes('streamtape.com') &&
                !server.url.includes('mixdrop.co') &&
                !server.url.includes('player.videasy.net') &&
                !server.url.includes('vidfast.pro') &&
                !server.url.includes('godriveplayer.com') &&
                !server.url.includes('2embed.cc') &&
                !server.url.includes('vidlink.pro')
            );
        }

        function applyAutoEmbedToContent(entries, filterType = 'all') {
            const config = getAutoEmbedConfig();
            let processed = 0;
            let added = 0;

            entries.forEach(entry => {
                // Apply filter
                if (filterType === 'no-embed' && hasEmbedSources(entry)) return;
                if (filterType === 'direct-only' && !hasDirectLinksOnly(entry)) return;

                processed++;
                
                // Get TMDB ID from entry
                const tmdbId = entry.TMDB_ID || entry.tmdb_id;
                if (!tmdbId) return;

                // Determine content type
                const contentType = entry.Type === 'series' || entry.type === 'series' ? 'tv' : 'movie';
                
                // Generate embed sources
                const embedSources = generateEmbedSources(tmdbId, contentType);
                
                // Add embed sources to existing servers
                if (!entry.Servers) entry.Servers = [];
                entry.Servers = dedupeServers([...entry.Servers, ...embedSources]);
                
                added += embedSources.length;
            });

            return { processed, added };
        }

        function applyAutoEmbedToMovies() {
            const moviesCategory = currentData.Categories.find(c => c.MainCategory === "Movies");
            if (!moviesCategory) {
                showStatus('error', 'Movies category not found');
                return;
            }

            const filterType = document.getElementById('auto-embed-filter').value;
            const result = applyAutoEmbedToContent(moviesCategory.Entries, filterType);
            
            showStatus('success', `Applied auto-embed to ${result.processed} movies, added ${result.added} embed sources`);
            saveData();
            updateDataStats();
        }

        function applyAutoEmbedToSeries() {
            const seriesCategory = currentData.Categories.find(c => c.MainCategory === "TV Series");
            if (!seriesCategory) {
                showStatus('error', 'TV Series category not found');
                return;
            }

            const filterType = document.getElementById('auto-embed-filter').value;
            let totalProcessed = 0;
            let totalAdded = 0;

            seriesCategory.Entries.forEach(series => {
                if (series.Seasons && Array.isArray(series.Seasons)) {
                    series.Seasons.forEach(season => {
                        if (season.Episodes && Array.isArray(season.Episodes)) {
                            const result = applyAutoEmbedToContent(season.Episodes, filterType);
                            totalProcessed += result.processed;
                            totalAdded += result.added;
                        }
                    });
                }
            });

            showStatus('success', `Applied auto-embed to ${totalProcessed} episodes, added ${totalAdded} embed sources`);
            saveData();
            updateDataStats();
        }

        function applyAutoEmbedToAll() {
            const filterType = document.getElementById('auto-embed-filter').value;
            let totalProcessed = 0;
            let totalAdded = 0;

            currentData.Categories.forEach(category => {
                if (category.MainCategory === "Movies") {
                    const result = applyAutoEmbedToContent(category.Entries, filterType);
                    totalProcessed += result.processed;
                    totalAdded += result.added;
                } else if (category.MainCategory === "TV Series") {
                    category.Entries.forEach(series => {
                        if (series.Seasons && Array.isArray(series.Seasons)) {
                            series.Seasons.forEach(season => {
                                if (season.Episodes && Array.isArray(season.Episodes)) {
                                    const result = applyAutoEmbedToContent(season.Episodes, filterType);
                                    totalProcessed += result.processed;
                                    totalAdded += result.added;
                                }
                            });
                        }
                    });
                }
            });

            showStatus('success', `Applied auto-embed to ${totalProcessed} items, added ${totalAdded} embed sources`);
            saveData();
            updateDataStats();
        }

        function updateAutoEmbedStatus() {
            const statusEl = document.getElementById('auto-embed-status');
            const config = getAutoEmbedConfig();
            const enabledCount = [config.vidsrc.enabled, config.vidjoy.enabled, config.multiembed.enabled].filter(Boolean).length;
            
            if (enabledCount > 0) {
                statusEl.style.display = 'block';
                statusEl.className = 'status info';
                statusEl.textContent = `Auto-embed enabled for ${enabledCount} services (VidSrc: ${config.vidsrc.enabled ? 'ON' : 'OFF'}, VidJoy: ${config.vidjoy.enabled ? 'ON' : 'OFF'}, MultiEmbed: ${config.multiembed.enabled ? 'ON' : 'OFF'})`;
            } else {
                statusEl.style.display = 'none';
            }
        }
        // Global variables for checkbox functionality
        window.selectedContent = new Map(); // Store selected content with TMDB search results
        window.contentGroups = { movies: [], series: [] };
        const MAX_SELECTIONS = 10;

        function refreshContentCheckboxes() {
            const container = document.getElementById('content-checkbox-list');
            container.innerHTML = '';
            
            // Clear previous selections
            window.selectedContent.clear();
            updateSelectionCounter();
            
            // Group movies and series
            let movieGroups = {};
            let seriesGroups = {};
            
            currentData.Categories.forEach(category => {
                if (category.MainCategory === "Movies") {
                    category.Entries.forEach((movie, movieIndex) => {
                        if (!hasEmbedSources(movie)) {
                            if (!movieGroups[movie.Title]) {
                                movieGroups[movie.Title] = {
                                    type: 'movie',
                                    category: 'Movies',
                                    title: movie.Title,
                                    entries: []
                                };
                            }
                            movieGroups[movie.Title].entries.push({
                                index: movieIndex,
                                entry: movie
                            });
                        }
                    });
                } else if (category.MainCategory === "TV Series") {
                    category.Entries.forEach((series, seriesIndex) => {
                        let episodesWithoutEmbed = [];
                        
                        if (series.Seasons && Array.isArray(series.Seasons)) {
                            series.Seasons.forEach((season, seasonIndex) => {
                                if (season.Episodes && Array.isArray(season.Episodes)) {
                                    season.Episodes.forEach((episode, episodeIndex) => {
                                        if (!hasEmbedSources(episode)) {
                                            episodesWithoutEmbed.push({
                                                seriesIndex: seriesIndex,
                                                seasonIndex: seasonIndex,
                                                episodeIndex: episodeIndex,
                                                entry: episode,
                                                season: season.Season,
                                                episode: episode.Episode
                                            });
                                        }
                                    });
                                }
                            });
                        }
                        
                        if (episodesWithoutEmbed.length > 0) {
                            seriesGroups[series.Title] = {
                                type: 'series',
                                category: 'TV Series',
                                title: series.Title,
                                episodes: episodesWithoutEmbed
                            };
                        }
                    });
                }
            });
            
            // Create checkboxes for movies
            Object.values(movieGroups).forEach((group, index) => {
                const checkboxItem = createCheckboxItem(`movie_${index}`, group.title, 'Movies', group.entries.length);
                container.appendChild(checkboxItem);
            });
            
            // Create checkboxes for series
            Object.values(seriesGroups).forEach((group, index) => {
                const checkboxItem = createCheckboxItem(`series_${index}`, group.title, 'TV Series', group.episodes.length);
                container.appendChild(checkboxItem);
            });
            
            // Store the grouped content for later use
            window.contentGroups = {
                movies: Object.values(movieGroups),
                series: Object.values(seriesGroups)
            };
            
            console.log('Content groups stored:', window.contentGroups);
            console.log('Movies found:', Object.values(movieGroups).length);
            console.log('Series found:', Object.values(seriesGroups).length);
            
            const totalItems = Object.values(movieGroups).length + Object.values(seriesGroups).length;
            const message = `Found ${totalItems} content items without embed links`;
            showStatus('info', message);
        }

        function createCheckboxItem(value, title, category, count) {
            const item = document.createElement('div');
            item.className = 'content-checkbox-item';
            item.dataset.value = value;
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `checkbox_${value}`;
            checkbox.addEventListener('change', (e) => handleCheckboxChange(e, value, title, category));
            
            const label = document.createElement('label');
            label.className = 'content-checkbox-label';
            label.htmlFor = `checkbox_${value}`;
            label.textContent = `[${category}] ${title} (${count} item${count > 1 ? 's' : ''})`;
            
            const statusDiv = document.createElement('div');
            statusDiv.className = 'tmdb-status';
            statusDiv.id = `status_${value}`;
            
            item.appendChild(checkbox);
            item.appendChild(label);
            item.appendChild(statusDiv);
            
            return item;
        }

        async function handleCheckboxChange(event, value, title, category) {
            const checkbox = event.target;
            const item = checkbox.closest('.content-checkbox-item');
            const statusDiv = document.getElementById(`status_${value}`);
            
            if (checkbox.checked) {
                // Check if we've reached the maximum selection limit
                if (window.selectedContent.size >= MAX_SELECTIONS) {
                    checkbox.checked = false;
                    showStatus('warning', `Maximum ${MAX_SELECTIONS} items can be selected at once`);
                    return;
                }
                
                // Show searching status
                statusDiv.className = 'tmdb-status searching';
                statusDiv.innerHTML = '<div class="loading-spinner"></div><span>Searching TMDB...</span>';
                
                // Disable other checkboxes while searching
                disableUnselectedCheckboxes();
                
                try {
                    // Search for TMDB ID
                    const tmdbId = await searchTMDBForTitle(title, category);
                    
                    if (tmdbId) {
                        // TMDB ID found
                        statusDiv.className = 'tmdb-status found';
                        statusDiv.innerHTML = `<span>✅ TMDB ID: ${tmdbId}</span>`;
                        
                        // Store the selection
                        window.selectedContent.set(value, {
                            title: title,
                            category: category,
                            tmdbId: tmdbId,
                            contentData: getContentData(value)
                        });
                        
                        updateSelectionCounter();
                        updateBulkUpdateButton();
                        
                    } else {
                        // TMDB ID not found - uncheck and show manual input option
                        checkbox.checked = false;
                        statusDiv.className = 'tmdb-status not-found';
                        statusDiv.innerHTML = `
                            <span>❌ Not found</span>
                            <div class="manual-tmdb-input">
                                <input type="number" placeholder="TMDB ID" id="manual_${value}" min="1" onkeypress="if(event.key==='Enter') verifyManualTMDBId('${value}', '${title}', '${category}')">
                                <button class="btn btn-primary btn-verify" onclick="verifyManualTMDBId('${value}', '${title}', '${category}')">✓ Verify</button>
                            </div>
                        `;
                        
                        showStatus('warning', `TMDB ID not found for "${title}". You can enter the TMDB ID manually.`);
                    }
                } catch (error) {
                    // Error occurred - uncheck and show error
                    checkbox.checked = false;
                    statusDiv.className = 'tmdb-status not-found';
                    statusDiv.innerHTML = '<span>❌ Search failed</span>';
                    
                    showStatus('error', `Failed to search TMDB for "${title}": ${error.message}`);
                }
                
                // Re-enable checkboxes
                enableAllCheckboxes();
                
            } else {
                // Unchecked - remove from selection and clear status
                statusDiv.className = 'tmdb-status';
                statusDiv.innerHTML = '';
                window.selectedContent.delete(value);
                updateSelectionCounter();
                updateBulkUpdateButton();
            }
        }

        async function searchTMDBForTitle(title, category) {
            try {
                // Clean the title for better search results
                const cleanTitle = title.replace(/[^\w\s]/g, '').trim();
                const searchType = category === 'Movies' ? 'movie' : 'tv';
                
                console.log(`🔍 Searching TMDB for: "${cleanTitle}" (${searchType})`);
                
                const endpoint = `/search/${searchType}`;
                const results = await fetchTMDB(endpoint, { query: cleanTitle });
                
                if (results && results.results && results.results.length > 0) {
                    // Get the first result (most relevant)
                    const firstResult = results.results[0];
                    const resultTitle = firstResult.title || firstResult.name;
                    
                    console.log(`✅ Found TMDB match: "${resultTitle}" (ID: ${firstResult.id})`);
                    return firstResult.id;
                } else {
                    console.log(`❌ No TMDB results found for: "${cleanTitle}"`);
                    return null;
                }
            } catch (error) {
                console.error('TMDB search error:', error);
                throw error;
            }
        }

        function getContentData(value) {
            const [type, index] = value.split('_');
            return type === 'movie' ? 
                window.contentGroups.movies[parseInt(index)] : 
                window.contentGroups.series[parseInt(index)];
        }

        function disableUnselectedCheckboxes() {
            document.querySelectorAll('.content-checkbox-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (!checkbox.checked) {
                    item.classList.add('disabled');
                    checkbox.disabled = true;
                }
            });
        }

        function enableAllCheckboxes() {
            document.querySelectorAll('.content-checkbox-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                item.classList.remove('disabled');
                checkbox.disabled = false;
            });
        }

        function updateSelectionCounter() {
            const counter = document.getElementById('selection-counter');
            const count = window.selectedContent.size;
            counter.textContent = `Selected: ${count}/${MAX_SELECTIONS}`;
            
            if (count >= MAX_SELECTIONS) {
                counter.style.color = 'var(--warning)';
            } else {
                counter.style.color = 'var(--accent)';
            }
        }

        function updateBulkUpdateButton() {
            const button = document.getElementById('bulk-update-btn');
            const count = window.selectedContent.size;
            
            if (count > 0) {
                button.disabled = false;
                button.textContent = `🚀 Update ${count} Selected Item${count > 1 ? 's' : ''} with TMDB Metadata & Sources`;
            } else {
                button.disabled = true;
                button.textContent = '🚀 Update Selected Content with TMDB Metadata & Sources';
            }
        }

        // Helper: merge and deduplicate servers by URL while preserving existing links
        function dedupeServers(servers) {
            if (!Array.isArray(servers)) return [];
            const seen = new Set();
            const out = [];
            for (const srv of servers) {
                if (!srv || !srv.url) continue;
                const key = srv.url.trim().toLowerCase();
                if (!seen.has(key)) {
                    seen.add(key);
                    out.push({ name: srv.name, url: srv.url });
                }
            }
            return out;
        }

        function clearAllSelections() {
            // Uncheck all checkboxes
            document.querySelectorAll('.content-checkbox-item input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Clear status indicators
            document.querySelectorAll('.tmdb-status').forEach(status => {
                status.className = 'tmdb-status';
                status.innerHTML = '';
            });
            
            // Clear selections
            window.selectedContent.clear();
            updateSelectionCounter();
            updateBulkUpdateButton();
            
            showStatus('info', 'All selections cleared');
        }

        async function verifyManualTMDBId(value, title, category) {
            const input = document.getElementById(`manual_${value}`);
            const tmdbId = parseInt(input.value);
            const statusDiv = document.getElementById(`status_${value}`);
            const checkbox = document.getElementById(`checkbox_${value}`);
            
            if (!tmdbId || tmdbId < 1) {
                showStatus('warning', 'Please enter a valid TMDB ID (positive number)');
                input.focus();
                return;
            }
            
            // Check if we've reached the maximum selection limit
            if (window.selectedContent.size >= MAX_SELECTIONS) {
                showStatus('warning', `Maximum ${MAX_SELECTIONS} items can be selected at once`);
                return;
            }
            
            // Show verifying status
            statusDiv.className = 'tmdb-status searching';
            statusDiv.innerHTML = '<div class="loading-spinner"></div><span>Verifying TMDB ID...</span>';
            
            try {
                // Verify the TMDB ID exists by fetching its details
                const searchType = category === 'Movies' ? 'movie' : 'tv';
                const tmdbData = await fetchTMDB(`/${searchType}/${tmdbId}`);
                
                if (tmdbData && (tmdbData.title || tmdbData.name)) {
                    // TMDB ID is valid
                    const tmdbTitle = tmdbData.title || tmdbData.name;
                    statusDiv.className = 'tmdb-status manual-entry';
                    statusDiv.innerHTML = `<span>✅ Manual: ${tmdbId} (${tmdbTitle})</span>`;
                    
                    // Check the checkbox and store the selection
                    checkbox.checked = true;
                    window.selectedContent.set(value, {
                        title: title,
                        category: category,
                        tmdbId: tmdbId,
                        contentData: getContentData(value),
                        isManualEntry: true,
                        verifiedTitle: tmdbTitle
                    });
                    
                    updateSelectionCounter();
                    updateBulkUpdateButton();
                    
                    showStatus('success', `✅ TMDB ID ${tmdbId} verified for "${title}" (matches: "${tmdbTitle}")`);
                    
                } else {
                    // TMDB ID doesn't exist
                    statusDiv.className = 'tmdb-status not-found';
                    statusDiv.innerHTML = `
                        <span>❌ Invalid ID</span>
                        <div class="manual-tmdb-input">
                            <input type="number" placeholder="TMDB ID" id="manual_${value}" min="1" value="${tmdbId}" onkeypress="if(event.key==='Enter') verifyManualTMDBId('${value}', '${title}', '${category}')">
                            <button class="btn btn-primary btn-verify" onclick="verifyManualTMDBId('${value}', '${title}', '${category}')">✓ Verify</button>
                        </div>
                    `;
                    
                    showStatus('error', `TMDB ID ${tmdbId} does not exist. Please check the ID and try again.`);
                }
                
            } catch (error) {
                // Error occurred during verification
                statusDiv.className = 'tmdb-status not-found';
                statusDiv.innerHTML = `
                    <span>❌ Verify failed</span>
                    <div class="manual-tmdb-input">
                        <input type="number" placeholder="TMDB ID" id="manual_${value}" min="1" value="${tmdbId}" onkeypress="if(event.key==='Enter') verifyManualTMDBId('${value}', '${title}', '${category}')">
                        <button class="btn btn-primary btn-verify" onclick="verifyManualTMDBId('${value}', '${title}', '${category}')">✓ Verify</button>
                    </div>
                `;
                
                showStatus('error', `Failed to verify TMDB ID ${tmdbId}: ${error.message}`);
            }
        }

        async function bulkUpdateSelectedContent() {
            if (window.selectedContent.size === 0) {
                showStatus('warning', 'No content selected for update');
                return;
            }

            const button = document.getElementById('bulk-update-btn');
            const progressBar = document.getElementById('bulk-update-progress');
            const progressFill = document.getElementById('bulk-update-progress-fill');
            const statusDiv = document.getElementById('bulk-update-status');
            
            // Disable button and show progress
            button.disabled = true;
            progressBar.style.display = 'block';
            statusDiv.style.display = 'block';
            statusDiv.className = 'status info';
            
            const totalItems = window.selectedContent.size;
            let processed = 0;
            let successful = 0;
            let failed = 0;
            
            statusDiv.textContent = `Processing 0/${totalItems} items...`;
            
            try {
                for (const [key, contentInfo] of window.selectedContent.entries()) {
                    try {
                        // Update progress
                        const progressPercent = (processed / totalItems) * 100;
                        progressFill.style.width = `${progressPercent}%`;
                        statusDiv.textContent = `Processing ${processed + 1}/${totalItems}: ${contentInfo.title} (updating metadata & sources)`;
                        
                        // Apply auto-embed to the content using the found TMDB ID
                        await applyAutoEmbedToContentGroup(contentInfo.contentData, contentInfo.tmdbId);
                        
                        successful++;
                        const entryType = contentInfo.isManualEntry ? 'Manual' : 'Auto';
                        const verifiedInfo = contentInfo.verifiedTitle ? ` → ${contentInfo.verifiedTitle}` : '';
                        console.log(`✅ Successfully updated: ${contentInfo.title} (${entryType} TMDB ID: ${contentInfo.tmdbId}${verifiedInfo})`);
                        
                    } catch (error) {
                        failed++;
                        console.error(`❌ Failed to update: ${contentInfo.title}`, error);
                    }
                    
                    processed++;
                    
                    // Small delay to prevent overwhelming the system
                    await new Promise(resolve => setTimeout(resolve, 100));
                }
                
                // Complete progress
                progressFill.style.width = '100%';
                
                // Show final status
                if (failed === 0) {
                    statusDiv.className = 'status success';
                    statusDiv.textContent = `✅ Successfully updated ${successful} items with TMDB metadata & auto-embed sources!`;
                    showStatus('success', `Bulk update completed! ${successful} items updated with full TMDB metadata and sources.`);
                } else {
                    statusDiv.className = 'status warning';
                    statusDiv.textContent = `⚠️ Update completed with ${successful} successful and ${failed} failed items.`;
                    showStatus('warning', `Bulk update completed with some errors. ${successful} successful, ${failed} failed.`);
                }
                
                // Clear selections and refresh
                clearAllSelections();
                await saveData();
                updateDataStats();
                updatePreview();
                
                // Hide progress after delay
                setTimeout(() => {
                    progressBar.style.display = 'none';
                    statusDiv.style.display = 'none';
                    button.disabled = false;
                }, 3000);
                
            } catch (error) {
                console.error('Bulk update error:', error);
                statusDiv.className = 'status error';
                statusDiv.textContent = `❌ Bulk update failed: ${error.message}`;
                showStatus('error', `Bulk update failed: ${error.message}`);
                
                // Re-enable button
                button.disabled = false;
                progressBar.style.display = 'none';
            }
        }

        async function applyAutoEmbedToContentGroup(contentGroup, tmdbId) {
            const config = getAutoEmbedConfig();
            
            if (contentGroup.type === 'movie') {
                // Fetch movie metadata from TMDB
                const movieData = await fetchTMDB(`/movie/${tmdbId}`);
                const credits = await fetchTMDB(`/movie/${tmdbId}/credits`);
                
                if (movieData) {
                    // Process movie entries
                    for (const movieEntry of contentGroup.entries) {
                        const movie = movieEntry.entry;
                        
                        // Update metadata from TMDB
                        await updateMovieMetadata(movie, movieData, credits);
                        
                        // Add auto-embed sources
                        const autoSources = generateEmbedSources(tmdbId, 'movie');
                        autoSources.forEach(source => {
                            movie.Servers = movie.Servers || [];
                            movie.Servers = dedupeServers([
                                ...movie.Servers,
                                {
                                    name: source.name || source.title,
                                    url: source.url
                                }
                            ]);
                        });
                    }
                }
            } else if (contentGroup.type === 'series') {
                // Fetch series metadata from TMDB
                const seriesData = await fetchTMDB(`/tv/${tmdbId}`);
                const credits = await fetchTMDB(`/tv/${tmdbId}/credits`);
                
                if (seriesData) {
                    // Update series-level metadata first
                    const seriesTitle = contentGroup.title;
                    const seriesCategory = currentData.Categories.find(cat => cat.MainCategory === "TV Series");
                    const seriesEntry = seriesCategory?.Entries.find(entry => entry.Title === seriesTitle);
                    
                    if (seriesEntry) {
                        await updateSeriesMetadata(seriesEntry, seriesData, credits);

                        // NEW: Detect and generate any missing seasons/episodes for this series
                        try {
                            const allSeasons = seriesData.seasons || [];
                            const existingSeasons = new Set(seriesEntry.Seasons?.map(s => s.Season) || []);
                            let generatedCount = 0;

                            for (const tmdbSeason of allSeasons) {
                                const seasonNumber = tmdbSeason.season_number;
                                if (!existingSeasons.has(seasonNumber)) {
                                    const newSeason = await generateMissingSeason(tmdbId, seasonNumber, seriesEntry);
                                    if (newSeason) {
                                        if (!seriesEntry.Seasons) seriesEntry.Seasons = [];
                                        seriesEntry.Seasons.push(newSeason);
                                        generatedCount++;
                                    }
                                } else {
                                    const existingSeason = seriesEntry.Seasons.find(s => s.Season === seasonNumber);
                                    if (existingSeason) {
                                        const seasonData = await fetchTMDB(`/tv/${tmdbId}/season/${seasonNumber}`);
                                        if (seasonData) {
                                            const allEpisodes = seasonData.episodes || [];
                                            const existingEpisodes = new Set(existingSeason.Episodes?.map(e => e.Episode) || []);
                                            for (const tmdbEpisode of allEpisodes) {
                                                if (!existingEpisodes.has(tmdbEpisode.episode_number)) {
                                                    const newEpisode = await generateMissingEpisode(tmdbId, seasonNumber, tmdbEpisode, seriesEntry);
                                                    if (newEpisode) {
                                                        if (!existingSeason.Episodes) existingSeason.Episodes = [];
                                                        existingSeason.Episodes.push(newEpisode);
                                                        // Keep episodes ordered
                                                        existingSeason.Episodes.sort((a, b) => a.Episode - b.Episode);
                                                        generatedCount++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            // Ensure seasons are ordered
                            if (seriesEntry.Seasons) {
                                seriesEntry.Seasons.sort((a, b) => a.Season - b.Season);
                            }

                            if (generatedCount > 0) {
                                console.log(`🆕 Generated ${generatedCount} missing seasons/episodes for ${seriesEntry.Title}`);
                            }
                        } catch (genErr) {
                            console.warn(`Failed generating missing content for ${seriesEntry.Title}:`, genErr);
                        }
                    }
                    
                    // Process series episodes
                    const processedSeasons = new Set();
                    
                    for (const episodeInfo of contentGroup.episodes) {
                        const episode = episodeInfo.entry;
                        const seasonNum = episodeInfo.season;
                        const episodeNum = episodeInfo.episode;
                        
                        // Fetch season data if not already processed
                        if (!processedSeasons.has(seasonNum)) {
                            const seasonData = await fetchTMDB(`/tv/${tmdbId}/season/${seasonNum}`);
                            if (seasonData && seriesEntry) {
                                await updateSeasonMetadata(seriesEntry, seasonNum, seasonData);
                            }
                            processedSeasons.add(seasonNum);
                        }
                        
                        // Fetch episode-specific data
                        const episodeData = await fetchTMDB(`/tv/${tmdbId}/season/${seasonNum}/episode/${episodeNum}`);
                        if (episodeData) {
                            await updateEpisodeMetadata(episode, episodeData);
                        }
                        
                        // Add auto-embed sources
                        const autoSources = generateEmbedSources(tmdbId, 'tv', seasonNum, episodeNum);
                        autoSources.forEach(source => {
                            episode.Servers = episode.Servers || [];
                            episode.Servers = dedupeServers([
                                ...episode.Servers,
                                {
                                    name: source.name || source.title,
                                    url: source.url
                                }
                            ]);
                        });
                    }
                }
            }
            
            console.log(`✅ Applied auto-embed sources and updated metadata for ${contentGroup.title} (TMDB ID: ${tmdbId})`);
        }

        async function updateMovieMetadata(movie, movieData, credits) {
            try {
                // Update basic metadata
                if (movieData.overview) movie.Description = movieData.overview;
                if (movieData.poster_path) {
                    movie.Poster = `${TMDB_IMAGE_BASE}${movieData.poster_path}`;
                    movie.Thumbnail = `${TMDB_IMAGE_BASE}${movieData.poster_path}`;
                }
                if (movieData.vote_average) movie.Rating = Math.round(movieData.vote_average);
                if (movieData.runtime) movie.Duration = formatDuration(movieData.runtime);
                if (movieData.release_date) movie.Year = parseInt(movieData.release_date.substring(0, 4));
                
                // Update country (production countries)
                if (movieData.production_countries && movieData.production_countries.length > 0) {
                    movie.Country = movieData.production_countries.map(c => c.name).join(', ');
                }
                
                // Update subcategory based on primary genre
                if (movieData.genres && movieData.genres.length > 0) {
                    const primaryGenre = movieData.genres[0].name;
                    movie.SubCategory = primaryGenre;
                    
                    // Update category subcategories list
                    const moviesCategory = currentData.Categories.find(cat => cat.MainCategory === "Movies");
                    if (moviesCategory && !moviesCategory.SubCategories.includes(primaryGenre)) {
                        moviesCategory.SubCategories.push(primaryGenre);
                    }
                }
                
                console.log(`📝 Updated movie metadata: ${movie.Title}`);
            } catch (error) {
                console.error(`⚠️ Error updating movie metadata for ${movie.Title}:`, error);
            }
        }

        async function updateSeriesMetadata(seriesEntry, seriesData, credits) {
            try {
                // Update basic metadata
                if (seriesData.overview) seriesEntry.Description = seriesData.overview;
                if (seriesData.poster_path) {
                    seriesEntry.Poster = `${TMDB_IMAGE_BASE}${seriesData.poster_path}`;
                    seriesEntry.Thumbnail = `${TMDB_IMAGE_BASE}${seriesData.poster_path}`;
                }
                if (seriesData.vote_average) seriesEntry.Rating = Math.round(seriesData.vote_average);
                if (seriesData.first_air_date) seriesEntry.Year = parseInt(seriesData.first_air_date.substring(0, 4));
                
                // Update country (origin countries)
                if (seriesData.origin_country && seriesData.origin_country.length > 0) {
                    seriesEntry.Country = seriesData.origin_country.join(', ');
                }
                
                // Update subcategory based on primary genre
                if (seriesData.genres && seriesData.genres.length > 0) {
                    const primaryGenre = seriesData.genres[0].name;
                    seriesEntry.SubCategory = primaryGenre;
                    
                    // Update category subcategories list
                    const seriesCategory = currentData.Categories.find(cat => cat.MainCategory === "TV Series");
                    if (seriesCategory && !seriesCategory.SubCategories.includes(primaryGenre)) {
                        seriesCategory.SubCategories.push(primaryGenre);
                    }
                }
                
                console.log(`📺 Updated series metadata: ${seriesEntry.Title}`);
            } catch (error) {
                console.error(`⚠️ Error updating series metadata for ${seriesEntry.Title}:`, error);
            }
        }

        async function updateSeasonMetadata(seriesEntry, seasonNum, seasonData) {
            try {
                if (!seriesEntry.Seasons) return;
                
                const season = seriesEntry.Seasons.find(s => s.Season === seasonNum);
                if (season && seasonData.poster_path) {
                    season.SeasonPoster = `${TMDB_IMAGE_BASE}${seasonData.poster_path}`;
                }
                
                console.log(`🎭 Updated season ${seasonNum} metadata for: ${seriesEntry.Title}`);
            } catch (error) {
                console.error(`⚠️ Error updating season ${seasonNum} metadata:`, error);
            }
        }

        async function updateEpisodeMetadata(episode, episodeData) {
            try {
                // Update episode metadata
                if (episodeData.name && episodeData.name !== `Episode ${episodeData.episode_number}`) {
                    episode.Title = episodeData.name;
                }
                if (episodeData.overview) episode.Description = episodeData.overview;
                if (episodeData.runtime) episode.Duration = formatDuration(episodeData.runtime);
                if (episodeData.still_path) {
                    episode.Thumbnail = `${TMDB_IMAGE_BASE}${episodeData.still_path}`;
                }
                
                console.log(`📝 Updated episode metadata: S${episodeData.season_number}E${episodeData.episode_number} - ${episode.Title}`);
            } catch (error) {
                console.error(`⚠️ Error updating episode metadata:`, error);
            }
        }
        
        function updateSelectedContentInfo() {
            const select = document.getElementById('auto-embed-content-select');
            const infoDiv = document.getElementById('selected-content-info');
            
            if (!select.value || !window.contentGroups) {
                infoDiv.style.display = 'none';
                return;
            }
            
            const [type, index] = select.value.split('_');
            const selectedGroup = type === 'movie' ? 
                window.contentGroups.movies[parseInt(index)] : 
                window.contentGroups.series[parseInt(index)];
            
            if (selectedGroup) {
                infoDiv.style.display = 'block';
                infoDiv.className = 'status info';
                
                let totalCurrentServers = 0;
                let totalEmbedServers = 0;
                
                if (selectedGroup.type === 'movie') {
                    selectedGroup.entries.forEach(item => {
                        const servers = item.entry.Servers || [];
                        totalCurrentServers += servers.length;
                        totalEmbedServers += servers.filter(s => 
                            s.url && (s.url.includes('vidsrc.net/embed') || 
                                     s.url.includes('vidjoy.pro/embed') || 
                                     s.url.includes('multiembed.mov'))
                        ).length;
                    });
                } else if (selectedGroup.type === 'series') {
                    selectedGroup.episodes.forEach(item => {
                        const servers = item.entry.Servers || [];
                        totalCurrentServers += servers.length;
                        totalEmbedServers += servers.filter(s => 
                            s.url && (s.url.includes('vidsrc.net/embed') || 
                                     s.url.includes('vidjoy.pro/embed') || 
                                     s.url.includes('multiembed.mov'))
                        ).length;
                    });
                }
                
                const embedSourcesToAdd = getAutoEmbedConfig().vidsrc.enabled + getAutoEmbedConfig().vidjoy.enabled + getAutoEmbedConfig().multiembed.enabled;
                const totalItems = selectedGroup.type === 'movie' ? selectedGroup.entries.length : selectedGroup.episodes.length;
                const totalEmbedSourcesToAdd = totalItems * embedSourcesToAdd;
                
                const tmdbIdInput = document.getElementById('selected-tmdb-id');
                const hasExistingTmdbId = selectedGroup.type === 'movie' ? 
                    selectedGroup.entries[0]?.entry.TMDB_ID || selectedGroup.entries[0]?.entry.tmdb_id :
                    selectedGroup.episodes[0]?.entry.TMDB_ID || selectedGroup.episodes[0]?.entry.tmdb_id;
                
                infoDiv.innerHTML = `
                    <strong>Selected:</strong> ${selectedGroup.title}<br>
                    <strong>Type:</strong> ${selectedGroup.type === 'movie' ? 'Movie' : 'TV Series'}<br>
                    <strong>Items:</strong> ${totalItems} ${selectedGroup.type === 'movie' ? 'movie(s)' : 'episode(s)'}<br>
                    <strong>Current Servers:</strong> ${totalCurrentServers}<br>
                    <strong>Embed Servers:</strong> ${totalEmbedServers}<br>
                    <strong>Will Add:</strong> ${totalEmbedSourcesToAdd} embed sources (${embedSourcesToAdd} per item)<br>
                    <strong>TMDB ID:</strong> ${hasExistingTmdbId ? hasExistingTmdbId : 'None'} ${tmdbIdInput.value ? `(Will use: ${tmdbIdInput.value})` : ''}
                `;
            }
        }
        async function bulkGenerateRegional() {
            const region = document.getElementById('bulk-regional-select').value;
            const contentType = document.getElementById('bulk-regional-content-type').value;
            const yearRange = document.getElementById('bulk-regional-year-select').value;
            const pages = parseInt(document.getElementById('bulk-regional-pages').value);
            const skipDuplicates = document.getElementById('bulk-regional-skip-duplicates').checked;

            showLoading('bulk-regional-loading', true);
            const statusDiv = document.getElementById('bulk-regional-status');
            const progressBar = document.getElementById('bulk-regional-progress');
            
            statusDiv.innerHTML = '<div class="status info">Starting regional bulk generation...</div>';
            progressBar.style.width = '0%';

            let generated = 0;
            let skipped = 0;
            let totalFetched = 0;

            const years = [];
            if (yearRange === 'all-recent') {
                for (let y = 2025; y >= 2020; y--) years.push(y);
            } else if (yearRange === 'all-2010s') {
                for (let y = 2019; y >= 2010; y--) years.push(y);
            } else if (yearRange === 'all-2000s') {
                for (let y = 2009; y >= 2000; y--) years.push(y);
            } else if (yearRange === 'all-classic') {
                for (let y = 1999; y >= 1990; y--) years.push(y);
            } else if (yearRange === 'all-time') {
                 for (let y = 2025; y >= 1990; y--) years.push(y);
            } else if (yearRange) {
                years.push(parseInt(yearRange));
            }

            const totalSteps = (years.length > 0 ? years.length : 1) * pages;
            let currentStep = 0;

            const processYear = async (y) => {
                for (let page = 1; page <= pages; page++) {
                    currentStep++;
                    const progress = (currentStep / totalSteps) * 100;
                    progressBar.style.width = `${progress}%`;
                    statusDiv.innerHTML = `<div class="status info">Processing Year: ${y || 'Any'}, Page: ${page}/${pages} - Generated: ${generated}, Skipped: ${skipped}</div>`;

                    const contentTypesToFetch = contentType === 'both' ? ['movie', 'tv'] : [contentType];
                    for (const type of contentTypesToFetch) {
                        const params = {
                            with_origin_country: REGIONAL_CONFIGS[region].origin_country,
                            with_original_language: REGIONAL_CONFIGS[region].language,
                            page: page,
                            sort_by: 'popularity.desc'
                        };
                        if (y) {
                            if (type === 'movie') params.primary_release_year = y;
                            else params.first_air_date_year = y;
                        }
                        if (REGIONAL_CONFIGS[region].genres.length > 0) {
                            params.with_genres = REGIONAL_CONFIGS[region].genres.join(',');
                        }

                        const data = await fetchTMDB(`/discover/${type}`, params);
                        if (!data?.results) continue;

                        totalFetched += data.results.length;

                        for (const item of data.results) {
                            if (skipDuplicates && isDuplicateByTitle(item.title || item.name, type)) {
                                skipped++;
                                continue;
                            }
                            try {
                                if (type === 'movie') {
                                    await generateMovie(item.id);
                                } else {
                                    await generateSeries(item.id);
                                }
                                generated++;
                            } catch (error) {
                                console.error(`Error generating ${type} ${item.id}:`, error);
                            }
                        }
                    }
                    await new Promise(resolve => setTimeout(resolve, 200)); // API rate limiting
                }
            };

            if (years.length > 0) {
                for (const y of years) {
                    await processYear(y);
                }
            } else {
                await processYear(null); // For "Any Year"
            }

            showLoading('bulk-regional-loading', false);
            statusDiv.innerHTML = `<div class="status success">Regional bulk generation complete! Fetched: ${totalFetched}, Generated: ${generated}, Skipped: ${skipped}</div>`;
            updateDataStats();
            updatePreview();
        }

        async function applyAutoEmbedToSelected() {
            const select = document.getElementById('auto-embed-content-select');
            const tmdbIdInput = document.getElementById('selected-tmdb-id');
            
            if (!select.value || !window.contentGroups) {
                showStatus('warning', 'Please select content to apply auto-embed to');
                return;
            }
            
            const [type, index] = select.value.split('_');
            const selectedGroup = type === 'movie' ? 
                window.contentGroups.movies[parseInt(index)] : 
                window.contentGroups.series[parseInt(index)];
            
            if (!selectedGroup) {
                showStatus('error', 'Selected content not found');
                return;
            }
            
            // Get TMDB ID from input or existing entry
            let tmdbId = tmdbIdInput.value.trim();
            if (!tmdbId) {
                // Use existing TMDB ID from first item
                if (selectedGroup.type === 'movie') {
                    tmdbId = selectedGroup.entries[0]?.entry.TMDB_ID || selectedGroup.entries[0]?.entry.tmdb_id;
                } else {
                    tmdbId = selectedGroup.episodes[0]?.entry.TMDB_ID || selectedGroup.episodes[0]?.entry.tmdb_id;
                }
            }
            
            if (!tmdbId) {
                showStatus('error', 'No TMDB ID found. Please enter a TMDB ID or ensure content has TMDB ID.');
                return;
            }
            
            let processedItems = 0;
            let totalEmbedSourcesAdded = 0;
            let updatedInfo = false;
            
            try {
                if (selectedGroup.type === 'movie') {
                    // Fetch fresh movie data from TMDB if new ID provided
                    let movieData = null;
                    if (tmdbIdInput.value.trim()) {
                        showStatus('info', 'Fetching fresh data from TMDB...');
                        movieData = await fetchTMDB(`/movie/${tmdbId}`);
                        if (movieData) {
                            updatedInfo = true;
                        }
                    }
                    
                    // Process all movies with the same title
                    for (const item of selectedGroup.entries) {
                        // Update info if fresh data was fetched
                        if (movieData) {
                            item.entry.Title = movieData.title;
                            item.entry.Description = movieData.overview || item.entry.Description;
                            item.entry.Poster = movieData.poster_path ? `${TMDB_IMAGE_BASE}${movieData.poster_path}` : item.entry.Poster;
                            item.entry.Thumbnail = movieData.backdrop_path ? `${TMDB_IMAGE_BASE}${movieData.backdrop_path}` : item.entry.Thumbnail;
                            item.entry.Rating = Math.round(movieData.vote_average || item.entry.Rating);
                            item.entry.Year = parseInt(movieData.release_date?.substring(0, 4) || item.entry.Year);
                            item.entry.Duration = formatDuration(movieData.runtime) || item.entry.Duration;
                        }
                        
                        // Generate embed sources
                        
                        // Generate embed sources
                        const embedSources = generateEmbedSources(tmdbId, 'movie');
                        
                        // Preserve existing servers and add embed sources
                        if (!item.entry.Servers) item.entry.Servers = [];
                        item.entry.Servers = dedupeServers([...item.entry.Servers, ...embedSources]);
                        
                        // Update the actual data structure
                        const moviesCategory = currentData.Categories.find(c => c.MainCategory === "Movies");
                        if (moviesCategory && moviesCategory.Entries[item.index]) {
                            moviesCategory.Entries[item.index] = item.entry;
                        }
                        
                        processedItems++;
                        totalEmbedSourcesAdded += embedSources.length;
                    }
                } else if (selectedGroup.type === 'series') {
                    // Fetch fresh series data from TMDB if new ID provided
                    let seriesData = null;
                    if (tmdbIdInput.value.trim()) {
                        showStatus('info', 'Fetching fresh data from TMDB...');
                        seriesData = await fetchTMDB(`/tv/${tmdbId}`);
                        if (seriesData) {
                            updatedInfo = true;
                        }
                    }
                    
                    // Process all episodes of the series
                    for (const item of selectedGroup.episodes) {
                        // Update episode info if fresh data was fetched
                        if (seriesData) {
                            // Update series-level info
                            const seriesCategory = currentData.Categories.find(c => c.MainCategory === "TV Series");
                            if (seriesCategory && seriesCategory.Entries[item.seriesIndex]) {
                                const series = seriesCategory.Entries[item.seriesIndex];
                                series.Title = seriesData.name;
                                series.Description = seriesData.overview || series.Description;
                                series.Poster = seriesData.poster_path ? `${TMDB_IMAGE_BASE}${seriesData.poster_path}` : series.Poster;
                                series.Thumbnail = seriesData.backdrop_path ? `${TMDB_IMAGE_BASE}${seriesData.backdrop_path}` : series.Thumbnail;
                                series.Rating = Math.round(seriesData.vote_average || series.Rating);
                                series.Year = parseInt(seriesData.first_air_date?.substring(0, 4) || series.Year);
                            }
                            
                            // Fetch individual episode data for episode-specific images
                            try {
                                const episodeData = await fetchTMDB(`/tv/${tmdbId}/season/${item.season}/episode/${item.episode}`);
                                if (episodeData) {
                                    // Update episode-specific info
                                    item.entry.Title = episodeData.name || item.entry.Title;
                                    item.entry.Description = episodeData.overview || item.entry.Description;
                                    item.entry.Thumbnail = episodeData.still_path ? `${TMDB_IMAGE_BASE}${episodeData.still_path}` : item.entry.Thumbnail;
                                    item.entry.Duration = formatDuration(episodeData.runtime) || item.entry.Duration;
                                }
                            } catch (error) {
                                console.log(`Could not fetch episode ${item.season}x${item.episode} data:`, error.message);
                            }
                        }
                        
                        // Generate embed sources for episode
                        const embedSources = generateEmbedSources(tmdbId, 'tv', item.season, item.episode);
                        
                        // Preserve existing servers and add embed sources
                        if (!item.entry.Servers) item.entry.Servers = [];
                        item.entry.Servers = dedupeServers([...item.entry.Servers, ...embedSources]);
                        
                        // Update the actual data structure
                        const seriesCategory = currentData.Categories.find(c => c.MainCategory === "TV Series");
                        if (seriesCategory && seriesCategory.Entries[item.seriesIndex]) {
                            const series = seriesCategory.Entries[item.seriesIndex];
                            if (series.Seasons && series.Seasons[item.seasonIndex]) {
                                const season = series.Seasons[item.seasonIndex];
                                if (season.Episodes && season.Episodes[item.episodeIndex]) {
                                    season.Episodes[item.episodeIndex] = item.entry;
                                }
                            }
                        }
                        
                        processedItems++;
                        totalEmbedSourcesAdded += embedSources.length;
                    }
                }
                
                if (processedItems > 0) {
                    const infoMessage = updatedInfo ? 
                        `Updated info from TMDB and applied auto-embed to "${selectedGroup.title}" - processed ${processedItems} items, added ${totalEmbedSourcesAdded} embed sources` :
                        `Applied auto-embed to "${selectedGroup.title}" - processed ${processedItems} items, added ${totalEmbedSourcesAdded} embed sources`;
                    
                    showStatus('success', infoMessage);
                    saveData();
                    updateDataStats();
                    
                    // Clear the TMDB ID input
                    tmdbIdInput.value = '';
                    
                    // Refresh the checkboxes to remove the processed group
                    refreshContentCheckboxes();
                } else {
                    showStatus('error', 'No items were processed.');
                }
            } catch (error) {
                showStatus('error', `Error processing content: ${error.message}`);
            }
        }
