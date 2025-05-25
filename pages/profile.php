<?php
require_once './system/connect.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id = $_SESSION['id'];

// Truy vấn thông tin người dùng dựa trên role
if ($role == 1) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} elseif ($role == 3) {
    $stmt = $conn->prepare("SELECT u.*, s.FullName AS student_name, s.DateOfBirth, s.Gender, s.Address, s.ParentName, s.ParentPhone, c.ClassName, s.StudentID, u.email 
                            FROM users u
                            LEFT JOIN students s ON u.studentID = s.id
                            LEFT JOIN classes c ON s.ClassID = c.id
                            WHERE u.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} elseif ($role == 2) {
    $stmt = $conn->prepare("SELECT u.*, t.FullName AS teacher_name, t.Phone, t.Email, t.Address, t.HireDate
                            FROM users u
                            LEFT JOIN teachers t ON u.teacherID = t.id
                            WHERE u.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: ../index.php");
    exit();
}

// Xử lý cập nhật thông tin khi người dùng ấn nút Cập nhật
if (isset($_POST['btn-updateprofile'])) {
    // Cập nhật thông tin email cho role = 3
    if ($role == 3) {
        $newEmail = $_POST['email'];
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $newEmail, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Cập nhật thông tin ParentPhone cho role = 3
    if ($role == 3) {
        $newParentPhone = $_POST['parentphone'];
        $stmt = $conn->prepare("UPDATE students SET ParentPhone = ? WHERE id = ?");
        $stmt->bind_param("si", $newParentPhone, $user['studentID']);
        $stmt->execute();
        $stmt->close();

    }

    // Cập nhật thông tin Phone và Address cho role = 2
    if ($role == 2) {
        $newPhone = $_POST['phone'];
        $newAddress = $_POST['address'];
        $stmt = $conn->prepare("UPDATE teachers SET Phone = ?, Address = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newPhone, $newAddress, $user['teacherID']);
        $stmt->execute();
        $stmt->close();
    }

    // Thông báo cập nhật thành công và chuyển hướng về trang profile
    echo "<script>
            alert('Cập nhật thành công!');
            window.location.href = 'profile.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Mầm Nan Xanh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="form-container">
            <h3 class="text-center">Thông tin cá nhân</h3>
            <form action="profile.php" method="post">
                <!-- Hiển thị tên người dùng -->
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" disabled>
                </div>

                <!-- Hiển thị FullName (Admin hoặc Học sinh) -->
                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?= $role == 1 ? 'Admin' : ($role == 3 ? $user['student_name'] : $user['teacher_name']); ?>" required disabled>
                </div>

                <!-- Hiển thị email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>" >
                </div>

                <!-- Hiển thị thông tin tùy theo vai trò -->
                <?php if ($role == 3): ?>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= $user['DateOfBirth']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Giới tính</label>
                        <input type="text" class="form-control" id="gender" name="gender" value="<?= $user['Gender']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $user['Address']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="parentname" class="form-label">Tên phụ huynh</label>
                        <input type="text" class="form-control" id="parentname" name="parentname" value="<?= $user['ParentName']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="parentphone" class="form-label">Số điện thoại phụ huynh</label>
                        <input type="text" class="form-control" id="parentphone" name="parentphone" value="<?= $user['ParentPhone']; ?>" >
                    </div>
                    <div class="mb-3">
                        <label for="classid" class="form-label">Lớp</label>
                        <input type="text" class="form-control" id="classid" name="classid" value="<?= $user['ClassName']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="studentid" class="form-label">Mã học sinh</label>
                        <input type="text" class="form-control" id="studentid" name="studentid" value="<?= $user['StudentID']; ?>" disabled>
                    </div>
                <?php endif; ?>

                <!-- Hiển thị thông tin giáo viên -->
                <?php if ($role == 2): ?>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['Phone']; ?>" >
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $user['Address']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="hiredate" class="form-label">Ngày bắt đầu làm việc</label>
                        <input type="date" class="form-control" id="hiredate" name="hiredate" value="<?= $user['HireDate']; ?>" required disabled>
                    </div>
                <?php endif; ?>

                <!-- Nút đổi mật khẩu -->
                <div class="d-flex gap-2" style="justify-content: center;">
                    <button type="submit" name="btn-updateprofile" class="btn btn-primary">Cập nhật</button>
                    <a href="change-password.php" class="btn btn-warning">Đổi mật khẩu</a>
                    <a href="../index.php" class="btn btn-secondary">Quay lại trang chủ</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
