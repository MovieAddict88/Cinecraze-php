<?php require_once 'includes/header.php'; ?>

    <!-- Main Content -->
    <main>
        <!-- Carousel -->
        <div class="carousel">
            <div class="carousel-inner" id="carousel-inner">
                <!-- Carousel items will be dynamically added here -->
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn" id="carousel-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn" id="carousel-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="carousel-indicators" id="carousel-indicators">
                <!-- Indicators will be dynamically added here -->
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="section-header">
                <h2 class="section-title">Browse Content</h2>
                <div class="view-toggle">
                    <button class="view-btn active" id="view-toggle-btn">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>

            <div class="mobile-filters-menu">
                <div class="filters-row">
                <div class="filter-group">
                    <label for="genre-filter">Genre</label>
                    <select id="genre-filter" class="filter-select">
                        <option value="all">All Genres</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="year-filter">Year</label>
                    <select id="year-filter" class="filter-select">
                        <option value="all">All Years</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="country-filter">Country</label>
                    <select id="country-filter" class="filter-select">
                        <option value="all">All Countries</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="sort-filter">Sort By</label>
                    <select id="sort-filter" class="filter-select">
                        <option value="newest">Newest First</option>
                        <option value="popular">Most Popular</option>
                        <option value="rating">Highest Rated</option>
                    </select>
                </div>
            </div>
            </div>
        </div>

        <!-- Content Grid/List -->
        <div class="content-container">
            <div class="content-grid" id="content-grid">
                <!-- Content cards will be dynamically added here -->
            </div>

            <div class="content-list" id="content-list">
                <!-- List view will be dynamically added here -->
            </div>

            <div class="content-grid" id="watch-later-grid" style="display: none;">
                <!-- Watch Later items will be dynamically added here -->
            </div>

            <!-- Loading Spinner -->
            <div class="loading-spinner" id="loading-spinner"></div>

            <!-- Progress Bar -->
            <div class="progress-bar-container" id="progress-bar-container" style="display: none;">
                <div class="progress-bar-text" id="progress-bar-text"></div>
                <div class="progress-bar" id="progress-bar"></div>
            </div>

            <!-- Pagination -->
            <div class="pagination-container" id="pagination-container">
                <!-- Pagination buttons will be dynamically added here -->
            </div>
        </div>

        <!-- Viewer Page - YouTube Style -->
        <div class="viewer-page" id="viewer-page">
            <div class="youtube-viewer-container">
                <!-- Back Button -->
                <button class="back-button" id="back-button">
                    <i class="fas fa-arrow-left"></i>
                    <span class="back-text">Back to Browse</span>
                    <span class="mobile-back-hint" style="display: none;">← Swipe or tap</span>
                </button>

                <div class="player-container">
                    <video id="player" playsinline controls crossorigin="anonymous">
                        <!-- Source will be set dynamically -->
                    </video>
                    <div id="player-message-area" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; padding: 20px; background-color: var(--youtube-gray); border-radius: var(--radius);"></div>
                </div>

                <div class="viewer-content">
                    <div class="video-details">
                        <div class="video-info">
                            <h1 class="video-title" id="viewer-title">Movie Title</h1>
                            <div class="video-meta">
                                <div class="video-stats">
                                    <span id="viewer-views"><i class="fas fa-eye"></i> 1.2M views</span>
                                    <span id="viewer-date"><i class="fas fa-calendar"></i> Jan 15, 2023</span>
                                </div>
                                <div class="video-actions">
                                    <div class="like-dislike-container">
                                        <div class="icons-box">
                                            <label class="icons">
                                                <span class="btn-label">
                                                    <input class="input-box" type="checkbox" id="like-checkbox" name="like-checkbox">
                                                    <span class="like-text-content" id="like-count-span">24K</span>
                                                    <i id="icon-like-regular" class="fas fa-thumbs-up"></i>
                                                    <i id="icon-like-solid" class="fas fa-thumbs-up"></i>
                                                    <div class="fireworks">
                                                        <div class="checked-like-fx"></div>
                                                    </div>
                                                </span>
                                            </label>
                                            <label class="icons">
                                                <span class="btn-label">
                                                    <input class="input-box" type="checkbox" id="dislike-checkbox" name="dislike-checkbox">
                                                    <i id="icon-dislike-regular" class="fas fa-thumbs-down"></i>
                                                    <i id="icon-dislike-solid" class="fas fa-thumbs-down"></i>
                                                    <span class="dislike-text-content" id="dislike-count-span">1K</span>
                                                    <div class="fireworks">
                                                        <div class="checked-dislike-fx"></div>
                                                    </div>
                                                </span>
                                            </label>
                                            <label class="icons">
                                                <span class="btn-label">
                                                    <input class="input-box" type="checkbox" id="share-checkbox" name="share-checkbox">
                                                    <i id="icon-share-regular" class="fas fa-share"></i>
                                                    <i id="icon-share-solid" class="fas fa-share"></i>
                                                    <span class="share-text-content">Share</span>
                                                    <div class="fireworks">
                                                        <div class="checked-share-fx"></div>
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="video-description" id="viewer-description">
                            Description will appear here...
                        </div>

                        <div class="server-selector-container" id="server-selector-container">
                            <h3>Select Server</h3>
                            <select class="server-selector" id="server-selector"></select>
                        </div>

                        <div class="episode-selector-container" id="episode-selector-container">
                            <h3>Select Episode</h3>
                            <select class="episode-selector" id="episode-selector">
                                <!-- Episode options will be dynamically added -->
                            </select>
                        </div>

                        <div class="season-selector" id="season-selector">
                            <h3>Seasons</h3>
                            <div class="seasons-grid" id="seasons-grid">
                                <!-- Season cards will be dynamically added -->
                            </div>
                        </div>
                    </div>

                    <div class="related-videos">
                        <h2 class="related-title">Related Content</h2>
                        <div class="related-grid" id="related-grid">
                            <!-- Related content will be dynamically added -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active" data-category="all">
            <i class="fas fa-home"></i>
            <span>All</span>
        </a>
        <a href="#" class="nav-item" data-category="movies">
            <i class="fas fa-film"></i>
            <span>Movie</span>
        </a>
        <a href="#" class="nav-item" data-category="series">
            <i class="fas fa-tv"></i>
            <span>Series</span>
        </a>
        <a href="#" class="nav-item" data-category="live">
            <i class="fas fa-broadcast-tower"></i>
            <span>Live</span>
        </a>
        <a href="#" class="nav-item" data-category="watch-later">
            <i class="fas fa-clock"></i>
            <span>Watch Later</span>
        </a>
    </nav>
    <!-- Parental Controls Modals -->
    <div id="parental-controls-modal" class="modal">
        <div class="modal-content parental-controls-content">
            <div class="modal-header">
                <h2 class="modal-title">Parental Controls</h2>
                <button class="close-modal" id="close-parental-controls-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="parental-control-pin-section">
                    <h3>Set a 4-digit PIN to restrict access</h3>
                    <div class="pin-display-container">
                        <div class="pin-display">
                            <span class="pin-dot"></span><span class="pin-dot"></span><span class="pin-dot"></span><span class="pin-dot"></span>
                        </div>
                        <p class="pin-status-text"></p>
                    </div>
                    <div class="pin-pad">
                        <!-- PIN pad buttons will be generated by JS -->
                    </div>
                    <button id="reset-pin-btn" class="parental-btn">Reset PIN</button>
                </div>
                <div class="parental-control-setting-item">
                    <div>
                        <h4>Content Restrictions</h4>
                        <p>Select which content ratings are allowed.</p>
                        <p id="allowed-ratings-display" class="ratings-summary">All ratings allowed</p>
                    </div>
                    <button id="change-ratings-btn" class="parental-btn-secondary">Change</button>
                </div>
                <div class="parental-control-setting-item">
                    <div>
                        <h4>Unrated Content</h4>
                        <p>Allow content without a rating to be played.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="unrated-content-toggle">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div id="ratings-select-modal" class="modal">
        <div class="modal-content ratings-select-content">
            <div class="modal-header">
                <h3 class="modal-title">Select Allowed Ratings</h3>
            </div>
            <div class="modal-body" id="ratings-checkbox-container">
                <!-- Checkboxes will be generated by JS -->
            </div>
            <div class="modal-footer">
                <button class="parental-btn-secondary" id="cancel-ratings-btn">Cancel</button>
                <button class="parental-btn" id="ok-ratings-btn">OK</button>
            </div>
        </div>
    </div>

    <div id="pin-entry-modal" class="modal">
        <div class="modal-content pin-entry-content">
             <div class="modal-header">
                <h3 class="modal-title" id="pin-entry-title">Enter Parental Control PIN</h3>
                <button class="close-modal" id="close-pin-entry-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="pin-display-container">
                    <div class="pin-display-input">
                        <span class="pin-dot"></span><span class="pin-dot"></span><span class="pin-dot"></span><span class="pin-dot"></span>
                    </div>
                    <p class="pin-status-text-input"></p>
                </div>
                <div class="pin-pad-input">
                    <!-- PIN pad buttons will be generated by JS -->
                </div>
                <div class="pin-entry-actions">
                     <button class="parental-btn-secondary" id="cancel-pin-entry-btn">Cancel</button>
                     <button class="parental-btn" id="ok-pin-entry-btn">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Parental Controls Modals -->

<?php require_once 'includes/footer.php'; ?>