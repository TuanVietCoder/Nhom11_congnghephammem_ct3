<?php
require_once '../../../system/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Kiểm tra xem có ID không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID không hợp lệ.";
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin ảnh để xóa khỏi thư mục uploads
$sql_get_image = "SELECT image FROM curriculum WHERE id = ?";
$stmt = $conn->prepare($sql_get_image);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $image_path = "../../../../images/uploads/" . $row['image'];
    
    // Xóa ảnh nếu tồn tại
    if (file_exists($image_path) && !empty($row['image'])) {
        unlink($image_path);
    }
}

// Xóa bản ghi khỏi database
$sql_delete = "DELETE FROM curriculum WHERE id = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Xóa thành công!";
    header("Location: curriculum.php");
    exit();
} else {
    echo "Lỗi khi xóa bản ghi.";
}
?>
