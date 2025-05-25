<?php
require_once '../../system/connect.php';
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra quyền truy cập của người dùng
if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $fullName = $_POST['FullName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];
    $hireDate = $_POST['HireDate'];  // Trường HireDate

    // Câu truy vấn SQL để thêm mới giáo viên
    $query = "INSERT INTO teachers (FullName, Phone, Email, Address, HireDate) 
              VALUES (?, ?, ?, ?, ?)";
    
    // Chuẩn bị và thực thi câu truy vấn
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $fullName, $phone, $email, $address, $hireDate);
    $stmt->execute();
    
    // Chuyển hướng về danh sách giáo viên sau khi thêm
    header('Location: list_teacher.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Giáo Viên</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Thêm Giáo Viên</h1>
        <form method="POST" action="add_teacher.php">
            <div class="mb-3">
                <label for="FullName" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="FullName" name="FullName" required>
            </div>
            <div class="mb-3">
                <label for="Phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="Phone" name="Phone" required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" required>
            </div>
            <div class="mb-3">
                <label for="Address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="Address" name="Address" required>
            </div>
            <div class="mb-3">
                <label for="HireDate" class="form-label">Ngày tuyển dụng</label>
                <input type="date" class="form-control" id="HireDate" name="HireDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="list_teacher.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
