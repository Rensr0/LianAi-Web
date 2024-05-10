<?php
include 'db_connect.php';

// Fetch posts from database
$sql = "SELECT id, name, post, date, like_count FROM posts ORDER BY date DESC";
$result = $conn->query($sql);

$posts = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$conn->close();

// Return posts as JSON
header('Content-Type: application/json');
echo json_encode($posts);
?>
