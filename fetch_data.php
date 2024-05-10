<?php
include 'db_connect.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取传入的日期并确保是有效的日期格式
$date = isset($_POST['date']) ? $_POST['date'] : '';
if (!strtotime($date)) {
    // 输出JSON格式的错误信息
    echo json_encode(array("error" => "错误的日期格式"));
    exit(); // 停止脚本执行
}

// 使用预处理语句来防止 SQL 注入攻击
$sql = "SELECT id, name, content FROM Happened_Oneday WHERE date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$data = array();

if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    // 返回JSON格式的数据
    echo json_encode($data);
} else {
}

$stmt->close();
$conn->close();
?>
