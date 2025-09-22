<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraze - Movie Streaming</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/shaka-player@4.3.0/dist/shaka-player.compiled.js"></script>
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
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff6b6b;
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 0.5rem 1rem;
            color: white;
            width: 300px;
        }

        .profile-btn {
            background: #ff6b6b;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-btn:hover {
            background: #ff5252;
            transform: scale(1.1);
        }

        .main-content {
            margin-top: 80px;
            padding: 2rem;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .movie-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .movie-poster {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .movie-info {
            padding: 1.5rem;
        }

        .movie-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        .movie-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #b0b0b0;
        }

        .movie-rating {
            background: #ff6b6b;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            font-weight: 500;
        }

        .movie-description {
            font-size: 0.9rem;
            color: #d0d0d0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            overflow-y: auto;
        }

        .modal-content {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            margin: 2rem auto;
            padding: 0;
            border-radius: 20px;
            max-width: 1200px;
            position: relative;
            overflow: hidden;
        }

        .modal-header {
            position: relative;
            height: 400px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: end;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        }

        .modal-info {
            position: relative;
            z-index: 1;
            padding: 2rem;
            color: white;
        }

        .modal-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .modal-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: #d0d0d0;
        }

        .interaction-bar {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .interaction-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .interaction-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .interaction-btn.liked {
            background: #4CAF50;
            border-color: #4CAF50;
        }

        .interaction-btn.disliked {
            background: #f44336;
            border-color: #f44336;
        }

        .server-selector {
            margin-bottom: 2rem;
        }

        .server-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .server-tab {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .server-tab.active {
            background: #ff6b6b;
        }

        .video-container {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .video-player {
            width: 100%;
            height: 500px;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            z-index: 2;
        }

        .admin-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 3000;
        }

        .admin-content {
            background: #1a1a2e;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            position: relative;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #d0d0d0;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
        }

        .btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #ff5252;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #b0b0b0;
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .search-box {
                width: 200px;
            }

            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }

            .modal-content {
                margin: 1rem;
            }

            .modal-title {
                font-size: 2rem;
            }

            .interaction-bar {
                flex-wrap: wrap;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">CineCraze</div>
        <div class="nav-controls">
            <input type="text" class="search-box" placeholder="Search movies..." id="searchInput">
            <button class="profile-btn" onclick="openAdminPanel()">
                <i class="fas fa-user"></i>
            </button>
        </div>
    </header>

    <main class="main-content">
        <div id="movieGrid" class="movie-grid">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading movies...</p>
            </div>
        </div>
    </main>

    <!-- Movie Modal -->
    <div id="movieModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">&times;</button>
            <div class="modal-header" id="modalHeader">
                <div class="modal-info">
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <div class="modal-meta" id="modalMeta"></div>
                </div>
            </div>
            <div class="modal-body">
                <p class="modal-description" id="modalDescription"></p>
                
                <div class="interaction-bar">
                    <button class="interaction-btn" id="likeBtn" onclick="interactMovie('like')">
                        <i class="fas fa-thumbs-up"></i>
                        <span id="likeCount">0</span>
                    </button>
                    <button class="interaction-btn" id="dislikeBtn" onclick="interactMovie('dislike')">
                        <i class="fas fa-thumbs-down"></i>
                        <span id="dislikeCount">0</span>
                    </button>
                    <button class="interaction-btn" onclick="shareMovie()">
                        <i class="fas fa-share"></i>
                        Share
                    </button>
                    <div style="margin-left: auto; color: #b0b0b0;">
                        <i class="fas fa-eye"></i>
                        <span id="viewCount">0</span> views
                    </div>
                </div>

                <div class="server-selector">
                    <div class="server-tabs" id="serverTabs"></div>
                    <div class="video-container">
                        <div id="videoPlayer" class="video-player"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Modal -->
    <div id="adminModal" class="admin-modal">
        <div class="admin-content">
            <button class="close-btn" onclick="closeAdminModal()">&times;</button>
            <h2>Admin Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>

    <script>
        let movies = [];
        let currentMovie = null;
        let currentServers = [];
        let currentPlayer = null;

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            loadMovies();
            setupSearch();
        });

        // Load movies from API
        async function loadMovies() {
            try {
                const response = await fetch('api/movies.php?action=list');
                movies = await response.json();
                renderMovies(movies);
            } catch (error) {
                console.error('Error loading movies:', error);
                document.getElementById('movieGrid').innerHTML = '<div class="loading">Error loading movies</div>';
            }
        }

        // Render movies grid
        function renderMovies(movieList) {
            const grid = document.getElementById('movieGrid');
            
            if (movieList.length === 0) {
                grid.innerHTML = '<div class="loading">No movies found</div>';
                return;
            }

            grid.innerHTML = movieList.map(movie => `
                <div class="movie-card" onclick="openMovie(${movie.id})">
                    <img src="${movie.poster}" alt="${movie.title}" class="movie-poster" onerror="this.src='https://via.placeholder.com/250x350?text=No+Image'">
                    <div class="movie-info">
                        <h3 class="movie-title">${movie.title}</h3>
                        <div class="movie-meta">
                            <span>${movie.year}</span>
                            <span class="movie-rating">${movie.rating}/10</span>
                        </div>
                        <p class="movie-description">${movie.description || 'No description available'}</p>
                    </div>
                </div>
            `).join('');
        }

        // Setup search functionality
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const filtered = movies.filter(movie => 
                    movie.title.toLowerCase().includes(query) ||
                    movie.description.toLowerCase().includes(query)
                );
                renderMovies(filtered);
            });
        }

        // Open movie modal
        async function openMovie(movieId) {
            try {
                const response = await fetch(`api/movies.php?action=get&id=${movieId}`);
                currentMovie = await response.json();
                currentServers = currentMovie.servers || [];

                // Record view
                await fetch(`api/movies.php?action=interact&id=${movieId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: 'view' })
                });

                // Update modal content
                document.getElementById('modalTitle').textContent = currentMovie.title;
                document.getElementById('modalDescription').textContent = currentMovie.description;
                document.getElementById('modalHeader').style.backgroundImage = `url(${currentMovie.backdrop})`;
                
                document.getElementById('modalMeta').innerHTML = `
                    <span><i class="fas fa-calendar"></i> ${currentMovie.year}</span>
                    <span><i class="fas fa-star"></i> ${currentMovie.rating}/10</span>
                    <span><i class="fas fa-clock"></i> ${currentMovie.duration}</span>
                    <span><i class="fas fa-globe"></i> ${currentMovie.language}</span>
                `;

                // Update interaction counts
                updateInteractionCounts();

                // Setup servers
                setupServers();

                // Show modal
                document.getElementById('movieModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error loading movie:', error);
            }
        }

        // Update interaction counts
        function updateInteractionCounts() {
            document.getElementById('likeCount').textContent = currentMovie.likes || 0;
            document.getElementById('dislikeCount').textContent = currentMovie.dislikes || 0;
            document.getElementById('viewCount').textContent = currentMovie.views || 0;
        }

        // Setup server tabs and player
        function setupServers() {
            const serverTabs = document.getElementById('serverTabs');
            
            if (currentServers.length === 0) {
                serverTabs.innerHTML = '<p>No servers available</p>';
                document.getElementById('videoPlayer').innerHTML = '<p>No video available</p>';
                return;
            }

            serverTabs.innerHTML = currentServers.map((server, index) => `
                <button class="server-tab ${index === 0 ? 'active' : ''}" onclick="selectServer(${index})">
                    ${server.server_name} ${server.quality ? `(${server.quality})` : ''}
                </button>
            `).join('');

            // Load first server by default
            selectServer(0);
        }

        // Select server and load video
        function selectServer(index) {
            // Update active tab
            document.querySelectorAll('.server-tab').forEach((tab, i) => {
                tab.classList.toggle('active', i === index);
            });

            const server = currentServers[index];
            const playerContainer = document.getElementById('videoPlayer');

            // Clear previous player
            if (currentPlayer) {
                currentPlayer.destroy();
                currentPlayer = null;
            }

            playerContainer.innerHTML = '';

            // Load video based on server type
            switch (server.server_type) {
                case 'embed':
                    if (server.embed_enabled) {
                        playerContainer.innerHTML = `<iframe src="${server.server_url}" width="100%" height="500" frameborder="0" allowfullscreen></iframe>`;
                    } else {
                        playerContainer.innerHTML = '<p>Embed server is disabled</p>';
                    }
                    break;
                    
                case 'hls':
                case 'live':
                    // Use Shaka Player for HLS and live streams
                    const video = document.createElement('video');
                    video.controls = true;
                    video.style.width = '100%';
                    video.style.height = '500px';
                    playerContainer.appendChild(video);
                    
                    if (shaka.Player.isBrowserSupported()) {
                        currentPlayer = new shaka.Player(video);
                        currentPlayer.load(server.server_url).catch(error => {
                            console.error('Error loading video:', error);
                            playerContainer.innerHTML = '<p>Error loading video</p>';
                        });
                    } else {
                        // Fallback for browsers that don't support Shaka Player
                        video.src = server.server_url;
                    }
                    break;
                    
                default: // direct
                    const directVideo = document.createElement('video');
                    directVideo.controls = true;
                    directVideo.style.width = '100%';
                    directVideo.style.height = '500px';
                    directVideo.src = server.server_url;
                    playerContainer.appendChild(directVideo);
                    break;
            }
        }

        // Handle movie interactions
        async function interactMovie(type) {
            if (!currentMovie) return;

            try {
                const response = await fetch(`api/movies.php?action=interact&id=${currentMovie.id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type })
                });

                const stats = await response.json();
                currentMovie.likes = stats.likes;
                currentMovie.dislikes = stats.dislikes;
                currentMovie.views = stats.views;
                
                updateInteractionCounts();

                // Update button states
                document.getElementById('likeBtn').classList.toggle('liked', type === 'like');
                document.getElementById('dislikeBtn').classList.toggle('disliked', type === 'dislike');
            } catch (error) {
                console.error('Error recording interaction:', error);
            }
        }

        // Share movie
        function shareMovie() {
            if (!currentMovie) return;
            
            const url = `${window.location.origin}${window.location.pathname}?movie=${currentMovie.id}`;
            
            if (navigator.share) {
                navigator.share({
                    title: currentMovie.title,
                    text: currentMovie.description,
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    alert('Movie link copied to clipboard!');
                });
            }
        }

        // Modal controls
        function closeModal() {
            document.getElementById('movieModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            if (currentPlayer) {
                currentPlayer.destroy();
                currentPlayer = null;
            }
        }

        // Admin panel functions
        function openAdminPanel() {
            document.getElementById('adminModal').style.display = 'block';
        }

        function closeAdminModal() {
            document.getElementById('adminModal').style.display = 'none';
        }

        // Handle admin login
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeAdminModal();
                    window.open('admin.php', '_blank');
                } else {
                    alert('Invalid credentials');
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Login failed');
            }
        });

        // Handle direct movie access via URL
        const urlParams = new URLSearchParams(window.location.search);
        const movieId = urlParams.get('movie');
        if (movieId) {
            setTimeout(() => openMovie(movieId), 1000);
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('movieModal');
            if (e.target === modal) {
                closeModal();
            }
            
            const adminModal = document.getElementById('adminModal');
            if (e.target === adminModal) {
                closeAdminModal();
            }
        });
    </script>
</body>
</html>