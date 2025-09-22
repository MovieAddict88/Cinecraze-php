<?php
session_start();

// If the user is already logged in, redirect to the admin index page
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

require_once '../includes/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Prepare and execute the statement to get the user
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_regenerate_id();
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                // Password is not valid
                $error = 'Invalid username or password.';
            }
        } else {
            // Username doesn't exist
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/cinecraze.css">
    <style>
        body {
            background-color: #1a1a1a;
            color: #f5f5f5;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .card {
            background-color: #2a2a2a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .card h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #e50914;
        }
        .status.error {
            background-color: #5c1a1a;
            color: #f5c6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e50914;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ccc;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #555;
            background-color: #333;
            color: #f5f5f5;
            font-size: 16px;
        }
        .btn.btn-primary {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            background-color: #e50914;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn.btn-primary:hover {
            background-color: #b20710;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 450px; margin-left: auto; margin-right: auto;">
            <h2>CineCraze Admin Panel</h2>
            <?php if ($error): ?>
                <div class="status error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
