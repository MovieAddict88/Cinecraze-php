<?php
require_once 'auth.php'; // Protect this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CineCraze Admin</title>
    <style>
        :root {
            --primary: #e50914;
            --background: #141414;
            --surface: #1f1f1f;
            --text: #ffffff;
            --text-secondary: #b3b3b3;
            --danger: #f40612;
            --success: #46d369;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .change-password-container {
            background-color: var(--surface);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            color: var(--primary);
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-secondary);
        }
        input {
            width: 100%;
            padding: 14px;
            border: 2px solid #333;
            border-radius: 8px;
            background: #111;
            color: var(--text);
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: var(--primary);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #b8070f;
        }
        .message {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid;
            text-align: center;
        }
        .message.error {
            background-color: rgba(244, 6, 18, 0.2);
            color: var(--danger);
            border-color: var(--danger);
        }
        .message.success {
            background-color: rgba(70, 211, 105, 0.2);
            color: var(--success);
            border-color: var(--success);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
            text-decoration: none;
        }
        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="change-password-container">
        <h1>Change Password</h1>
        <?php
        if (isset($_SESSION['message'])) {
            $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'error';
            echo '<div class="message ' . $message_type . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        <form action="change_password_process.php" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Update Password</button>
        </form>
        <a href="index.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
