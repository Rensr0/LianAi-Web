<?php
include 'db_connect.php';

// 开启会话
session_start();

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理登录表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取用户输入的用户名和密码
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 使用预处理语句来防止 SQL 注入攻击
    $stmt = $conn->prepare("SELECT id FROM users WHERE name=? AND password=?");
    $stmt->bind_param("ss", $username, $password);

    // 执行查询
    $stmt->execute();

    // 获取查询结果
    $result = $stmt->get_result();

    // 检查用户名和密码是否匹配
    if ($result->num_rows > 0) {
        // 登录成功，将用户名存储在会话中
        $_SESSION['username'] = $username;
        
        // 重定向到 index.html 页面
        header("Location: index.php");
        exit();
    } else {
        // 登录失败，显示错误消息或者重新显示登录页面
        echo "用户名或密码错误";
    }
}

// 关闭数据库连接
$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>登录页面</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/login.css" />
	</head>
	<body>

		<div class="login-container">
			<h2>登录</h2>
			<form id="login-form" action="login.php" method="post">
				<input type="text" id="username" name="username" placeholder="账号" required>
				<input type="password" id="password" name="password" placeholder="密码" required>
				<button type="submit">登录</button>
			</form>
		</div>
	</body>
</html>