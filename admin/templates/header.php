<?php
require_once '../config.php';

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
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
    <style>
        :root {
            --primary: #e50914;
            --primary-dark: #b8070f;
            --secondary: #221f1f;
            --background: #0a0a0a;
            --surface: #1a1a1a;
            --surface-light: #2d2d2d;
            --surface-hover: #333333;
            --text: #ffffff;
            --text-secondary: #b3b3b3;
            --text-muted: #808080;
            --success: #46d369;
            --warning: #ffa500;
            --danger: #f40612;
            --accent: #00d4ff;
            --accent-dark: #0099cc;
            --bottom-bar-height: 80px;
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --shadow: 0 8px 32px rgba(0,0,0,0.4);
            --shadow-hover: 0 16px 48px rgba(0,0,0,0.6);
            --shadow-primary: 0 8px 32px rgba(229, 9, 20, 0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(ellipse at center, var(--surface) 0%, var(--background) 70%, var(--secondary) 100%);
            color: var(--text);
            min-height: 100vh;
            padding: max(20px, env(safe-area-inset-top)) max(20px, env(safe-area-inset-right)) calc(var(--bottom-bar-height) + max(20px, env(safe-area-inset-bottom))) max(20px, env(safe-area-inset-left));
            line-height: 1.6;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: var(--surface);
            border-radius: var(--border-radius);
            margin-bottom: 20px;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            color: var(--primary);
            margin: 0;
        }

        .admin-header a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: var(--border-radius-sm);
            transition: background-color 0.3s;
        }

        .admin-header a:hover {
            background-color: var(--surface-light);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 30px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-primary);
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .subtitle {
            font-size: clamp(1rem, 2.5vw, 1.3rem);
            opacity: 0.9;
            font-weight: 300;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--surface) 0%, var(--surface-light) 100%);
            border-top: 2px solid var(--primary);
            box-shadow: 0 -8px 32px rgba(0,0,0,0.4);
            backdrop-filter: blur(20px);
            z-index: 1000;
            height: var(--bottom-bar-height);
            -webkit-backdrop-filter: blur(20px);
        }

        .nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 max(10px, env(safe-area-inset-left)) 0 max(10px, env(safe-area-inset-right));
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s ease;
            min-width: 60px;
            text-decoration: none;
            color: var(--text-secondary);
        }

        .nav-item:hover {
            background: rgba(229, 9, 20, 0.1);
            color: var(--text);
            transform: translateY(-2px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
        }

        .nav-icon {
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: clamp(0.7rem, 2vw, 0.8rem);
            font-weight: 600;
            text-align: center;
            line-height: 1;
        }

        .card {
            background: var(--surface);
            border-radius: var(--border-radius);
            padding: clamp(25px, 4vw, 35px);
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--surface-light);
        }

        .card h2 {
            color: var(--primary);
            margin-bottom: 25px;
            font-size: clamp(1.3rem, 3vw, 1.8rem);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        input, select, textarea {
            width: 100%;
            padding: clamp(14px, 2.5vw, 18px);
            border: 2px solid var(--surface-light);
            border-radius: var(--border-radius-sm);
            background: var(--background);
            color: var(--text);
            font-size: clamp(14px, 2.5vw, 16px);
        }

        .btn {
            padding: clamp(14px, 2.5vw, 18px) clamp(24px, 4vw, 32px);
            border: none;
            border-radius: var(--border-radius-sm);
            font-size: clamp(14px, 2.5vw, 16px);
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(200px, 100%), 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .preview-item {
            background: var(--surface-light);
            border-radius: 10px;
            overflow: hidden;
        }

        .preview-item img {
            width: 100%;
            height: clamp(200px, 40vw, 300px);
            object-fit: cover;
        }

        .preview-item .info {
            padding: 15px;
        }

        .preview-item .title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .preview-item .meta {
            font-size: clamp(0.8rem, 1.8vw, 0.9rem);
            color: var(--text-secondary);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: var(--surface);
            margin: 10% auto;
            padding: 30px;
            border: 1px solid var(--border);
            width: 80%;
            max-width: 700px;
            border-radius: var(--border-radius);
            position: relative;
            animation: fadeIn 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .modal-content h2 {
            margin-top: 0;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close-button {
            color: var(--text-secondary);
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 25px;
            line-height: 1;
        }

        .close-button:hover,
        .close-button:focus {
            color: var(--primary);
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="admin-header">
    <h1>CineCraze Admin</h1>
    <a href="logout.php">Logout</a>
</div>
