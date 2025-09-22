<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: login.php");
        exit();
    }

    // Prepare and execute the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Redirect to the admin dashboard
            header("Location: index.php");
            exit();
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        // No user found
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, redirect to login page
    header("Location: login.php");
    exit();
}
?>
