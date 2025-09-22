# CineCraze Admin Panel

This is a PHP-based admin panel for managing the content of the CineCraze website.

## Setup

1.  **Database:**
    *   Create a database in your MySQL server.
    *   Copy `config-sample.php` to `config.php` and fill in your database credentials and your TMDB API key.
    *   Run the `setup.php` script by navigating to `yourdomain.com/admin/setup.php` in your browser. This will create the necessary tables in your database.

## Usage

*   **TMDB Generator:** Use this tab to fetch movie and series data from The Movie Database (TMDB) and add it to your database.
*   **Manual Input:** Use this tab to manually add movies, series, and live TV channels.
*   **Data Management:**
    *   **Server Selection:** Configure the auto-embed servers.
    *   **Preview:** Preview the content in your database.
