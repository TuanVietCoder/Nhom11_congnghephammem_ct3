<?php


// session_start();

// if (isset($_SESSION['username'])) {
//     header("Location: ../index.php");
//     exit();
// }

// require_once './system/connect.php';

// if (isset($_POST['btn-signup'])) {
//     $fullname = htmlspecialchars($_POST['FullName']);
//     $username = htmlspecialchars($_POST['username']);
//     $password = $_POST['password'];
//     $pswrepeat = $_POST['psw-repeat'];
//     $email= $_POST['email'];
//     $role = 3; // Vai trò mặc định là 3 (Người dùng thông thường)

//     if ($password !== $pswrepeat) {
//         echo "<script>alert('Password không giống nhau. Vui lòng thử lại!');</script>";
//     } else {
//         // Kiểm tra xem username đã tồn tại chưa
//         $check_user = $conn->prepare("SELECT * FROM users WHERE Username = ?");
//         $check_user->bind_param("s", $username);
//         $check_user->execute();
//         $result = $check_user->get_result();

//         if ($result->num_rows > 0) {
//             echo "<script>alert('Tài khoản đã tồn tại. Vui lòng thử lại!');</script>";
//         } else {
//             // Mã hóa mật khẩu
//             $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//             // Thêm người dùng vào cơ sở dữ liệu
//             $stmt = $conn->prepare("INSERT INTO users (Username, Password, FullName, Role,email) VALUES (?, ?, ?, ?,?)");
//             $stmt->bind_param("sssis", $username, $hashed_password, $fullname, $role, $email);

//             if ($stmt->execute()) {
//                 echo "<script>alert('Đăng ký thành công. Hãy đăng nhập!');</script>";
//                 header("Location: login.php");
//                 exit();
//             } else {
//                 echo "<script>alert('Đăng ký thất bại. Vui lòng thử lại!');</script>";
//             }
            
//             $stmt->close();
//         }

//         $check_user->close();
//     }
// }

session_start();

// Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once './system/connect.php';

if (isset($_POST['btn-signup'])) {
    $studentID = htmlspecialchars($_POST['StudentID']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $pswrepeat = $_POST['psw-repeat'];
    $email = $_POST['email'];
    $role = 3; // Vai trò mặc định là 3 (Người dùng thông thường)

    // Kiểm tra mật khẩu nhập lại
    if ($password !== $pswrepeat) {
        echo "<script>alert('Password không giống nhau. Vui lòng thử lại!');</script>";
    } else {
        // Kiểm tra tên đăng nhập trùng lặp
        $check_username = $conn->prepare("SELECT id FROM users WHERE Username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $username_result = $check_username->get_result();

        if ($username_result->num_rows > 0) {
            echo "<script>alert('Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác!');</script>";
        } else {
            // Kiểm tra mã học sinh có tồn tại trong bảng students
            $check_student = $conn->prepare("SELECT id FROM students WHERE studentID = ?");
            $check_student->bind_param("s", $studentID);
            $check_student->execute();
            $student_result = $check_student->get_result();

            if ($student_result->num_rows === 0) {
                echo "<script>alert('Mã học sinh không tồn tại. Vui lòng kiểm tra lại!');</script>";
            } else {
                $student_row = $student_result->fetch_assoc();
                $student_db_id = $student_row['id'];

                // Kiểm tra xem mã học sinh đã được liên kết với tài khoản nào chưa
                $check_user = $conn->prepare("SELECT * FROM users WHERE studentID = ?");
                $check_user->bind_param("i", $student_db_id);
                $check_user->execute();
                $user_result = $check_user->get_result();

                if ($user_result->num_rows > 0) {
                    echo "<script>alert('Mã học sinh này đã được đăng ký. Vui lòng thử lại!');</script>";
                } else {
                    // Mã hóa mật khẩu
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Thêm người dùng vào cơ sở dữ liệu
                    $stmt = $conn->prepare("INSERT INTO users (Username, Password, Role, email, studentID) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssisi", $username, $hashed_password, $role, $email, $student_db_id);

                    if ($stmt->execute()) {
                        echo "<script>alert('Đăng ký thành công. Hãy đăng nhập!');</script>";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "<script>alert('Đăng ký thất bại. Vui lòng thử lại!');</script>";
                    }
                    
                    $stmt->close();
                }

                $check_user->close();
            }

            $check_student->close();
        }

        $check_username->close();
    }
}

$conn->close();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .input-group .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }

    .input-group .btn i {
        font-size: 18px;
    }
    button#togglePassword {
        border: 1px solid #dbdbdb;
    }
    button#toggleRepeatPassword {
        border: 1px solid #dbdbdb;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="form-container">
            <h3 class="text-center mb-4">Đăng kí</h3>
            <form action="signup.php" method="post">
                <div class="mb-3">
                    <label for="IDstudent" class="form-label">Mã học sinh</label>
                    <input type="text" class="form-control" id="StudentID" name="StudentID" placeholder="Nhập mã học sinh" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập của bạn" required>
                </div>

             
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="psw-repeat" class="form-label">Nhập lại mật khẩu</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="psw-repeat" name="psw-repeat" placeholder="Nhập lại mật khẩu" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleRepeatPassword">
                            <i class="bi bi-eye-slash" id="toggleRepeatIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="psw-repeat" class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="password" name="email" placeholder="Nhập email của bạn" required>                   
                    </div>
                </div>


                <!-- <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div> -->

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" name="btn-signup">Đăng kí</button>
                    <a href="../index.php" class="btn btn-secondary">Hủy</a>
                </div>
                <div class="text-center mt-3">
                    <small>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></small>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        // Đổi loại input giữa 'password' và 'text'
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Đổi icon
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    document.getElementById('toggleRepeatPassword').addEventListener('click', function () {
        const repeatPasswordInput = document.getElementById('psw-repeat');
        const repeatIcon = document.getElementById('toggleRepeatIcon');

        // Đổi loại input giữa 'password' và 'text'
        const type = repeatPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        repeatPasswordInput.setAttribute('type', type);

        // Đổi icon
        repeatIcon.classList.toggle('bi-eye');
        repeatIcon.classList.toggle('bi-eye-slash');
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
