<?php
// Start the session on any page that includes this file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if an admin user is currently logged in.
 *
 * @return bool True if the admin is logged in, false otherwise.
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * If the user is not logged in as an admin, this function will redirect them
 * to the login page and terminate the script execution.
 *
 * This should be called at the top of every page in the admin panel
 * that requires authentication.
 *
 * @param string $login_path The relative path to the login page.
 *                           Defaults to 'login.php'. It might need to be
 *                           adjusted depending on the directory structure.
 */
function require_admin_login($login_path = 'login.php') {
    if (!is_admin_logged_in()) {
        // Clear any potentially lingering session data
        session_unset();
        session_destroy();

        header("Location: $login_path");
        exit;
    }
}

?>
