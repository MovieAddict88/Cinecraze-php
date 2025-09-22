<?php
require_once 'auth.php'; // Ensures user is logged in
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // --- Validation ---
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = "New passwords do not match.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    if (strlen($new_password) < 8) {
        $_SESSION['message'] = "New password must be at least 8 characters long.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    // --- Database Interaction ---
    // Fetch the current user's hashed password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        // This should not happen if the user is logged in
        $_SESSION['message'] = "User not found.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $hashed_password_from_db = $user['password'];

    // Verify the current password
    if (!password_verify($current_password, $hashed_password_from_db)) {
        $_SESSION['message'] = "Incorrect current password.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    // Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Password changed successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: change_password.php");
        exit();
    } else {
        $_SESSION['message'] = "An error occurred. Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: change_password.php");
        exit();
    }

    $stmt->close();
    $update_stmt->close();
    $conn->close();

} else {
    // If not a POST request, redirect
    header("Location: change_password.php");
    exit();
}
?>
