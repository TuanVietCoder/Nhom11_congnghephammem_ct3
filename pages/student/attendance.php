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

// Lấy ngày được chọn từ form hoặc  hôm nay
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$attendances = [];

// Lấy danh sách điểm danh của tất cả học sinh trong lớp
$sql = "SELECT a.id, a.status, a.notes, a.reason, a.date, s.FullName, c.ClassName
        FROM attendance a
        JOIN students s ON a.StudentID = s.id
        LEFT JOIN classes c ON s.ClassID = c.id
        WHERE c.id = '$classID' AND a.date = '$selectedDate'";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $attendances[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Điểm Danh</title>
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
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/student/attendance.php">
                                <i class="fas fa-calendar-check me-2"></i>
                                Danh Sách Điểm Danh
                            </a>
                            
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/student/timetable.php">
                                <i class="fas fa-clock me-2"></i>
                                Thời khóa biểu
                            </a>
                            
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/student/event.php">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Sự kiện
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/student/payment.php">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Đóng học phí
                            </a>
                        </li>               
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Danh Sách Điểm Danh</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>

                <!-- Form chọn ngày -->
                <form method="GET" action="attendance.php">
                    <div class="row mb-3">
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <label for="class">Lớp:</label>
                            <input type="text" id="class" value="<?php echo htmlspecialchars($className); ?>" class="form-control" readonly>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="date">Ngày:</label>
                            <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($selectedDate); ?>" class="form-control">
                        </div>
                        <div class="col-md-3 col-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </div>
                </form>

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
                                        <?php echo $attendance['status'] == 1 ? 'Có mặt' : 'Vắng'; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($attendance['reason'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['notes'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
