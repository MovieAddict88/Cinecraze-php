<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header('Location: login.php?error=Username and password are required.');
        exit();
    }

    try {
        $pdo = $db->connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            // Invalid credentials
            header('Location: login.php?error=Invalid username or password.');
            exit();
        }
    } catch (PDOException $e) {
        // Database error
        header('Location: login.php?error=A database error occurred.');
        exit();
    }
} else {
    // Not a POST request, redirect to login
    header('Location: login.php');
    exit();
}
?>
