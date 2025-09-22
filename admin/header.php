<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#e50914">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>CineCraze Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/cinecraze-admin.css">
    <style>
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #1f1f1f;
        }
        .admin-nav .welcome-msg {
            color: #fff;
            font-size: 16px;
        }
        .admin-nav a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            background-color: #e50914;
            margin-left: 10px;
            transition: background-color 0.3s;
        }
        .admin-nav a:hover {
            background-color: #f40612;
        }
    </style>
</head>
<body>
    <div class="admin-nav">
        <div class="welcome-msg">
            Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!
        </div>
        <div>
            <a href="profile.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <header>
            <h1>CineCraze Admin Panel</h1>
            <p class="subtitle">Manage your content for Movies, TV Series, and Live TV</p>
        </header>
