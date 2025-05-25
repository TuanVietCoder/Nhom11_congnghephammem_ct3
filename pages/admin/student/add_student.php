<?php
require_once '../../system/connect.php'; // Kết nối cơ sở dữ liệu
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách lớp học để điền vào dropdown
$query = "SELECT id, ClassName FROM classes";
$stmt = $conn->prepare($query);
$stmt->execute();
$classes = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-addstudent'])) {
    $fullName = $_POST['FullName'];
    $dateOfBirth = $_POST['DateOfBirth'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];
    $parentName = $_POST['ParentName'];
    $parentPhone = $_POST['ParentPhone'];
    $classID = $_POST['ClassID'];

    // Truy vấn để thêm học sinh vào cơ sở dữ liệu
    $query = "INSERT INTO students (FullName, DateOfBirth, Gender, Address, ParentName, ParentPhone, ClassID) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $fullName, $dateOfBirth, $gender, $address, $parentName, $parentPhone, $classID);

    if ($stmt->execute()) {
        header("Location: list_student.php"); // Chuyển hướng đến trang danh sách học sinh
        exit();
    } else {
        echo "Error: Could not insert the student data.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Học Sinh</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Thêm Mới Học Sinh</h1>
        <form method="POST" action="add_student.php">
            <div class="mb-3">
                <label for="FullName" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="FullName" name="FullName" required>
            </div>
            <div class="mb-3">
                <label for="DateOfBirth" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới tính</label>
                <select class="form-select" name="Gender" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="Address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="Address" name="Address" required>
            </div>
            <div class="mb-3">
                <label for="ParentName" class="form-label">Tên phụ huynh</label>
                <input type="text" class="form-control" id="ParentName" name="ParentName" required>
            </div>
            <div class="mb-3">
                <label for="ParentPhone" class="form-label">Số điện thoại phụ huynh</label>
                <input type="text" class="form-control" id="ParentPhone" name="ParentPhone" required>
            </div>
            <div class="mb-3">
                <label for="ClassID" class="form-label">Lớp học</label>
                <select class="form-select" id="ClassID" name="ClassID" required>
                    <option value="" disabled selected>Chọn lớp học</option>
                    <?php while ($class = $classes->fetch_assoc()) { ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['ClassName']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="btn-addstudent">Lưu</button>
            <a href="list_student.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
