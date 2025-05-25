<?php
// Kết nối cơ sở dữ liệu
require_once '../../system/connect.php'; // Kết nối với cơ sở dữ liệu
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
$attendances = [];
$classes = [];
$totalPages = 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Lấy danh sách lớp
$sql_classes = "SELECT id, ClassName FROM classes";
$result_classes = $conn->query($sql_classes);
while ($row = $result_classes->fetch_assoc()) {
    $classes[] = $row;
}

// Tính toán số học sinh và số trang
$sql_count = "SELECT COUNT(*) AS total FROM attendance a
              JOIN students s ON a.StudentID = s.id
              LEFT JOIN classes c ON s.ClassID = c.id
              WHERE a.date = '$selectedDate'";

if ($classFilter != '') {
    $sql_count .= " AND c.id = '$classFilter'";
}

$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$totalRecords = $row_count['total'];
$recordsPerPage = 20;
$totalPages = ceil($totalRecords / $recordsPerPage);
$offset = ($page - 1) * $recordsPerPage;

// Lọc danh sách học sinh theo lớp và ngày với phân trang
$sql = "SELECT a.id, a.status, a.notes, a.reason, a.date, s.FullName, c.ClassName
        FROM attendance a
        JOIN students s ON a.StudentID = s.id
        LEFT JOIN classes c ON s.ClassID = c.id
        WHERE a.date = '$selectedDate'";

if ($classFilter != '') {
    $sql .= " AND c.id = '$classFilter'";
}

$sql .= " LIMIT $recordsPerPage OFFSET $offset"; // Phân trang

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $attendances[] = $row;
}

//ddddiểm danh ngày hôm nay
if (isset($_GET['add_today'])) {
    $today = date('Y-m-d');
    $sql_students = "SELECT id FROM students";
    $result_students = $conn->query($sql_students);

    while ($row = $result_students->fetch_assoc()) {
        $student_id = $row['id'];

        // Kiểm tra hs đã có  trong bảng điểm danh chưachưa
        $sql_check = "SELECT * FROM attendance WHERE studentid = '$student_id' AND date = '$today'";
        $result_check = $conn->query($sql_check);

        // Nếu hs chưa có điểm danh,- thêm mới
        if ($result_check->num_rows == 0) {
            $sql_insert = "INSERT INTO attendance (studentid, date, status, notes, reason) 
                            VALUES ('$student_id', '$today', 0, '', '')";
            $conn->query($sql_insert);
        }
    }

    // Chuyển hướng lại trang để hiển thị điểm danh
    header("Location: list_attendance.php?date=$today");
    exit;
}

// Nếu nhấn "Lưu tất cả"
if (isset($_POST['save_attendance'])) {
    foreach ($_POST['attendance'] as $attendance_id => $attendance_data) {
        $status = isset($attendance_data['status']) ? 1 : 0;
        $notes = $attendance_data['notes'] ?? '';
        $reason = $attendance_data['reason'] ?? '';

        $sql_update = "UPDATE attendance 
                       SET status = '$status', notes = '$notes', reason = '$reason' 
                       WHERE id = '$attendance_id'";
        $conn->query($sql_update);
    }

    // Chuyển hướng lại trang để cập nhật điểm danh
    header("Location: list_attendance.php?date=$selectedDate");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Điểm Danh</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        .page {
            display: flex;
            justify-content: center;
            margin-top: auto;
        }

        .table-responsive {
            flex-grow: 1;
            min-height: 450px;
        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        input.form-check-input {
            font-size: 22px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100" style="padding:0;position: fixed;">
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
                        <li class="nav-item" >
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
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/admin/attendance/list_attendance.php">
                            <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/admin/timetable/timetable.php">
                                <i class="fas fa-clock me-2"></i>
                                Quản lý thời khóa biểu
                            </a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/slide/list_slide.php">
                             <i class="fas fa-layer-group me-2"></i>
                                Quản lý trang chủ
                            </a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/curriculum/curriculum.php">
                                <i class="fas fa-book me-2"></i>
                                Quản lý chương trình học
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/payment.php">
                                <i class="fas fa-book me-2"></i>
                                Quản lý tài chính
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Điểm Danh</h1>
                    <!-- <a href="statistical.php" class="btn btn-secondary">Thống kê</a> -->


                </div>

                <!-- Form chọn ngày và lớp -->
                <form action="list_attendance.php" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <label for="date" class="form-label">Chọn ngày:</label>
                            <input type="date" name="date" id="date" class="form-control" value="<?php echo $selectedDate; ?>" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <label for="class" class="form-label">Chọn lớp:</label>
                            <select name="class" id="class" class="form-control">
                                <option value="">Tất cả lớp</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $classFilter == $class['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($class['ClassName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <button type="submit" class="btn btn-primary w-100 mt-4">
                                <i class="fas fa-search me-2"></i> Xem điểm danh
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Nút tạo điểm danh ngày hôm nay -->
                <form action="list_attendance.php" method="GET">
                    <div class="mb-3">
                        <a href="list_attendance.php?add_today=1" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Điểm danh ngày hôm nay
                        </a>
                    </div>
                </form>

                <form action="list_attendance.php" method="POST">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Học Sinh</th>
                                    <th>Lớp Học</th>
                                    <th>Ngày</th>
                                    <th>Trạng Thái</th>
                                    <th>Lý Do</th>
                                    <th>Ghi Chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendances as $index => $attendance): ?>
                                    <tr>
                                        <td><?php echo $index + 1 + ($page - 1) * $recordsPerPage; ?></td>
                                        <td><?php echo htmlspecialchars($attendance['FullName']); ?></td>
                                        <td><?php echo htmlspecialchars($attendance['ClassName'] ?? ''); ?></td>
                                        <td><?php echo $attendance['date']; ?></td>
                                        <td>
                                            <input type="checkbox" name="attendance[<?php echo $attendance['id']; ?>][status]" <?php echo $attendance['status'] == 1 ? 'checked' : ''; ?>>
                                        </td>
                                        <td>
                                            <input name="attendance[<?php echo $attendance['id']; ?>][reason]" value="<?php echo $attendance['reason'] ?? ''; ?>">
                                        </td>
                                        <td>
                                            <input name="attendance[<?php echo $attendance['id']; ?>][notes]" value="<?php echo $attendance['notes'] ?? ''; ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Nút lưu tất cả -->
                    <button type="submit" name="save_attendance" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu tất cả
                    </button>
                </form>

                <!-- Phân trang -->
<!-- Phân trang -->
<nav aria-label="Page navigation" class="page">
    <ul class="pagination">
        <!-- Nút Previous -->
        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo ($page == 1) ? $totalPages : $page - 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        
        <!-- Hiển thị các trang gần trang hiện tại -->
        <?php
            // Giới hạn số trang hiển thị trước và sau trang hiện tại
            $pageLimit = 2;
            $startPage = max(1, $page - $pageLimit);
            $endPage = min($totalPages, $page + $pageLimit);

            // Nếu có nhiều trang, hiển thị nhóm trang
            if ($startPage > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . htmlspecialchars($searchTerm) . '">1</a></li>';
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }

            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php
            if ($endPage < $totalPages) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&search=' . htmlspecialchars($searchTerm) . '">' . $totalPages . '</a></li>';
            }
        ?>

        <!-- Nút Next -->
        <li class="page-item <?php echo ($page == $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo ($page == $totalPages) ? 1 : $page + 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>

        
    </ul>
</nav>

            </main>
        </div>
    </div>
</body>
</html>
