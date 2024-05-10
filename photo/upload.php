<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["album"])) {
    $albumName = $_POST["album"];
    $targetDir = "img/$albumName/";

    // 检查相册目录是否存在，不存在则创建
    if (!is_dir($targetDir)) {
        mkdir($targetDir);
    }

    if (isset($_FILES["file"])) {
        // 处理单个文件上传
        $file = $_FILES["file"];
        $uploadResult = handleFileUpload($file, $targetDir);
        echo "<script>alert('$uploadResult');window.history.back();</script>";
    } elseif (isset($_FILES["files"])) {
        // 处理多个文件上传
        $files = $_FILES["files"];
        $uploadResult = handleMultipleFileUpload($files, $targetDir);
        echo "<script>alert('$uploadResult');window.history.back();</script>";
    } else {
        echo "<script>alert('没有文件被上传。');window.history.back();</script>";
    }
}

function handleFileUpload($file, $targetDir) {
    $fileName = basename($file["name"]);
    $targetFile = $targetDir . $fileName;

    // 检查图片文件是否真的是图片
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return "文件 $fileName 不是图片。";
    }

    // 检查文件是否已存在
    if (file_exists($targetFile)) {
        return "文件 $fileName 已存在。";
    }

    // 检查文件大小
    if ($file["size"] > 10000000) {
        return "文件 $fileName 太大。";
    }

    // 允许的文件格式
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        return "只允许 JPG, JPEG, PNG & GIF 文件。";
    }

    // 移动文件到相册目录
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return "文件 $fileName 上传成功。";
    } else {
        return "上传文件 $fileName 失败。";
    }
}

function handleMultipleFileUpload($files, $targetDir) {
    $uploadResult = "";
    foreach ($files["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $tmpName = $files["tmp_name"][$key];
            $fileName = basename($files["name"][$key]);
            $targetFile = $targetDir . $fileName;

            // 移动文件到相册目录
            if (move_uploaded_file($tmpName, $targetFile)) {
                $uploadResult .= "文件 $fileName 上传成功。";
            } else {
                $uploadResult .= "上传文件 $fileName 失败。";
            }
        }
    }
    return $uploadResult;
}
?>
