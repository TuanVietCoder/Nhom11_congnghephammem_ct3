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
    $query = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "<script>alert('Student not found!'); window.location.href='edit_student.php?id=$id';</script>";
        exit;
    }

    // Lấy danh sách các lớp học
    $classQuery = "SELECT id, ClassName FROM classes";
    $classResult = $conn->query($classQuery);
    if (!$classResult) {
        die("Error fetching classes: " . $conn->error);
    }
    $classes = $classResult->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $studentId = $_POST['StudentID'];
    $fullName = $_POST['FullName'];
    $classId = $_POST['ClassID'];
    $dateOfBirth = $_POST['DateOfBirth'];
    $gender = $_POST['Gender'];
    $parentName = $_POST['ParentName'];
    $parentPhone = $_POST['ParentPhone'];
    $address = $_POST['Address'];

    // Kiểm tra độ dài của StudentID
    if (strlen($studentId) > 12) {
        echo "<script>alert('Mã học sinh không hợp lệ, tối đa 12 ký tự!'); window.location.href='edit_student.php?id=$id';</script>";
        exit;
    }

    // Kiểm tra trùng lặp StudentID
    $checkQuery = "SELECT COUNT(*) FROM students WHERE StudentID = ? AND id != ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("si", $studentId, $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_row();
    
    if ($checkRow[0] > 0) {
        echo "<script>alert('Mã học sinh đã tồn tại!'); window.location.href='edit_student.php?id=$id';</script>";
        exit;
    }

    // Cập nhật thông tin học sinh
    $query = "UPDATE students SET FullName = ?, ClassID = ?, DateOfBirth = ?, Gender = ?, ParentName = ?, ParentPhone = ?, Address = ?, StudentID=? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssi", $fullName, $classId, $dateOfBirth, $gender, $parentName, $parentPhone, $address, $studentId, $id);
    $stmt->execute();

    echo "<script>window.location.href = 'list_student.php';</script>";
    exit;
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Học Sinh</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Sửa Học Sinh</h1>
        <form method="POST" action="edit_student.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
            <div class="mb-3">
                <label for="StudentID" class="form-label">Mã học sinh</label>
                <input type="text" class="form-control" id="StudentID" name="StudentID" value="<?php echo htmlspecialchars($student['StudentID']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="FullName" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="FullName" name="FullName" value="<?php echo htmlspecialchars($student['FullName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ClassID" class="form-label">Lớp</label>
                <select class="form-control" id="ClassID" name="ClassID" required>
                    <option value="">Chọn lớp</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['id']); ?>"
                            <?php echo ($class['id'] == $student['ClassID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['ClassName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="DateOfBirth" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" value="<?php echo htmlspecialchars($student['DateOfBirth']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Gender" class="form-label">Giới tính</label>
                <select class="form-control" id="Gender" name="Gender" required>
                    <option value="">Chọn giới tính</option>
                    <option value="Male" <?php echo ($student['Gender'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="Female" <?php echo ($student['Gender'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                    <option value="Other" <?php echo ($student['Gender'] == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="ParentName" class="form-label">Tên phụ huynh</label>
                <input type="text" class="form-control" id="ParentName" name="ParentName" value="<?php echo htmlspecialchars($student['ParentName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ParentPhone" class="form-label">Số điện thoại phụ huynh</label>
                <input type="tel" class="form-control" id="ParentPhone" name="ParentPhone" value="<?php echo htmlspecialchars($student['ParentPhone']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="Address" name="Address" value="<?php echo htmlspecialchars($student['Address']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="list_student.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
