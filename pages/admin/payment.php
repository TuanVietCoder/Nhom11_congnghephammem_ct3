<?php
session_start();
require_once('../system/connect.php');

// Kiểm tra đăng nhập và vai trò
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
    header("Location: ../../index.php");
    exit();
}

$errors = [];
$success = "";

// Xử lý thêm/sửa/xóa ngân hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $bank_id = isset($_POST['bank_id']) ? (int)$_POST['bank_id'] : 0;
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);
    $account_holder = trim($_POST['account_holder']);

    // Xử lý upload mã QR
    $qr_code_path = "";
    if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['qr_code']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES['qr_code']['size'];
        $file_tmp = $_FILES['qr_code']['tmp_name'];

        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif).";
        } elseif ($file_size > 5 * 1024 * 1024) {
            $errors[] = "Kích thước file ảnh không được vượt quá 5MB.";
        } else {
            $upload_dir = dirname(__DIR__) . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_file_name = uniqid() . '.' . $file_ext;
            $qr_code_path = 'uploads/' . $new_file_name;
            if (!move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $errors[] = "Không thể upload mã QR.";
            }
        }
    } elseif ($_POST['action'] == 'edit' && empty($_FILES['qr_code']['name'])) {
        $result = $conn->query("SELECT qr_code_path FROM banks WHERE id = $bank_id");
        if ($row = $result->fetch_assoc()) {
            $qr_code_path = $row['qr_code_path'];
        }
    } else {
        $errors[] = "Mã QR là bắt buộc.";
    }

    // Thêm hoặc sửa ngân hàng
    if (empty($errors)) {
        if ($_POST['action'] == 'add') {
            $sql = "INSERT INTO banks (bank_name, account_number, account_holder, qr_code_path) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $bank_name, $account_number, $account_holder, $qr_code_path);
        } elseif ($_POST['action'] == 'edit') {
            $sql = "UPDATE banks SET bank_name = ?, account_number = ?, account_holder = ?, qr_code_path = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $bank_name, $account_number, $account_holder, $qr_code_path, $bank_id);
        }

        if ($stmt->execute()) {
            $success = $_POST['action'] == 'add' ? "Thêm ngân hàng thành công!" : "Cập nhật ngân hàng thành công!";
        } else {
            $errors[] = "Lỗi khi lưu thông tin: " . $conn->error;
        }
        $stmt->close();
    }
}

