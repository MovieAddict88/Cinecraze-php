<?php
// API handler for user interactions like like/dislike

allow_methods('POST');

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    send_error_response(400, "Content ID and action are required.");
}

$id = intval($_GET['id']);
$action = $_GET['action'];

if (!in_array($action, ['like', 'dislike'])) {
    send_error_response(400, "Invalid action.");
}

// Determine which column to increment
$column_to_update = $action === 'like' ? 'likes' : 'dislikes';

// Prepare the SQL statement to avoid SQL injection, even though we control the column name
$sql = "UPDATE content SET {$column_to_update} = {$column_to_update} + 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // Fetch the new counts to return them
        $select_stmt = $conn->prepare("SELECT likes, dislikes FROM content WHERE id = ?");
        $select_stmt->bind_param("i", $id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        $new_counts = $result->fetch_assoc();
        $select_stmt->close();

        send_success_response($new_counts);
    } else {
        send_error_response(404, "Content not found or no changes made.");
    }
} else {
    send_error_response(500, "Database error: " . $stmt->error);
}

$stmt->close();
?>
