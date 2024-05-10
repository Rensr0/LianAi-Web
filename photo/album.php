<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>我们的恋爱网站</title>
		<link rel="stylesheet" type="text/css" href="css/album.css" />
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
			<button onclick="goBack()">返回</button>
		<div class="biaoti"><?php
    if (isset($_GET["name"])) {
        $album_name = $_GET["name"];
        $display_name = str_replace('xc_', '', $album_name);
        echo "<h1>$display_name</h1>";
    }
	?>
			<h2>上传图片</h2>
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="album" value="<?php echo isset($_GET["name"]) ? $_GET["name"] : ''; ?>">
				<input type="file" id="fileInput" name="files[]" accept="image/*" multiple>
				<input type="submit" value="上传">
			</form>
		</div>
		<div class="gallery">
			<?php
    if (isset($_GET["name"])) {
        $album_name = $_GET["name"];
        $files = glob("img/$album_name/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($files as $file) {
            echo "<img src='$file' alt='$file' class='gallery-img' width='300'><br>";
        }
    } else {
        echo "未指定相册名称。";
    }
    ?>
		</div>
		<div id="modal" class="modal">
			<span class="close">&times;</span>
			<img id="modal-img" class="modal-content">
		</div>
		<script>
			function goBack() {
				window.history.back();
			}
			document.addEventListener("DOMContentLoaded", function(event) {
				// 获取相册中的所有图片
				var galleryImages = document.querySelectorAll('.gallery-img');

				// 获取模态框、图片和关闭按钮
				var modal = document.getElementById('modal');
				var modalImg = document.getElementById('modal-img');
				var closeBtn = document.getElementsByClassName('close')[0];

				// 为每张图片添加点击事件监听器
				galleryImages.forEach(function(img) {
					img.onclick = function() {
						modal.style.display = 'block'; // 显示模态框
						modalImg.src = this.src; // 设置模态框中的图片源
					}
				});

				// 点击关闭按钮关闭模态框
				closeBtn.onclick = function() {
					modal.style.display = 'none';
				};

				// 点击模态框以外的区域也关闭模态框
				window.onclick = function(event) {
					if (event.target == modal) {
						modal.style.display = 'none';
					}
				};
			});
		</script>
	</body>
</html>