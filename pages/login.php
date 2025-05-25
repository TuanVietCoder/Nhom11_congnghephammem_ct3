<?php
require_once './system/connect.php';
session_start();

if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn thông tin người dùng bằng prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username); // Ràng buộc biến $username với kiểu dữ liệu 'string'
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Kiểm tra mật khẩu với mật khẩu đã mã hóa
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['id'] = $user['id'] ?? null; // Kiểm tra và gán 'id'
            $_SESSION['teacherID'] = $user['teacherID'] ?? null;
            $_SESSION['studentID'] = $user['studentID'] ?? null;

            // Nếu là giáo viên, lấy danh sách học sinh mà giáo viên đó phụ trách
            if ($user['role'] == 2) {
                
                header("Location: ./teacher/attendance.php"); // Chuyển đến trang giáo viên
            } elseif ($user['role'] == 1) {
                // Nếu là admin, chuyển đến dashboard admin
                header("Location: ./admin/dashboard.php");
            } else {
                // Nếu là người dùng thông thường, chuyển đến trang chính
                header("Location: ./student/attendance.php");
            }
            exit();
        } else {
            echo "<script>alert('Sai mật khẩu. Vui lòng thử lại!');</script>";
        }
    } else {
        echo "<script>alert('Tên đăng nhập không tồn tại. Vui lòng kiểm tra lại!');</script>";
    }

    $stmt->close(); // Đóng prepared statement
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mầm Non 11</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

   <style>
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 100px;
            margin: 0 auto 20px;
            display: block;
        }
        button#togglePassword {
            border: 1px solid #dbdbdb;
        }
        
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="form-container">
            <form action="login.php" method="post">
                <img src="../images/logo.png" alt="Avatar" class="avatar " style="width: 170px;">
                <h3 class="text-center">Đăng nhập</h3>

                <div class="mb-3">
                    <label for="username" class="form-label">Tên tài khoản</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên tài khoản của bạn"
                        required>
                </div>

                
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-radius: 0 4px 4px 0;">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>


                <!-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div> -->

                <div class="d-grid gap-2">
                    <button type="submit" name="btn-login" class="btn btn-primary">Đăng nhập</button>
                    <a href="../index.php" class="btn btn-secondary">Hủy</a>
                </div>

                <div class="text-center mt-3">
                    <a href="#">Quên mật khẩu?</a> |
                    <a href="signup.php">Đăng kí</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordField = document.querySelector("#password");
    const toggleIcon = document.querySelector("#toggleIcon");

    togglePassword.addEventListener("click", function () {
        // Đổi type của ô password giữa 'password' và 'text'
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);

        // Đổi icon hiển thị
        toggleIcon.classList.toggle("bi-eye");
        toggleIcon.classList.toggle("bi-eye-slash");
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>