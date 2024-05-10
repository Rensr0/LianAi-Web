<?php
include 'db_connect.php';

// Get post ID from request
$postId = $_GET['id'];

// Increment like count for the post
$sql = "UPDATE posts SET like_count = like_count + 1 WHERE id = $postId";
if ($conn->query($sql) === TRUE) {
    http_response_code(200);
} else {
    http_response_code(500);
}

$conn->close();
?>
