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

if (!$teacherID) {
    die("Không tìm thấy thông tin giáo viên.");
}

// Lấy danh sách GradeID và GradeName mà giáo viên phụ trách
$sqlGrade = "
    SELECT DISTINCT 
        classes.GradeID, grade.GradeName 
    FROM 
        classes 
    INNER JOIN 
        grade ON classes.GradeID = grade.id
    WHERE 
        classes.TeacherID = '$teacherID'
";

$resultGrade = $conn->query($sqlGrade);

if (!$resultGrade) {
    die("Truy vấn thất bại: " . $conn->error);
}

$grade = [];
while ($row = $resultGrade->fetch_assoc()) {
    $grade[] = $row;
}

//  lọc dữ liệu
$selectedGrade = $_GET['grade'] ?? ($grade[0]['GradeID'] ?? ''); 
$selectedDate = $_GET['date'] ?? date('Y-m-d'); 

// Truy vấn theo điều kiện lọc
$sql = "
    SELECT 
        timetable.GradeID,
        grade.GradeName,
        timetable.date,
        timetable.Description,
        activity.activityname,
        time.timename
    FROM 
        classes
    INNER JOIN 
        timetable ON classes.GradeID = timetable.GradeID
    INNER JOIN 
        activity ON timetable.ActivityID = activity.id
    INNER JOIN 
        time ON timetable.TimeID = time.id
    INNER JOIN 
        grade ON classes.GradeID = grade.id
    WHERE 
        classes.TeacherID = '$teacherID'
        AND timetable.GradeID = '$selectedGrade'
        AND timetable.date = '$selectedDate'
    ORDER BY 
       
        time.id; 
";

$result = $conn->query($sql);

if (!$result) {
    die("Truy vấn thất bại: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thời gian biểu</title>
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
        
        .main-content .form-inline {
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .table th, .table td {
            text-align: center;
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

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thời khóa biểu</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>

                </div>
                <form method="GET" action="" class="form-inline">
                     

                    <div class="form-group me-3">
                        <label for="grade" class="form-label">Chọn Lớp:</label>
                        <select name="grade" id="grade" class="form-select">
                            <?php foreach ($grade as $grade) : ?>
                                <option value="<?= htmlspecialchars($grade['GradeID']) ?>" <?= $grade['GradeID'] == $selectedGrade ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade['GradeName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group me-3">
                        <label for="date" class="form-label">Chọn Ngày:</label>
                        <input type="date" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control">
                    </div>
                    <div>
                        <label for="date" class="form-label"></label>
                        <button type="submit" class="btn btn-primary form-control">Xem</button>
                       
                    </div>
                    
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Thời Gian</th>
                                <th>Hoạt Động</th>
                                <th>Mô Tả</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0) : ?>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                        <td><?= htmlspecialchars($row['timename']) ?></td>
                                        <td><?= htmlspecialchars($row['activityname']) ?></td>
                                        <td><?= htmlspecialchars($row['Description']) ?></td>
                                        
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có thời khóa biểu nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
