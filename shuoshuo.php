<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>我们的恋爱网站</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/rili.css">
		<link rel="stylesheet" type="text/css" href="css/shuoshuo.css">
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
		<div class="love">
			<h1>恋爱说说</h1>
			<form id="post-form">
				<textarea id="post-content" rows="4" cols="50" placeholder="你想说些什么？"></textarea><br>
				<button type="submit">发表</button>
			</form>
			<div id="posts"></div>
		</div>
		<script>
			// 显示帖子的功能
			function displayPosts(posts) {
				const postsContainer = document.getElementById('posts');
				postsContainer.innerHTML = '';
				posts.forEach(post => {
					const postElement = document.createElement('div');
					postElement.classList.add('post');
					postElement.innerHTML = `
			            <p><strong>日期：</strong>${post.date}</p>
			            <p>${post.name}:${post.post}</p>
			            <button class="like-btn" data-id="${post.id}">${post.like_count} 点赞</button>
			        `;
					postsContainer.appendChild(postElement);
				});
			}


			// 从服务器获取帖子的函数
			async function fetchPosts() {
				try {
					const response = await fetch('get_posts.php');
					const posts = await response.json();
					displayPosts(posts);
				} catch (error) {
					console.error('Error fetching posts:', error);
				}
			}

			// 用于处理后期提交的功能
			async function handlePostSubmit(event) {
				event.preventDefault();
				const postContent = document.getElementById('post-content').value;
				if (postContent.trim() !== '') {
					const postData = {
						post: postContent
					};
					try {
						const response = await fetch('save_post.php', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
							},
							body: JSON.stringify(postData),
						});
						if (response.ok) {
							document.getElementById('post-content').value = '';
							fetchPosts();
						} else {
							console.error('Failed to save post:', response.statusText);
						}
					} catch (error) {
						console.error('Error saving post:', error);
					}
				}
			}

			// 处理类似按钮点击的功能
			async function handleLike(event) {
				const postId = event.target.dataset.id;
				if (postId) {
					try {
						const response = await fetch(`like_post.php?id=${postId}`, {
							method: 'PUT',
						});
						if (response.ok) {
							fetchPosts();
						} else {
							console.error('Failed to like post:', response.statusText);
						}
					} catch (error) {
						console.error('Error liking post:', error);
					}
				}
			}

			// 事件侦听器
			document.getElementById('post-form').addEventListener('submit', handlePostSubmit);
			document.addEventListener('click', function(event) {
				if (event.target.classList.contains('like-btn')) {
					handleLike(event);
				}
			});

			// 初始显示
			fetchPosts();
		</script>
	</body>
</html>