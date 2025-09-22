<?php
require_once '../config.php';

// If user is already logged in, redirect to admin dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CineCraze</title>
    <style>
        :root {
            --primary: #e50914;
            --background: #141414;
            --surface: #1a1a1a;
            --text: #ffffff;
            --text-secondary: #b3b3b3;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--background);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: var(--surface);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #333;
            color: var(--text);
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: var(--primary);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #b20710;
        }
        .error-message {
            background-color: #ff3333;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>CineCraze Admin</h1>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <form action="auth.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
