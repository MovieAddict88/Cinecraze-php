<?php
require_once '../includes/auth.php';
require_admin_login(); // Protect this page

require_once '../config/db.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $admin_id = $_SESSION['admin_id'];

    // Basic validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = 'Please fill in all fields.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'New password and confirmation do not match.';
    } elseif (strlen($new_password) < 8) {
        $error_message = 'New password must be at least 8 characters long.';
    } else {
        $conn = get_db_connection();
        if ($conn) {
            // Get the current password hash from the database
            $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $stmt->close();

            if ($admin && password_verify($current_password, $admin['password'])) {
                // Current password is correct, hash the new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_password_hash, $admin_id);

                if ($update_stmt->execute()) {
                    $success_message = 'Password changed successfully!';
                } else {
                    $error_message = 'Failed to update password. Please try again.';
                }
                $update_stmt->close();
            } else {
                $error_message = 'Incorrect current password.';
            }
            $conn->close();
        } else {
            $error_message = 'Database connection error.';
        }
    }
}
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
            --primary-dark: #b20710;
            --dark: #141414;
            --dark-2: #1a1a1a;
            --light: #f5f5f5;
            --gray: #8c8c8c;
            --success: #46d369;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: var(--dark);
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: var(--dark-2);
            padding: 40px 60px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 500px;
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
            margin-bottom: 5px;
            color: var(--gray);
        }
        input[type="password"] {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 4px;
            background-color: #333;
            color: var(--light);
            font-size: 16px;
        }
        input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary);
        }
        .action-button {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 4px;
            background-color: var(--primary);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .action-button:hover {
            background-color: var(--primary-dark);
        }
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message.success {
            background-color: var(--success);
            color: white;
        }
        .message.error {
            background-color: #e87c03;
            color: white;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .nav-links a {
            color: var(--gray);
            text-decoration: none;
            margin: 0 10px;
        }
        .nav-links a:hover {
            color: var(--light);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <?php if ($success_message): ?>
            <div class="message success">
                <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="change_password.php">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="8">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="action-button">Change Password</button>
        </form>
        <div class="nav-links">
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
