<?php
// This file is included at the bottom of all admin pages.
?>
    </div> <!-- Closing the .container div from header.php -->

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
            <div class="nav-item" onclick="switchTab('bulk-operations')" role="button" tabindex="0" aria-label="Bulk Operations">
                <div class="nav-icon">📦</div>
                <div class="nav-label">Bulk</div>
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
        // --- Utility Functions ---
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        function showStatus(type, message) {
            // This is a placeholder for a more robust notification system.
            alert(`${type.toUpperCase()}: ${message}`);
        }

        function showLoading(elementId, show) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = show ? 'inline-block' : 'none';
            }
        }

        function switchTab(tabName) {
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Find the nav item associated with the tab and activate it.
            // This is a simplified approach; a more robust one might use data attributes.
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                if (item.getAttribute('onclick').includes(tabName)) {
                    item.classList.add('active');
                }
            });

            document.getElementById(tabName).classList.add('active');
        }

        // --- Core Application Logic (Refactored for API) ---

        document.addEventListener('DOMContentLoaded', function() {
            // Initial load of the content preview
            updatePreview();
        });

        const debouncedUpdatePreview = debounce(() => updatePreview(), 300);

        /**
         * Fetches content from the API and renders the preview grid.
         */
        async function updatePreview() {
            const filter = document.getElementById('preview-filter')?.value || 'all';
            const searchTerm = document.getElementById('preview-search')?.value || '';
            const container = document.getElementById('content-preview');

            if (!container) return;
            container.innerHTML = '<span class="loading"></span> Loading content...';

            try {
                const response = await fetch(`../api/content_get.php?type=${filter}&search=${searchTerm}`);
                const result = await response.json();

                if (result.success && result.data) {
                    container.innerHTML = '';
                    if (result.data.length === 0) {
                        container.innerHTML = '<p>No content found.</p>';
                        return;
                    }
                    result.data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `
                            <img src="${item.poster_url || 'https://via.placeholder.com/300x450?text=No+Image'}" alt="${item.title}" loading="lazy">
                            <div class="info">
                                <div class="title">${item.title}</div>
                                <div class="meta">${item.release_year || ''} • ${item.type.toUpperCase()}</div>
                                <div style="margin-top: 10px;">
                                    <button class="btn btn-secondary btn-small" onclick="editContent(${item.id})">Edit</button>
                                    <button class="btn btn-danger btn-small" onclick="deleteContent(${item.id}, '${item.title}')">Delete</button>
                                </div>
                            </div>
                        `;
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = `<p>Error loading content: ${result.message}</p>`;
                }
            } catch (error) {
                container.innerHTML = `<p>An error occurred while fetching content.</p>`;
                console.error('Update preview error:', error);
            }
        }

        /**
         * Deletes a piece of content after confirmation.
         */
        async function deleteContent(contentId, title) {
            if (!confirm(`Are you sure you want to delete "${title}"? This cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch('../api/content_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: contentId })
                });
                const result = await response.json();

                if (result.success) {
                    showStatus('success', result.message);
                    updatePreview(); // Refresh the list
                } else {
                    showStatus('error', result.message);
                }
            } catch (error) {
                showStatus('error', 'An API error occurred during deletion.');
                console.error('Delete content error:', error);
            }
        }

        /**
         * Generates content from TMDB by calling the backend API.
         */
        async function generateFromTMDB(type) {
             const tmdbId = document.getElementById(`${type}-tmdb-id`).value;
             if (!tmdbId) {
                 showStatus('warning', 'Please enter a TMDB ID');
                 return;
             }

             showLoading(`${type}-loading`, true);

             const formData = new FormData();
             formData.append('tmdb_id', tmdbId);
             formData.append('type', type);
             // TODO: Add logic to gather additional servers if needed

             try {
                 const response = await fetch('../api/generate_tmdb.php', {
                     method: 'POST',
                     body: formData
                 });
                 const result = await response.json();
                 if (result.success) {
                     showStatus('success', result.message);
                     updatePreview();
                 } else {
                     showStatus('error', result.message);
                 }
             } catch (error) {
                 showStatus('error', 'An API error occurred during TMDB generation.');
             } finally {
                 showLoading(`${type}-loading`, false);
             }
        }

        /**
         * Imports a JSON file by uploading it to the backend API.
         */
        async function importData() {
            const fileInput = document.getElementById('import-file');
            if (fileInput.files.length === 0) {
                showStatus('warning', 'Please select a file to import.');
                return;
            }

            showLoading('import-loading', true);
            const formData = new FormData();
            formData.append('jsonFile', fileInput.files[0]);

            try {
                const response = await fetch('../api/import_json.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                 if (result.success) {
                    showStatus('success', result.message);
                    updatePreview(); // Refresh content after successful import
                } else {
                    showStatus('error', `Import failed: ${result.message}`);
                }
            } catch(error) {
                showStatus('error', 'An API error occurred during import.');
            } finally {
                showLoading('import-loading', false);
            }
        }

        // Placeholder for edit functionality
        function editContent(contentId) {
            // TODO: Fetch full content details from API
            // Then populate the modal with a form
            showStatus('info', `Edit functionality for item ${contentId} is not yet implemented.`);
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            if(modal) modal.style.display = 'none';
        }

        // Placeholder for save edit
        async function saveEdit() {
            // TODO: Gather data from the edit form
            // and send it to api/content_update.php
            showStatus('info', 'Save edit functionality is not yet implemented.');
        }

    </script>
</body>
</html>
