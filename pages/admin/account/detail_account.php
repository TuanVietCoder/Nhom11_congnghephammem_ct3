<?php
require_once '../../system/connect.php';
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra nếu người dùng không có quyền quản trị
if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Lấy ID từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID không hợp lệ!";
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin tài khoản dựa trên ID
function getUserById($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$user = getUserById($conn, $id);

if (!$user) {
    echo "Tài khoản không tồn tại!";
    exit();
}

$teacherName = '';
if ($user['role'] == 2) {
    $teacherQuery = "SELECT FullName FROM teachers WHERE id = ?";
    $teacherStmt = $conn->prepare($teacherQuery);
    $teacherStmt->bind_param("i", $user['teacherID']);
    $teacherStmt->execute();
    $teacherResult = $teacherStmt->get_result();
    $teacherData = $teacherResult->fetch_assoc();
    $teacherName = $teacherData['FullName'] ?? 'Không tìm thấy giáo viên';
}

// Nếu vai trò là phụ huynh, lấy tên học sinh từ bảng students
$studentName = '';
if ($user['role'] == 3) {
    $studentQuery = "SELECT FullName FROM students WHERE id = ?";
    $studentStmt = $conn->prepare($studentQuery);
    $studentStmt->bind_param("i", $user['studentID']);
    $studentStmt->execute();
    $studentResult = $studentStmt->get_result();
    $studentData = $studentResult->fetch_assoc();
    $studentName = $studentData['FullName'] ?? 'Không tìm thấy học sinh';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Tài Khoản</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Chi Tiết Tài Khoản</h1>
        <div class="card">
        <div class="card-body">
    <h5 class="card-title">Thông tin chi tiết</h5>
    <table  cellspacing="0" cellpadding="5">
    <tr>
        <td><strong>ID:</strong></td>
        <td><?php echo htmlspecialchars($user['id'] ?? ''); ?></td>
    </tr>
    <tr>
        <td><strong>Tên đăng nhập:</strong></td>
        <td><?php echo htmlspecialchars($user['username'] ?? ''); ?></td>
    </tr>
    
   
    <tr>
        <td><strong>Vai trò:</strong></td>
        <td>
            <?php 
                if ($user['role'] == 1) {
                    echo 'Quản trị viên';
                } elseif ($user['role'] == 2) {
                    echo 'Giáo viên';
                } elseif ($user['role'] == 3) {
                    echo 'Phụ huynh';
                } else {
                    echo 'Người dùng';
                }
            ?>
        </td>
    </tr>
    <?php if ($user['role'] == 2): ?>
    <tr>
        <td><strong>Tên giáo viên:</strong></td>
        <td><?php echo htmlspecialchars($teacherName ?? ''); ?></td>
    </tr>
    <?php elseif ($user['role'] == 3): ?>
    <tr>
        <td><strong>Tên học sinh:</strong></td>
        <td><?php echo htmlspecialchars($studentName ?? ''); ?></td>
    </tr>
    
    <?php endif; ?>
    <tr>
        <td><strong>Email:</strong></td>
        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
    </tr>
    <tr>
        <td><strong>Ngày tạo:</strong></td>
        <td><?php echo htmlspecialchars($user['DateCreated'] ?? ''); ?></td>
    </tr>
</table>

    <a href="list_account.php" class="btn btn-secondary">Quay lại</a>
    <a href="edit_account.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">Chỉnh sửa</a>
</div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
