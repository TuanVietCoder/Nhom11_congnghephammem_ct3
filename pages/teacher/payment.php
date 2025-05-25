<?php
require_once '../system/connect.php';

session_start();
if (!isset($_SESSION['studentID'])) {
    header("Location: ../login.php");
    exit();
}

$studentID = $_SESSION['studentID'] ?? '';

// Lấy thông tin lớp học của học sinh
$sql_class = "SELECT c.id, c.ClassName 
              FROM students s
              JOIN classes c ON s.ClassID = c.id
              WHERE s.id = '$studentID'";
$result_class = $conn->query($sql_class);
$class = $result_class->fetch_assoc();
$classID = $class['id'];
$className = $class['ClassName'];

$errors = [];
$success = "";
$selected_bank = null;

// Lấy danh sách ngân hàng
$banks = [];
$result = $conn->query("SELECT id, bank_name, qr_code_path, account_number, account_holder FROM banks");
while ($row = $result->fetch_assoc()) {
    $banks[] = $row;
}

// Xử lý thanh toán
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'pay') {
    $amount = trim($_POST['amount']);
    $bank_id = trim($_POST['bank_id']);
    $payment_note = trim($_POST['payment_note']);

    // Xác thực dữ liệu
    if (empty($amount)) $errors[] = "Số tiền là bắt buộc.";
    elseif (!is_numeric($amount) || $amount <= 0) $errors[] = "Số tiền phải là số dương.";
    if (empty($bank_id)) $errors[] = "Vui lòng chọn ngân hàng.";

    // Tìm ngân hàng được chọn
    foreach ($banks as $bank) {
        if ($bank['id'] == $bank_id) {
            $selected_bank = $bank;
            break;
        }
    }
    if (!$selected_bank) $errors[] = "Ngân hàng không hợp lệ.";

    // Nếu không có lỗi, lưu thanh toán
    if (empty($errors)) {
        $sql = "INSERT INTO payments (student_id, amount, bank_id, payment_note) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdis", $studentID, $amount, $bank_id, $payment_note);

        if ($stmt->execute()) {
            $success = "Ghi nhận thanh toán thành công! Vui lòng quét mã QR để hoàn tất.";
        } else {
            $errors[] = "Lỗi khi lưu thông tin: " . $conn->error;
        }
        $stmt->close();
    }
}

// Lấy lịch sử thanh toán
$history = [];
$sql_history = "SELECT p.id, p.amount, p.payment_note, p.payment_date, b.bank_name 
                FROM payments p 
                JOIN banks b ON p.bank_id = b.id 
                WHERE p.student_id = ? 
                ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($sql_history);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result_history = $stmt->get_result();
while ($row = $result_history->fetch_assoc()) {
    $history[] = $row;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đóng Học Phí</title>
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
        .qr-container {
            text-align: center;
            margin-top: 20px;
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
        .history-container {
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100" style="padding:0;position: fixed;">
                <div class="position-sticky">
                    <div class="p-3 text-white">
                        <h4>MẦM NON 11</h4>
                    </div>
                    <ul class="nav flex-column">
                    <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/teacher/attendance.php">
                                <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/teacher/student.php">
                                <i class="fas fa-users me-2"></i>
                                Danh sách học sinh
                            </a>
                        </li>
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/teacher/timetable.php">
                                <i class="fas fa-clock me-2"></i>
                                Thời khóa biểu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/teacher/event/event.php">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Sự kiện
                            </a>
                        </li>
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/student/payment.php">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Đóng học phí
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Đóng Học Phí</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>

                <div class="form-container">
                    <?php if (!empty($errors)): ?>
                        <div class="error">
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

                    <?php if (empty($banks)): ?>
                        <div class="alert alert-warning">Hiện tại chưa có ngân hàng nào được cấu hình. Vui lòng liên hệ admin để thêm thông tin ngân hàng.</div>
                    <?php else: ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="pay">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Số tiền (VND) *</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>" required>
                                <div class="invalid-feedback">Vui lòng nhập số tiền hợp lệ.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bank_id" class="form-label">Chọn ngân hàng *</label>
                                <select class="form-select" id="bank_id" name="bank_id" required>
                                    <option value="">-- Chọn ngân hàng --</option>
                                    <?php foreach ($banks as $bank): ?>
                                        <option value="<?php echo $bank['id']; ?>" <?php echo isset($_POST['bank_id']) && $_POST['bank_id'] == $bank['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($bank['bank_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn ngân hàng.</div>
                            </div>
                            <div class="mb-3">
                                <label for="payment_note" class="form-label">Ghi chú thanh toán</label>
                                <textarea class="form-control" id="payment_note" name="payment_note"><?php echo isset($_POST['payment_note']) ? htmlspecialchars($_POST['payment_note']) : ''; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Ghi nhận và hiển thị QR</button>
                        </form>

                        <?php if ($selected_bank): ?>
                            <div class="qr-container">
                                <h3>Thông tin thanh toán</h3>
                                <p><strong>Ngân hàng:</strong> <?php echo htmlspecialchars($selected_bank['bank_name']); ?></p>
                                <p><strong>Số tài khoản:</strong> <?php echo htmlspecialchars($selected_bank['account_number']); ?></p>
                                <p><strong>Chủ tài khoản:</strong> <?php echo htmlspecialchars($selected_bank['account_holder']); ?></p>
                                <img src="../<?php echo htmlspecialchars($selected_bank['qr_code_path']); ?>" alt="QR Code">
                                <p>Vui lòng quét mã QR để chuyển khoản.</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Lịch sử thanh toán -->
                <div class="history-container mt-5">
                    <h3 class="text-center mb-4">Lịch Sử Thanh Toán</h3>
                    <?php if (!empty($history)): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ngày thanh toán</th>
                                    <th>Số tiền (VND)</th>
                                    <th>Ngân hàng</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                        <td><?php echo number_format($payment['amount'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($payment['bank_name']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['payment_note'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">Chưa có lịch sử thanh toán.</div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xác thực form Bootstrap
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>