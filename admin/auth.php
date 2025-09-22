<?php
session_start();

// If the user is not logged in, redirect to the login page.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Optional: You can also add activity-based session timeout logic here
// For example, update a timestamp on each authenticated page load and
// check if it's been too long since the last activity.
?>
