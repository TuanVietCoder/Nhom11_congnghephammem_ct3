<?php
require_once '../../system/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Kiểm tra nếu có ID của tài khoản cần xóa
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Kiểm tra  hợp lệ
    if ($userId > 0) {
        
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            // Sau khi xóa, chuyển hướng về trang danh sách tài khoản
            $_SESSION['message'] = "Tài khoản đã được xóa thành công.";
            header("Location: list_account.php");
            exit();
        } else {
       
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa tài khoản.";
        }
    } else {
       
        $_SESSION['error'] = "ID tài khoản không hợp lệ.";
    }
} else {
    // Nếu không có ID trong URL
    $_SESSION['error'] = "Không có tài khoản nào được chọn để xóa.";
}

// Quay lại trang danh sách nếu có lỗi
header("Location: list_account.php");
exit();
?>
