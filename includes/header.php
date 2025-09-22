<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icon-css@4.1.7/css/flag-icons.min.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Player Libraries -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/shaka-player/4.3.4/shaka-player.compiled.js" defer></script>
</head>
<body>
    <!-- Notification Bar -->
    <div class="notification-bar" id="notification-bar">
        <p id="notification-message"></p>
        <button class="close-btn" id="close-notification">&times;</button>
    </div>

    <!-- Header -->
    <header>
        <a href="#" class="logo">
            <img src="https://movie-fcs.fwh.is/cinecraze/cinecraze.png" alt="CineCraze Logo">
            <span>CineCraze</span>
        </a>

        <div class="search-container">
            <div class="search-input-container">
                <input type="text" id="search-input" placeholder="Search movies, TV shows..." role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="search-results" aria-haspopup="listbox">
                <button class="close-search-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="search-results" id="search-results" role="listbox" aria-label="Search suggestions"></div>
        </div>

        <div class="header-controls">
            <button class="mobile-search-btn" id="mobile-search-btn">
                <i class="fas fa-search"></i>
            </button>
            <button class="theme-toggle" id="refresh-btn" title="Refresh Data">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-profile">
                <i class="fas fa-user"></i>
            </div>
            <button class="hamburger-btn" id="hamburger-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
