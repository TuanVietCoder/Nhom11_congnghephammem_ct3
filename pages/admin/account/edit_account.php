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

// Fetch lists of students and teachers from the database
function getStudents($conn) {
    $query = "SELECT id, FullName FROM students ORDER BY FullName";
    $stmt = $conn->query($query);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

function getTeachers($conn) {
    $query = "SELECT id, FullName FROM teachers ORDER BY FullName";
    $stmt = $conn->query($query);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

$students = getStudents($conn);
$teachers = getTeachers($conn);


if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        header("Location: list_account.php");
        exit();
    }
} else {
    header("Location: list_account.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) : $user['password']; // Update password only if it's entered
    $role = intval($_POST['role']);
    $email = trim($_POST['email']);
    
    // Lấy ID học sinh hoặc giáo viên tùy thuộc vào vai trò
    if ($role == 2) {
        $teacherID = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : null;
        $studentID = null;
    } elseif ($role == 3) {
        $studentID = isset($_POST['student_id']) ? intval($_POST['student_id']) : null;
        $teacherID = null;
    } else {
        $studentID = null;
        $teacherID = null;
    }

    // Kiểm tra xem tên đăng nhập có trùng với người khác không
    $query = "SELECT id FROM users WHERE username = ? AND id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $username, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errorMessage = "Tên đăng nhập đã tồn tại!";
    } else {
        // Kiểm tra nếu mã học sinh hoặc giáo viên đã có tài khoản
        if ($role == 2 && $teacherID) {
            $query = "SELECT id FROM users WHERE teacherID = ? AND id != ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $teacherID, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errorMessage = "Mã giáo viên đã có tài khoản!";
            }
        } elseif ($role == 3 && $studentID) {
            $query = "SELECT id FROM users WHERE studentID = ? AND id != ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $studentID, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errorMessage = "Mã học sinh đã có tài khoản!";
            }
        }
    }

    // Cập nhật vào cơ sở dữ liệu nếu không có lỗi
    if (!isset($errorMessage)) {
        if ($username && $email) {
            $query = "UPDATE users SET username = ?, password = ?, role = ?, email = ?, studentID = ?, teacherID = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssisiii", $username, $password, $role, $email, $studentID, $teacherID, $userId);

            if ($stmt->execute()) {
                $successMessage = "Tài khoản đã được cập nhật thành công!";
                
                echo "<script>
                alert('Cập nhật thành công!');
                window.location.href = window.location.href; // Tải lại trang
              </script>";
            } else {
                $errorMessage = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        } else {
            $errorMessage = "Vui lòng điền đầy đủ thông tin!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tài Khoản</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Sửa Tài Khoản</h2>
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Tên Đăng Nhập</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
    <label for="password" class="form-label">Mật Khẩu</label>
    <div class="input-group">
       
        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu mới nếu thay đổi">
        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
            <i class="fas fa-eye" id="eyeIcon"></i>
        </button>
    </div>
</div>
        <div class="mb-3">
            <label for="role" class="form-label">Vai Trò</label>
            <select class="form-select" id="role" name="role" onchange="toggleRelatedField()" required>
                <option value="1" <?php echo ($user['role'] == 1) ? 'selected' : ''; ?>>Quản Trị Viên</option>
                <option value="2" <?php echo ($user['role'] == 2) ? 'selected' : ''; ?>>Giáo Viên</option>
                <option value="3" <?php echo ($user['role'] == 3) ? 'selected' : ''; ?>>Phụ Huynh</option>
            </select>
        </div>
        <div id="studentField" class="mb-3" style="display: <?php echo ($user['role'] == 3) ? 'block' : 'none'; ?>;">
            <label for="student_id" class="form-label">Tên Học Sinh</label>
            <select class="form-select" id="student_id" name="student_id">
                <option value="">-- Chọn Học Sinh --</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['id']; ?>" <?php echo ($user['studentID'] == $student['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($student['FullName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="teacherField" class="mb-3" style="display: <?php echo ($user['role'] == 2) ? 'block' : 'none'; ?>;">
            <label for="teacher_id" class="form-label">Tên Giáo Viên</label>
            <select class="form-select" id="teacher_id" name="teacher_id">
                <option value="">-- Chọn Giáo Viên --</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?php echo $teacher['id']; ?>" <?php echo ($user['teacherID'] == $teacher['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($teacher['FullName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="list_account.php" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === "password") {
            passwordInput.type = "text"; 
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password"; 
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye"); 
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
