<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>我们的恋爱网站</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
		<?php
    // 开启会话
    session_start();

    // 检查用户是否已登录，如果未登录，则重定向到登录页面
    if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }
    ?>
		<nav>
			<ul>
				<li><a href="../index.php">首页</a></li>
				<li><a href="../rili.php">日历</a></li>
				<li><a href="../shuoshuo.php">说说</a></li>
				<li><a href="index.php">相册</a></li>
			</ul>
		</nav>
		<div class="body">
			<div class="biaoti">
				<h1>云相册</h1>
				<h2>创建相册</h2>
				<form action="create_album.php" method="post">
					<input type="text" name="album_name" placeholder="相册名称">
					<input type="submit" value="创建相册">
				</form>
			</div>

			<h2>相册</h2>
			<ul>
    <?php
    $albums = glob("img/*", GLOB_ONLYDIR);
    foreach ($albums as $album) {
        $album_name = basename($album);
        //将显示名称中的“xc_”替换为空字符串
        $display_name = str_replace('xc_', '', $album_name);
        $cover = glob("$album/*.{jpg,jpeg,png,gif}", GLOB_BRACE)[0];
        // 使用原始专辑名称生成链接
        $link = "album.php?name=" . urlencode($album_name);
        echo "<li><a href='$link'><img src='$cover' alt='$display_name' width='100'></a><br>$display_name</li>";
    }
    ?>
</ul>

		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>