// Xóa ngân hàng
if (isset($_GET['delete'])) {
    $bank_id = (int)$_GET['delete'];
    $result = $conn->query("SELECT qr_code_path FROM banks WHERE id = $bank_id");
    if ($row = $result->fetch_assoc()) {
        $file_path = dirname(__DIR__) . '/' . $row['qr_code_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $sql = "DELETE FROM banks WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bank_id);
        if ($stmt->execute()) {
            $success = "Xóa ngân hàng thành công!";
        } else {
            $errors[] = "Lỗi khi xóa: " . $conn->error;
        }
        $stmt->close();
    }
}

// Lấy danh sách ngân hàng
$banks = [];
$result = $conn->query("SELECT id, bank_name, account_number, account_holder, qr_code_path FROM banks");
while ($row = $result->fetch_assoc()) {
    $banks[] = $row;
}

// Lấy lịch sử thanh toán
$payments = [];
$result = $conn->query("SELECT p.id, p.student_id, p.amount, p.payment_note, p.payment_date, b.bank_name 
                       FROM payments p 
                       LEFT JOIN banks b ON p.bank_id = b.id 
                       ORDER BY p.payment_date DESC LIMIT 10");
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thanh Toán</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            flex-grow: 1;
            min-height: 450px;
        }
        th, td {
            text-align: center;
        }
        .qr-container img {
            max-width: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100" style="padding:0; position: fixed;">
                <div class="position-sticky">
                    <div class="p-3 text-white">
                        <h4>QUẢN LÝ TRƯỜNG HỌC</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/dashboard.php">
                                <i class="fas fa-home me-2"></i>
                                Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/teacher/list_teacher.php">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Quản lý giáo viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/student/list_student.php">
                                <i class="fas fa-user-graduate me-2"></i>
                                Quản lý học sinh
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/class/list_class.php">
                                <i class="fas fa-school me-2"></i>
                                Quản lý lớp học
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/account/list_account.php">
                                <i class="fas fa-users me-2"></i>
                                Quản lý tài khoản
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/attendance/list_attendance.php">
                                <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/admin/payment.php">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Quản lý tài khoản
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/timetable/timetable.php">
                                <i class="fas fa-clock me-2"></i>
                                Quản lý thời khóa biểu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/slide/list_slide.php">
                                <i class="fas fa-layer-group me-2"></i>
                                Quản lý trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/slide/list_slide.php">
                                <i class="fas fa-book me-2"></i>
                                Quản lý chương trình học
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1>Quản Lý Thanh Toán</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>

                <!-- Thông báo lỗi/thành công -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <!-- Form thêm ngân hàng -->
                <h2>Quản lý thông tin ngân hàng</h2>
                <form method="POST" enctype="multipart/form-data" class="mb-5">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="bank_name" class="form-label">Tên ngân hàng *</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="account_number" class="form-label">Số tài khoản *</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="account_holder" class="form-label">Chủ tài khoản *</label>
                            <input type="text" class="form-control" id="account_holder" name="account_holder" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="qr_code" class="form-label">Mã QR (jpg, jpeg, png, gif) *</label>
                            <input type="file" class="form-control" id="qr_code" name="qr_code" accept="image/*" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm ngân hàng</button>
                </form>

                <!-- Danh sách ngân hàng -->
                <h3>Danh sách ngân hàng</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên ngân hàng</th>
                                <th>Số tài khoản</th>
                                <th>Chủ tài khoản</th>
                                <th>Mã QR</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banks as $bank): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($bank['bank_name']); ?></td>
                                    <td><?php echo htmlspecialchars($bank['account_number']); ?></td>
                                    <td><?php echo htmlspecialchars($bank['account_holder']); ?></td>
                                    <td><img src="../<?php echo htmlspecialchars($bank['qr_code_path']); ?>" alt="QR" style="max-width: 100px;"></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $bank['id']; ?>">Sửa</button>
                                        <a href="?delete=<?php echo $bank['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                    </td>
                                </tr>

                                <!-- Modal sửa ngân hàng -->
                                <div class="modal fade" id="editModal<?php echo $bank['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Sửa thông tin ngân hàng</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="action" value="edit">
                                                    <input type="hidden" name="bank_id" value="<?php echo $bank['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="bank_name_<?php echo $bank['id']; ?>" class="form-label">Tên ngân hàng *</label>
                                                        <input type="text" class="form-control" id="bank_name_<?php echo $bank['id']; ?>" name="bank_name" value="<?php echo htmlspecialchars($bank['bank_name']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="account_number_<?php echo $bank['id']; ?>" class="form-label">Số tài khoản *</label>
                                                        <input type="text" class="form-control" id="account_number_<?php echo $bank['id']; ?>" name="account_number" value="<?php echo htmlspecialchars($bank['account_number']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="account_holder_<?php echo $bank['id']; ?>" class="form-label">Chủ tài khoản *</label>
                                                        <input type="text" class="form-control" id="account_holder_<?php echo $bank['id']; ?>" name="account_holder" value="<?php echo htmlspecialchars($bank['account_holder']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="qr_code_<?php echo $bank['id']; ?>" class="form-label">Mã QR (Để trống nếu không thay đổi)</label>
                                                        <input type="file" class="form-control" id="qr_code_<?php echo $bank['id']; ?>" name="qr_code" accept="image/*">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Lịch sử thanh toán -->
                <h2 class="mt-5">Lịch sử thanh toán</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã học sinh</th>
                                <th>Số tiền (VND)</th>
                                <th>Ngân hàng</th>
                                <th>Ghi chú</th>
                                <th>Ngày thanh toán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['student_id']); ?></td>
                                    <td><?php echo number_format($payment['amount'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($payment['bank_name'] ?? 'Chưa xác định'); ?></td>
                                    <td><?php echo htmlspecialchars($payment['payment_note'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>