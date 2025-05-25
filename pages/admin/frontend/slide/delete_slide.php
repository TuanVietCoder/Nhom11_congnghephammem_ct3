<?php
require_once '../../../system/connect.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu có tham số ID được gửi
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Đảm bảo ID là số nguyên
    
    // Kiểm tra xem slide có tồn tại không
    $check = $conn->query("SELECT image_url FROM slides WHERE id = $id");
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $imagePath = '../../../../' . $row['image_url']; // Đường dẫn hình ảnh
        
        // Xóa hình ảnh nếu tồn tại
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        // Xóa slide khỏi database
        $conn->query("DELETE FROM slides WHERE id = $id");
    }
}

// Quay lại trang danh sách slide
header("Location: list_slide.php");
exit();
