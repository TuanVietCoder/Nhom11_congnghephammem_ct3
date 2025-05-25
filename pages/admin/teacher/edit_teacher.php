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


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Câu truy vấn SQL
    $query = "SELECT * FROM teachers WHERE id = ?";
    
    // Chuẩn bị truy vấn
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);  // Binds the parameter to the prepared statement
    
    // Thực thi câu truy vấn
    $stmt->execute();
    
    // Lấy kết quả
    $result = $stmt->get_result();  // Lấy kết quả từ câu truy vấn
    
    // Kiểm tra nếu có dữ liệu trả về
    $teacher = $result->fetch_assoc();  // Dùng fetch_assoc() để lấy dữ liệu dưới dạng mảng kết hợp
    
    // Nếu không có giáo viên với id đó
    if (!$teacher) {
        echo "Teacher not found!";
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fullName = $_POST['FullName'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $address = $_POST['Address'];

    $query = "UPDATE teachers SET FullName = ?, Phone = ?, Email = ?, Address = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$fullName, $phone, $email, $address, $id]);

    header('Location: list_teacher.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Giáo Viên</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Sửa Giáo Viên</h1>
        <form method="POST" action="edit_teacher.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($teacher['id']); ?>">
            <div class="mb-3">
                <label for="FullName" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="FullName" name="FullName" value="<?php echo htmlspecialchars($teacher['FullName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo htmlspecialchars($teacher['Phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" value="<?php echo htmlspecialchars($teacher['Email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="Address" name="Address" value="<?php echo htmlspecialchars($teacher['Address']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="list_teacher.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>