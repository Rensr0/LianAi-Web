<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["album_name"])) {
    $album_name = trim($_POST["album_name"]); // 去除相册名称的空白字符
    if (!empty($album_name)) { // 检查相册名称是否为空
        $album_path = "img/xc_$album_name"; // 在相册名称前加上"xc_"
        if (!is_dir($album_path)) {
            mkdir($album_path);
            echo "<script>alert('相册 \"$album_name\" 创建成功。');window.history.back();</script>";
        } else {
            echo "<script>alert('相册 \"$album_name\" 已存在。');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('相册名称不能为空。');window.history.back();</script>";
    }
}
?>
