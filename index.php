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
        header("Location: login.php");
        exit();
    }
    ?>
		<nav>
			<ul>
				<li><a href="index.php">首页</a></li>
				<li><a href="rili.php">日历</a></li>
				<li><a href="shuoshuo.php">说说</a></li>
				<li><a href="photo/">相册</a></li>
			</ul>
		</nav>
		<div class="centered">
			<h1>你的姓名</h1>
			<h1 style="color: red;">❤</h1>
			<h1>伴侣的姓名</h1>
			<div>
				<p>已经在一起 <span id="days"></span> 天</p>
				<p> <span id="hours"></span> 小时 <span id="minutes"></span> 分钟 <span id="seconds"></span> 秒</p>
			</div>
			<script>
				// 计算从特定日期到现在的时间差
				function timeSince(dateString) {
					var startDate = new Date(dateString);
					var endDate = new Date();
					var timeDiff = endDate - startDate;

					// 计算时间差中的小时、分钟和秒数
					var seconds = Math.floor((timeDiff / 1000) % 60);
					var minutes = Math.floor((timeDiff / (1000 * 60)) % 60);
					var hours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
					var days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

					return {
						days: days,
						hours: hours,
						minutes: minutes,
						seconds: seconds
					};
				}

				// 更新显示的时间
				function updateDisplay() {
					var startDateString = "2022-10-21"; // 开始日期
					var time = timeSince(startDateString);

					// 更新 HTML 元素的内容
					document.getElementById("days").textContent = time.days;
					document.getElementById("hours").textContent = time.hours;
					document.getElementById("minutes").textContent = time.minutes;
					document.getElementById("seconds").textContent = time.seconds;
				}

				// 每秒钟更新一次显示
				setInterval(updateDisplay, 1000);

				// 页面加载时立即更新一次显示
				updateDisplay();
			</script>

	</body>
</html>