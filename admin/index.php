<?php
require_once 'auth.php'; // Protect this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CineCraze</title>
    <style>
        :root {
            --primary: #e50914;
            --background: #141414;
            --surface: #1f1f1f;
            --text: #ffffff;
            --text-secondary: #b3b3b3;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            background-color: var(--surface);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            max-width: 1200px;
            margin: auto;
        }
        h1 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        p {
            color: var(--text-secondary);
            font-size: 1.1em;
        }
        .nav-links {
            margin-top: 30px;
        }
        .nav-links a {
            display: inline-block;
            padding: 12px 20px;
            margin-right: 15px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background-color: #b8070f;
        }
        .nav-links a.secondary {
            background-color: #555;
        }
        .nav-links a.secondary:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>This is the CineCraze Admin Dashboard. More features will be added here soon.</p>
        <div class="nav-links">
            <a href="../cinecraze.html">Go to Content Manager</a>
            <a href="change_password.php" class="secondary">Change Password</a>
            <a href="logout.php" class="secondary">Logout</a>
        </div>
    </div>
</body>
</html>
