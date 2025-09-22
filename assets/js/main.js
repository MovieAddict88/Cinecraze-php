document.addEventListener('DOMContentLoaded', () => {
    console.log("Frontend Script Loaded");
    init();
});

// --- GLOBALS ---
const API_BASE_URL = 'api/index.php';
let allContent = []; // Cache for all fetched content
let player = null; // To hold the Plyr or Shaka player instance
let currentContent = null; // To hold the details of the content being viewed

// --- INITIALIZATION ---
function init() {
    loadContent();
    const backButton = document.getElementById('back-button');
    if (backButton) backButton.addEventListener('click', closeViewer);

    // Add event listener for the profile icon to link to the admin panel
    const profileIcon = document.querySelector('.user-profile');
    if(profileIcon) {
        profileIcon.addEventListener('click', () => {
            window.location.href = 'admin/login.php';
        });
    }
}

// --- API & DATA FETCHING ---
async function fetchAPI(endpoint, options = {}) {
    const url = `${API_BASE_URL}?request=${endpoint}`;
    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ error: 'An unknown error occurred' }));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }
        // For 204 No Content response
        if (response.status === 204) {
            return null;
        }
        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

async function loadContent() {
    const grid = document.getElementById('content-grid');
    const spinner = document.getElementById('loading-spinner');
    if (spinner) spinner.style.display = 'block';
    try {
        const response = await fetchAPI('content');
        if (response.success) {
            allContent = response.data.data;
            renderCarousel(allContent.slice(0, 5));
            renderContentGrid(allContent);
        } else {
            throw new Error(response.error || 'Failed to load content.');
        }
    } catch (error) {
        if (grid) grid.innerHTML = `<p class="status error">Could not load content: ${error.message}</p>`;
    } finally {
        if (spinner) spinner.style.display = 'none';
    }
}

// --- UI RENDERING ---
function renderCarousel(items) {
    const carouselInner = document.getElementById('carousel-inner');
    if (!carouselInner || !items) return;
    carouselInner.innerHTML = '';
    items.forEach(item => {
        const carouselItem = document.createElement('div');
        carouselItem.className = 'carousel-item';
        carouselItem.innerHTML = `<img src="${item.backdrop_path || item.poster_path || ''}" alt="${item.title}"><div class="carousel-content"><h2>${item.title}</h2></div>`;
        carouselItem.addEventListener('click', () => openViewer(item.id));
        carouselInner.appendChild(carouselItem);
    });
    if (items.length > 0) carouselInner.children[0].classList.add('active');
}

function renderContentGrid(items) {
    const grid = document.getElementById('content-grid');
    if (!grid || !items) return;
    grid.innerHTML = '';
    items.forEach(item => {
        const card = document.createElement('div');
        card.className = 'content-card';
        card.innerHTML = `
            <div class="card-img"><img src="${item.poster_path || ''}" alt="${item.title}" loading="lazy"></div>
            <div class="card-info"><h3 class="card-title">${item.title}</h3><div class="card-meta"><span>${item.release_date ? item.release_date.substring(0, 4) : 'N/A'}</span><span class="badge">${item.content_type}</span></div></div>
        `;
        card.addEventListener('click', () => openViewer(item.id));
        grid.appendChild(card);
    });
}

// --- VIEWER & PLAYER LOGIC ---
async function openViewer(contentId) {
    const viewerPage = document.getElementById('viewer-page');
    const mainContent = document.querySelector('main');
    if (!viewerPage || !mainContent) return;

    mainContent.style.display = 'none';
    viewerPage.style.display = 'block';
    window.scrollTo(0, 0);

    try {
        const response = await fetchAPI(`content&id=${contentId}`);
        if (response.success) {
            currentContent = response.data;
            document.getElementById('viewer-title').textContent = currentContent.title;
            document.getElementById('viewer-description').textContent = currentContent.overview;

            // Setup Like, Dislike, Share
            setupInteractions(currentContent);

            setupPlayer(currentContent);
        } else {
            throw new Error(response.error);
        }
    } catch (error) {
        document.getElementById('player-message-area').innerHTML = `<p class="status error">Could not load content details: ${error.message}</p>`;
    }
}

function closeViewer() {
    const viewerPage = document.getElementById('viewer-page');
    const mainContent = document.querySelector('main');
    if (!viewerPage || !mainContent) return;

    if (player) {
        if (typeof player.destroy === 'function') {
            player.destroy();
        }
        player = null;
    }

    viewerPage.style.display = 'none';
    mainContent.style.display = 'block';
    currentContent = null;
}

function setupPlayer(content) {
    // Player setup logic from previous step...
    const videoElement = document.getElementById('player');
    const playerContainer = document.querySelector('.player-container');

    if (player && typeof player.destroy === 'function') player.destroy();
    playerContainer.querySelector('iframe')?.remove();
    videoElement.style.display = 'block';

    if (content.content_type === 'series') {
        // Series logic...
        document.getElementById('season-selector').style.display = 'block';
        document.getElementById('episode-selector-container').style.display = 'block';
        renderSeasons(content.seasons);
        if (content.seasons && content.seasons.length > 0) {
            renderEpisodes(content.seasons[0].episodes);
        }
    } else {
        document.getElementById('season-selector').style.display = 'none';
        document.getElementById('episode-selector-container').style.display = 'none';
        populateServerSelector(content.servers);
    }
}

