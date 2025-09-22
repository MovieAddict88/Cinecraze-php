<?php
// Main Admin Dashboard Page

// Include the authentication check at the very top.
// The header file also includes this, but it's good practice for the main page to have it too.
require_once '../includes/auth.php';
require_admin_login();

// Include the header, which contains the HTML head, styles, and opening body tags.
include 'header.php';
?>

<!-- This is the main content area where the different tabs will be displayed -->
<main>
    <?php
    // Include the HTML content for each of the tabs from the 'parts' directory.
    // The active class on the first tab and its nav item will make it visible by default.

    // TMDB Generator Tab
    include 'parts/tmdb_generator.php';

    // Manual Input Tab
    include 'parts/manual_input.php';

    // Bulk Operations Tab
    include 'parts/bulk_operations.php';

    // Data Management Tab
    include 'parts/data_management.php';
    ?>
</main>

<?php
// Include the footer, which contains the bottom navigation, modals,
// JavaScript logic, and closing body/html tags.
include 'footer.php';
?>
