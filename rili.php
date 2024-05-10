<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>我们的恋爱网站</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/rili.css">
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
		<div class="rili">
			<h1>恋爱日历</h1>
			<div id="currentDate"></div>
			<div id="insertEventPopup">
				<h2>插入日历</h2>
				<span class="closeButton" onclick="togglePopup()">×</span>
				<input type="date" id="eventDate">
				<textarea id="eventContent" placeholder="事件内容"></textarea>
				<button id="submitEventButton" onclick="insertEvent()">提交</button>
			</div>
			<button id="insertEventButton" onclick="togglePopup()">+</button>
			<table id="calendar">
				<thead>
					<tr>
						<th colspan="7" id="monthYear"></th>
					</tr>
					<tr>
						<th colspan="7" id="selectDate">
							<select id="selectYear"></select>年
							<select id="selectMonth"></select>月
							<button onclick="goToSelectedDate()">跳转</button>
						</th>
					</tr>
					<tr>
						<th>星期日</th>
						<th>星期一</th>
						<th>星期二</th>
						<th>星期三</th>
						<th>星期四</th>
						<th>星期五</th>
						<th>星期六</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<br />
		<div id="popup" class="popup">
			<span id="closePopup" style="cursor: pointer; position: absolute; top: 5px; right: 15px;">✖</span>
			<div id="selectedDate"></div>
		</div>
		<script>
			function togglePopup() {
				var popup = document.getElementById("insertEventPopup");
				if (popup.style.display === "block") {
					popup.style.display = "none";
				} else {
					popup.style.display = "block";
				}
			}
			
			window.onclick = function(event) {
				var popup = document.getElementById("insertEventPopup");
				if (event.target == popup) {
					popup.style.display = "none";
				}
			}
		</script>
		<script>
			const today = new Date();
			let currentMonth = today.getMonth();
			let currentYear = today.getFullYear();

			// 初始化下拉菜单
			initSelect();

			displayCalendar(currentMonth, currentYear);

			function displayCalendar(month, year) {
				const firstDay = new Date(year, month, 1);
				const lastDay = new Date(year, month + 1, 0);
				const startingDay = firstDay.getDay();
				const monthLength = lastDay.getDate();

				const table = document.getElementById("calendar").getElementsByTagName("tbody")[0];
				table.innerHTML = "";

				let row = table.insertRow();
				let cellCount = 0;

				for (let i = 0; i < startingDay; i++) {
					row.insertCell();
					cellCount++;
				}

				for (let day = 1; day <= monthLength; day++) {
					const cell = row.insertCell();
					cell.innerHTML = day;
					cell.dataset.year = year;
					cell.dataset.month = month;
					cell.dataset.day = day;
					cell.addEventListener("click", function() {
						const year = parseInt(this.dataset.year);
						const month = parseInt(this.dataset.month); // 不再加1
						const day = parseInt(this.dataset.day);

						// 构建日期字符串
						const selectedDate = year + '-' + (month < 9 ? '0' + (month + 1) : (month + 1)) + '-' + (day <
							10 ? '0' +
							day : day);

						// 发送 POST 请求
						fetch('fetch_data.php', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/x-www-form-urlencoded'
								},
								body: 'date=' + selectedDate
							})
							.then(response => response.json())
							.then(data => {
								// 检查是否有错误
								if (data.hasOwnProperty('error')) {
									// 显示错误信息
									document.getElementById('selectedDate').innerHTML = data.error;
								} else {
									// 显示日期对应的内容
									if (data.length > 0) {
										const selectedDate = new Date(year, month, day);
										const formattedDate = selectedDate.getFullYear() + '年' + (selectedDate
											.getMonth() + 1) + '月' + selectedDate.getDate() + '日';
										document.getElementById('selectedDate').innerHTML = "你选择的日期是：" +
											formattedDate + "<br/>" + "今天发生了：" + data[0].content + " - " + data[0]
											.name;
										document.getElementById("popup").style.display = "block";
									} else {

									}
								}
							})
							.catch(error => {
								console.error('Error:', error);
								// 如果请求过程中出现错误，显示错误信息
								const selectedDate = new Date(year, month, day);
								const formattedDate = selectedDate.getFullYear() + '年' + (selectedDate
									.getMonth() + 1) + '月' + selectedDate.getDate() + '日';
								document.getElementById('selectedDate').innerHTML = "你选择的日期是：" +
									formattedDate + "<br/>" + "今天没有发生事情哦~";
								document.getElementById("popup").style.display = "block";
							});
					});
					cellCount++;
					if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
						cell.classList.add("today");
					}
					if (cellCount % 7 === 0) {
						row = table.insertRow();
					}
				}

				document.getElementById("currentDate").innerHTML =
					`今天是：${today.getFullYear()}年${today.getMonth() + 1}月${today.getDate()}日`;
				document.getElementById("monthYear").innerHTML = `${year}年${month + 1}月`;

			}

			function insertEvent() {
				const eventContent = document.getElementById("eventContent").value;
				const eventDate = document.getElementById("eventDate").value;
				const username = "<?php echo $_SESSION['username']; ?>"; // 获取当前登录用户的用户名

				// 发送 POST 请求
				fetch('insert_data.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: 'username=' + username + '&eventContent=' + eventContent +
							'&eventDate=' + eventDate
					})
					.then(response => response.text())
					.then(data => {
						alert(data); // 显示插入结果
						// 清空输入框
						document.getElementById("eventContent").value = "";
						document.getElementById("eventDate").value = ""; // 清空日期选择器
					})
					.catch(error => console.error('Error:', error));
			}


			function initSelect() {
				const selectYear = document.getElementById("selectYear");
				const selectMonth = document.getElementById("selectMonth");

				for (let year = today.getFullYear() - 10; year <= today.getFullYear() + 1; year++) {
					const option = document.createElement("option");
					option.value = year;
					option.text = year;
					selectYear.appendChild(option);
				}

				for (let month = 1; month <= 12; month++) {
					const option = document.createElement("option");
					option.value = month - 1;
					option.text = month;
					selectMonth.appendChild(option);
				}

				selectYear.value = currentYear;
				selectMonth.value = currentMonth;
			}

			function goToSelectedDate() {
				const selectedYear = parseInt(document.getElementById("selectYear").value);
				const selectedMonth = parseInt(document.getElementById("selectMonth").value);
				displayCalendar(selectedMonth, selectedYear);
			}

			document.getElementById("closePopup").addEventListener("click", function() {
				document.getElementById("popup").style.display = "none";
			});
		</script>
	</body>
</html>