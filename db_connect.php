<?php
$servername = "localhost"; // 你的数据库主机名
$username = "root"; // 你的数据库用户名
$password = "password"; // 你的数据库密码
$dbname = "dbname"; // 你的数据库名

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