function populateServerSelector(servers) {
    const serverSelector = document.getElementById('server-selector');
    serverSelector.innerHTML = '';
    if (!servers || servers.length === 0) {
        loadVideoSource(null); return;
    }
    servers.forEach(server => {
        const option = document.createElement('option');
        option.value = JSON.stringify(server);
        option.textContent = server.server_name;
        serverSelector.appendChild(option);
    });
    serverSelector.onchange = (e) => loadVideoSource(JSON.parse(e.target.value));
    loadVideoSource(servers[0]);
}

function renderSeasons(seasons) {
    const seasonsGrid = document.getElementById('seasons-grid');
    seasonsGrid.innerHTML = '';
    if(!seasons) return;
    seasons.forEach(season => {
        const seasonCard = document.createElement('div');
        seasonCard.className = 'season-card';
        seasonCard.textContent = `Season ${season.season_number}`;
        seasonCard.addEventListener('click', () => renderEpisodes(season.episodes));
        seasonsGrid.appendChild(seasonCard);
    });
}

function renderEpisodes(episodes) {
    const episodeSelector = document.getElementById('episode-selector');
    episodeSelector.innerHTML = '';
    if(!episodes) return;
    episodes.forEach(episode => {
        const option = document.createElement('option');
        option.value = JSON.stringify(episode.servers);
        option.textContent = `E${episode.episode_number}: ${episode.title}`;
        episodeSelector.appendChild(option);
    });
    episodeSelector.onchange = (e) => populateServerSelector(JSON.parse(e.target.value));
    if (episodes.length > 0) {
        populateServerSelector(episodes[0].servers);
    }
}

function loadVideoSource(server) {
    // Player logic from previous step...
    const playerContainer = document.querySelector('.player-container');
    const videoElement = document.getElementById('player');
    playerContainer.querySelector('iframe')?.remove();
    if (player && typeof player.destroy === 'function') player.destroy();

    if (!server) {
        videoElement.style.display = 'none';
        document.getElementById('player-message-area').style.display = 'block';
        document.getElementById('player-message-area').textContent = 'No video source available.';
        return;
    }

    videoElement.style.display = 'block';
    document.getElementById('player-message-area').style.display = 'none';

    switch (server.server_type) {
        case 'embed':
            videoElement.style.display = 'none';
            const iframe = document.createElement('iframe');
            iframe.src = server.server_url;
            iframe.className = 'external-content-iframe';
            iframe.setAttribute('allowfullscreen', '');
            playerContainer.appendChild(iframe);
            break;
        case 'hls':
            player = new Plyr(videoElement);
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(server.server_url);
                hls.attachMedia(videoElement);
            } else {
                videoElement.src = server.server_url;
            }
            break;
        case 'drm':
            if (shaka.Player.isBrowserSupported()) {
                player = new shaka.Player(videoElement);
                player.configure({ drm: { servers: { 'com.widevine.alpha': server.drm_license_url } } });
                player.load(server.server_url).catch(e => console.error("Shaka Error", e));
            }
            break;
        default:
            player = new Plyr(videoElement, { sources: [{ src: server.server_url, type: 'video/mp4' }] });
            break;
    }
}

// --- INTERACTIONS (LIKE/DISLIKE/SHARE) ---
function setupInteractions(content) {
    const likeBtn = document.getElementById('like-checkbox');
    const dislikeBtn = document.getElementById('dislike-checkbox');
    const shareBtn = document.getElementById('share-checkbox');

    updateLikeDislikeUI(content);

    likeBtn.onclick = () => handleLike(content.id);
    dislikeBtn.onclick = () => handleDislike(content.id);
    shareBtn.onclick = () => handleShare(content);
}

function updateLikeDislikeUI(content) {
    document.getElementById('like-count-span').textContent = content.likes || 0;
    document.getElementById('dislike-count-span').textContent = content.dislikes || 0;
}

async function handleLike(contentId) {
    try {
        const response = await fetchAPI(`interactions&action=like&id=${contentId}`, { method: 'POST' });
        if (response.success) {
            document.getElementById('like-count-span').textContent = response.data.likes;
            document.getElementById('dislike-count-span').textContent = response.data.dislikes;
        }
    } catch (error) {
        console.error("Failed to register like:", error);
    }
}

async function handleDislike(contentId) {
    try {
        const response = await fetchAPI(`interactions&action=dislike&id=${contentId}`, { method: 'POST' });
        if (response.success) {
            document.getElementById('like-count-span').textContent = response.data.likes;
            document.getElementById('dislike-count-span').textContent = response.data.dislikes;
        }
    } catch (error) {
        console.error("Failed to register dislike:", error);
    }
}

async function handleShare(content) {
    const shareData = {
        title: `Watch ${content.title}`,
        text: `Check out ${content.title} on CineCraze!`,
        url: window.location.href
    };
    try {
        if (navigator.share) {
            await navigator.share(shareData);
        } else {
            await navigator.clipboard.writeText(shareData.url);
            alert('Link copied to clipboard!');
        }
    } catch (err) {
        console.error('Share/Copy failed:', err);
    }
}
