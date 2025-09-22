document.addEventListener('DOMContentLoaded', () => {
    console.log("Admin Panel Script Loaded");
    init();
});

const API_BASE_URL = '../api/index.php';
let currentPage = 1;

/**
 * Main initialization function
 */
function init() {
    // Setup Tab Switching
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const tabName = item.dataset.tab;
            if (tabName) {
                switchTab(tabName);
            }
        });
    });

    // Setup Event Listeners for Preview Tab
    const previewFilter = document.getElementById('preview-filter');
    const previewSearch = document.getElementById('preview-search');
    if (previewFilter) previewFilter.addEventListener('change', () => { currentPage = 1; updatePreview(); });
    if (previewSearch) previewSearch.addEventListener('input', debounce(() => { currentPage = 1; updatePreview(); }, 300));

    // TMDB Generator listeners
    const btnGenMovie = document.getElementById('btn-generate-movie');
    const btnGenSeries = document.getElementById('btn-generate-series');
    if (btnGenMovie) btnGenMovie.addEventListener('click', () => generateFromTMDB('movie'));
    if (btnGenSeries) btnGenSeries.addEventListener('click', () => generateFromTMDB('tv'));

    // Manual Input listener
    const manualForm = document.getElementById('manual-form');
    if (manualForm) manualForm.addEventListener('submit', addManualContent);

    // TMDB Search listener
    const btnSearchTMDB = document.getElementById('btn-tmdb-search');
    if (btnSearchTMDB) btnSearchTMDB.addEventListener('click', searchTMDB);

    // Initial load for the active tab
    updatePreview();
}

/**
 * A debouncing function to limit the rate of function execution
 */
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
    };
}

/**
 * Handles switching between tabs
 * @param {string} tabName The ID of the tab content to show
 */
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.querySelectorAll('.nav-item').forEach(nav => {
        nav.classList.remove('active');
    });

    const activeTab = document.getElementById(tabName);
    const activeNavItem = document.querySelector(`.nav-item[data-tab="${tabName}"]`);

    if (activeTab) activeTab.classList.add('active');
    if (activeNavItem) activeNavItem.classList.add('active');
}

/**
 * Generic API fetch helper
 * @param {string} endpoint The API endpoint to call (e.g., 'content')
 * @param {object} options The options for the fetch call (method, headers, body)
 * @returns {Promise<any>} The JSON response from the API
 */
async function fetchAPI(endpoint, options = {}) {
    const url = `${API_BASE_URL}?request=${endpoint}`;

    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };

    const config = { ...defaultOptions, ...options };
    if (config.body) {
        config.body = JSON.stringify(config.body);
    }

    try {
        const response = await fetch(url, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || `HTTP error! status: ${response.status}`);
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        showStatus('error', `API Error: ${error.message}`);
        throw error;
    }
}

/**
 * Fetches and displays the content preview list
 */
async function updatePreview() {
    const previewContainer = document.getElementById('content-preview');
    if (!previewContainer) return; // Don't run if the element isn't on the page

    const filter = document.getElementById('preview-filter').value;
    const search = document.getElementById('preview-search').value;

    previewContainer.innerHTML = '<div class="loading"></div>';

    try {
        let endpoint = `content&page=${currentPage}&limit=12`;
        if (filter) endpoint += `&type=${filter}`;
        if (search) endpoint += `&search=${encodeURIComponent(search)}`;

        const response = await fetchAPI(endpoint);

        previewContainer.innerHTML = ''; // Clear loading spinner

        if (response.success && response.data.data.length > 0) {
            response.data.data.forEach(item => {
                const itemEl = renderContentItem(item);
                previewContainer.appendChild(itemEl);
            });
            renderPagination(response.data);
        } else {
            previewContainer.innerHTML = '<p>No content found.</p>';
            document.getElementById('pagination-controls').innerHTML = '';
        }
    } catch (error) {
        previewContainer.innerHTML = `<p class="status error">Failed to load content.</p>`;
    }
}

/**
 * Renders a single content item for the preview grid
 */
function renderContentItem(item) {
    const div = document.createElement('div');
    div.className = 'preview-item';
    const placeholder = 'https://via.placeholder.com/300x450?text=No+Image';

    div.innerHTML = `
        <img src="${item.poster_path || placeholder}" alt="${item.title}" loading="lazy">
        <div class="info">
            <div class="title">${item.title}</div>
            <div class="meta">${item.release_date ? item.release_date.substring(0, 4) : 'N/A'} • ${item.content_type.toUpperCase()}</div>
            <div style="margin-top: 10px;">
                <button class="btn btn-secondary btn-small" onclick="editContent(${item.id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteContent(${item.id}, '${item.title.replace(/'/g, "\\'")}')">Delete</button>
            </div>
        </div>
    `;
    return div;
}

/**
 * Renders pagination controls
 */
function renderPagination(paginationData) {
    const { page, total_pages } = paginationData;
    const paginationContainer = document.getElementById('pagination-controls');

    if (!paginationContainer || total_pages <= 1) {
        if(paginationContainer) paginationContainer.innerHTML = '';
        return;
    }

    paginationContainer.innerHTML = `
        <button class="btn btn-secondary btn-small" ${page === 1 ? 'disabled' : ''} onclick="changePage(${page - 1})">Previous</button>
        <span id="page-info" style="margin: 0 15px; font-weight: 600;">Page ${page} of ${total_pages}</span>
        <button class="btn btn-secondary btn-small" ${page === total_pages ? 'disabled' : ''} onclick="changePage(${page + 1})">Next</button>
    `;
}

function changePage(newPage) {
    currentPage = newPage;
    updatePreview();
}

/**
 * Deletes a content item after confirmation
 */
