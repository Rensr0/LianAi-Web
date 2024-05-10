<?php
include 'db_connect.php';

// 开启会话
session_start();

// 获取当前登录用户的用户名
if(isset($_SESSION['username'])) {
    $name = $_SESSION['username'];
} else {
    // 如果未登录，则设定一个默认用户名或者处理未登录的情况
    $name = "Unknown";
}

// 获取帖子内容
$data = json_decode(file_get_contents('php://input'), true);
$postContent = $data['post'];

// 准备并绑定 SQL 语句
$stmt = $conn->prepare("INSERT INTO posts (name, post) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $post);
$post = $postContent;

// 执行 SQL 语句
if ($stmt->execute()) {
    http_response_code(200);
} else {
    http_response_code(500);
    echo "Error: " . $conn->error;
}

// 关闭语句和连接
$stmt->close();
$conn->close();
?>
