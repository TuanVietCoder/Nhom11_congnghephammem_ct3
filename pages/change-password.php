<?php
require_once './system/connect.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

if (isset($_POST['btn-change-password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['password'];

    // Hàm kiểm tra mật khẩu cũ
    function isOldPasswordCorrect($conn, $id, $oldPassword) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return password_verify($oldPassword, $user['password']);
    }

    if (isOldPasswordCorrect($conn, $id, $oldPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $id);

        if ($updateStmt->execute()) {
            // Hiển thị thông báo alert
            echo "<script>
            alert('Cập nhật mật khẩu thành công!');
            window.location.href = 'profile.php';
            </script>";
            
        } else {
            echo "<script>alert('Đã xảy ra lỗi khi cập nhật mật khẩu!');</script>";
        }
        $updateStmt->close();
    } else {
        echo "<script>alert('Mật khẩu cũ không chính xác!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="form-container">
            <h3 class="text-center">Đổi Mật Khẩu</h3>
            <form action="change-password.php" method="post">
                <div class="mb-3 position-relative">
                    <label for="old-password" class="form-label">Mật khẩu cũ</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="old-password" name="old_password" placeholder="Nhập mật khẩu cũ" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleOldPassword" style="border-radius: 0 4px 4px 0;border: 1px solid #cccccc;">
                            <i class="bi bi-eye-slash" id="toggleOldIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3 position-relative">
                    <label for="new-password" class="form-label">Mật khẩu mới</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new-password" name="password" placeholder="Nhập mật khẩu mới" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword" style="border-radius: 0 4px 4px 0;border: 1px solid #cccccc;">
                            <i class="bi bi-eye-slash" id="toggleNewIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="btn-change-password" class="btn btn-primary">Đổi Mật Khẩu</button>
                    <a href="profile.php" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Hiển thị/ẩn mật khẩu cho "Mật khẩu cũ"
        const toggleOldPassword = document.querySelector("#toggleOldPassword");
        const oldPasswordField = document.querySelector("#old-password");
        const toggleOldIcon = document.querySelector("#toggleOldIcon");

        toggleOldPassword.addEventListener("click", function () {
            const type = oldPasswordField.getAttribute("type") === "password" ? "text" : "password";
            oldPasswordField.setAttribute("type", type);

            toggleOldIcon.classList.toggle("bi-eye");
            toggleOldIcon.classList.toggle("bi-eye-slash");
        });

        // Hiển thị/ẩn mật khẩu cho "Mật khẩu mới"
        const toggleNewPassword = document.querySelector("#toggleNewPassword");
        const newPasswordField = document.querySelector("#new-password");
        const toggleNewIcon = document.querySelector("#toggleNewIcon");

        toggleNewPassword.addEventListener("click", function () {
            const type = newPasswordField.getAttribute("type") === "password" ? "text" : "password";
            newPasswordField.setAttribute("type", type);

            toggleNewIcon.classList.toggle("bi-eye");
            toggleNewIcon.classList.toggle("bi-eye-slash");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