async function deleteContent(id, title) {
    if (confirm(`Are you sure you want to delete "${title}"? This cannot be undone.`)) {
        try {
            showStatus('info', `Deleting "${title}"...`);
            const response = await fetchAPI(`content&id=${id}`, { method: 'DELETE' });
            if (response.success) {
                showStatus('success', `"${title}" was deleted successfully.`);
                updatePreview(); // Refresh the view
            }
        } catch (error) { /* Error already shown by fetchAPI */ }
    }
}

/**
 * Handles generating content from TMDB
 * @param {'movie'|'tv'} type The type of content to generate
 */
async function generateFromTMDB(type, tmdbIdFromSearch = null) {
    const idInput = document.getElementById(`${type === 'movie' ? 'movie' : 'series'}-tmdb-id`);
    const tmdbId = tmdbIdFromSearch || idInput.value;
    const button = document.getElementById(`btn-generate-${type === 'movie' ? 'movie' : 'series'}`);
    const loadingSpinner = button.querySelector('.loading');

    if (!tmdbId) {
        showStatus('error', 'Please enter a TMDB ID.');
        return;
    }

    loadingSpinner.style.display = 'inline-block';
    button.disabled = true;

    try {
        showStatus('info', `Importing ${type} with TMDB ID: ${tmdbId}...`);
        const response = await fetchAPI('tmdb', {
            method: 'POST',
            body: {
                tmdb_id: tmdbId,
                type: type
            }
        });

        if (response.success) {
            showStatus('success', `${response.data.title} imported successfully!`);
            if(idInput) idInput.value = ''; // Clear input on success
            updatePreview(); // Refresh the content list
        }
    } catch (error) {
        // Error is already displayed by fetchAPI
    } finally {
        loadingSpinner.style.display = 'none';
        button.disabled = false;
    }
}

/**
 * Handles the submission of the manual content form
 */
async function addManualContent(e) {
    e.preventDefault();
    const form = e.target;
    const button = form.querySelector('button[type="submit"]');
    const loadingSpinner = button.querySelector('.loading');

    const contentData = {
        content_type: form.querySelector('#manual-type').value,
        title: form.querySelector('#manual-title').value,
        overview: form.querySelector('#manual-overview').value,
        poster_path: form.querySelector('#manual-poster-path').value,
        release_date: form.querySelector('#manual-release-date').value,
    };

    loadingSpinner.style.display = 'inline-block';
    button.disabled = true;

    try {
        showStatus('info', `Adding "${contentData.title}"...`);
        const response = await fetchAPI('content', {
            method: 'POST',
            body: contentData
        });

        if (response.success) {
            showStatus('success', `"${contentData.title}" added successfully!`);
            form.reset();
            switchTab('data-management');
            currentPage = 1;
            updatePreview();
        }
    } catch (error) {
        // Error already handled
    } finally {
        loadingSpinner.style.display = 'none';
        button.disabled = false;
    }
}

/**
 * Searches TMDB via the backend proxy
 */
async function searchTMDB() {
    const query = document.getElementById('tmdb-search-query').value;
    const type = document.getElementById('tmdb-search-type').value;
    const resultsContainer = document.getElementById('tmdb-search-results');
    const button = document.getElementById('btn-tmdb-search');
    const loadingSpinner = button.querySelector('.loading');

    if (!query) {
        showStatus('error', 'Please enter a search query.');
        return;
    }

    loadingSpinner.style.display = 'inline-block';
    button.disabled = true;
    resultsContainer.innerHTML = '<div class="loading"></div>';

    try {
        const endpoint = `tmdb_search&query=${encodeURIComponent(query)}&type=${type}`;
        const response = await fetchAPI(endpoint, { method: 'GET' }); // No body for GET
        renderSearchResults(response.results);
    } catch (error) {
        resultsContainer.innerHTML = `<p class="status error">Search failed.</p>`;
    } finally {
        loadingSpinner.style.display = 'none';
        button.disabled = false;
    }
}

/**
 * Renders TMDB search results
 */
function renderSearchResults(results) {
    const resultsContainer = document.getElementById('tmdb-search-results');
    resultsContainer.innerHTML = '';

    if (!results || results.length === 0) {
        resultsContainer.innerHTML = '<p>No results found.</p>';
        return;
    }

    results.forEach(item => {
        const type = item.media_type || (item.title ? 'movie' : 'tv');
        if (type !== 'movie' && type !== 'tv') return;

        const title = item.title || item.name;
        const year = (item.release_date || item.first_air_date || '').substring(0, 4);
        const placeholder = 'https://via.placeholder.com/300x450?text=No+Image';
        const poster = item.poster_path ? `https://image.tmdb.org/t/p/w500${item.poster_path}` : placeholder;

        const div = document.createElement('div');
        div.className = 'preview-item';
        div.innerHTML = `
            <img src="${poster}" alt="${title}" loading="lazy">
            <div class="info">
                <div class="title">${title}</div>
                <div class="meta">${year} • ${type.toUpperCase()}</div>
                <button class="btn btn-primary btn-small" onclick="generateFromTMDB('${type}', ${item.id})">
                    Generate
                </button>
            </div>
        `;
        resultsContainer.appendChild(div);
    });
}

/**
 * Shows a status message (toast) on the screen
 */
function showStatus(type, message) {
    const statusContainer = document.getElementById('global-status');
    if (!statusContainer) return;

    const statusEl = document.createElement('div');
    statusEl.className = `status ${type}`;
    statusEl.textContent = message;
    statusContainer.appendChild(statusEl);
    setTimeout(() => { statusEl.remove(); }, 5000);
}

// Placeholder for edit functionality
function editContent(id) {
    showStatus('info', `Edit functionality for item ${id} is not yet implemented.`);
    console.log("TODO: Implement edit functionality for item", id);
}
