<?php
include 'db_connect.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取从 JavaScript 发送过来的数据
$username = $_POST['username'];
$content = $_POST['eventContent'];
$date = $_POST['eventDate'];

// 检查日期是否为空
if (!empty($date)) {
    // 将数据插入数据库
    $sql = "INSERT INTO Happened_Oneday (name, content, date) VALUES ('$username', '$content', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "记录插入成功";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "日期为空，不进行插入操作";
}

// 关闭连接
$conn->close();
?>
