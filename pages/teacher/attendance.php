<?php
require_once '../system/connect.php'; 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 2) {
    header("Location: ../../index.php");
    exit();
}
$teacherID = $_SESSION['teacherID'] ?? ''; 

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
$attendances = [];
$classes = [];
$totalPages = 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Lấy danh sách lớp mà giáo viên phụ trách
$sql_classes = "SELECT c.id, c.ClassName 
                FROM classes c
                JOIN teachers t ON c.TeacherID = t.id
                WHERE t.id = '$teacherID'"; 
$result_classes = $conn->query($sql_classes);
while ($row = $result_classes->fetch_assoc()) {
    $classes[] = $row;
}

$classFilter = isset($_GET['class']) ? $_GET['class'] : ''; 

// Lọc danh sách học sinh theo lớp và ngày
$sql = "SELECT a.id, a.status, a.notes, a.reason, a.date, s.FullName, c.ClassName
        FROM attendance a
        JOIN students s ON a.StudentID = s.id
        LEFT JOIN classes c ON s.ClassID = c.id
        WHERE a.date = '$selectedDate'";

// Nếu có lớp được chọn, thêm điều kiện lọc theo lớp
if ($classFilter != '') {
    $sql .= " AND c.id = '$classFilter'";
} else {
    $sql .= " AND c.TeacherID = '$teacherID'";
}

$result = $conn->query($sql);
$attendances = [];
while ($row = $result->fetch_assoc()) {
    $attendances[] = $row;
}

// Điểm danh ngày hôm nay
if (isset($_GET['add_today'])) {
    $today = date('Y-m-d');
    $sql_students = "SELECT id FROM students WHERE ClassID IN (SELECT id FROM classes WHERE TeacherID = '$teacherID')";
    $result_students = $conn->query($sql_students);

    while ($row = $result_students->fetch_assoc()) {
        $student_id = $row['id'];

        // Kiểm tra học sinh đã có mặt trong bảng điểm danh  ngày hôm nay 
        $sql_check = "SELECT * FROM attendance WHERE studentid = '$student_id' AND date = '$today'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            $sql_insert = "INSERT INTO attendance (studentid, date, status, notes, reason) 
                            VALUES ('$student_id', '$today', 0, '', '')";
            $conn->query($sql_insert);
        }
    }

    header("Location: attendance.php?date=$today");
    exit;
}

// Lưu tất cả
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

    header("Location: attendance.php?date=$selectedDate");
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
        .sidebar .nav-link:hover {
            background-color: #495057;
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
                       
                        <li class="nav-item" style="background:#575f66">
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
                        <li class="nav-item">
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
                    <h1 class="h2">Quản Lý Điểm Danh</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>

                </div>

                <form method="GET" action="attendance.php">
                    <div class="row mb-3">
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                        <label for="date">Ngày:</label>
                        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($selectedDate); ?>" class="form-control">
                        </div>
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <label for="class">Lớp:</label>
                            <select name="class" id="class" class="form-control">
                                <!-- <option value="">Tất cả lớp</option> -->
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


                <form action="attendance.php" method="GET">
                    <div class="mb-3">
                        <a href="attendance.php?add_today=1" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Điểm danh ngày hôm nay
                        </a>
                    </div>
                </form>

                <form action="attendance.php" method="POST">
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
                                        <td><?php echo $index + 1; ?></td>
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

                    <button type="submit" name="save_attendance" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu tất cả
                    </button>
                </form>
            </main>
        </div>
    </div>
</body>
</html>
