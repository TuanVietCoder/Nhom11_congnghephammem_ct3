<?php
session_start();
if (!isset($_SESSION['teacherID'])) {
    header("Location: ../login.php");
    exit();
}

$teacherID = $_SESSION['teacherID'] ?? '';
if (!$teacherID) {
    die("Không tìm thấy thông tin giáo viên.");
}

require_once '../../system/connect.php';

// Lấy danh sách lớp mà giáo viên quản lý
$sqlClasses = "
    SELECT id, ClassName 
    FROM classes 
    WHERE TeacherID = '$teacherID'
    ORDER BY id;
";
$resultClasses = $conn->query($sqlClasses);
if (!$resultClasses) {
    die("Truy vấn lớp thất bại: " . $conn->error);
}

$selectedClass = $_GET['classID'] ?? '';
$selectedDate = $_GET['dateFilter'] ?? ''; 

// Cập nhật truy vấn lấy sự kiện theo TeacherID, ClassID và ngày
$sqlEvent = "
    SELECT 
        event.id, 
        event.eventname, 
        DATE(event.eventdate) AS eventdate, 
        event.ClassID 
    FROM 
        event
    INNER JOIN 
        classes ON event.ClassID = classes.id
    WHERE 
        classes.TeacherID = '$teacherID' 
        AND ('' = '$selectedClass' OR event.ClassID = '$selectedClass')
";

if (!empty($selectedDate)) {
    $sqlEvent .= " AND DATE(event.eventdate) = '$selectedDate'";
}

$sqlEvent .= " ORDER BY event.id DESC;";


$resultEvent = $conn->query($sqlEvent);
if (!$resultEvent) {
    die("Truy vấn sự kiện thất bại: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <title>Quản Lý Sự Kiện</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <style>
        h2 {
            text-align: center;
            margin: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .modal-body img {
            width: 100%;
            margin-bottom: 10px;
        }
        .filterr{
            display: flex;
             gap: 20px;
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
                        
                        <li class="nav-item" >
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
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/teacher/event/event.php">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Sự kiện
                            </a>
                        </li>
                    </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản Lý Sự Kiện</h1>
                <a href="../../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
            </div>

            <div class="mb-3">
                <a href="add_event.php" class="btn btn-primary">Thêm Sự Kiện</a>
            </div>
            <form method="GET" action="event.php" class="filterr">
                <div class="mb-3">
                    <label for="classID" class="form-label">Chọn Lớp</label>
                    <select id="classID" name="classID" class="form-select">
                        <?php while ($class = $resultClasses->fetch_assoc()): ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                                <?= $class['ClassName'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="dateFilter" class="form-label">Chọn Ngày</label>
                    <input type="date" id="dateFilter" name="dateFilter" class="form-control" value="<?= $_GET['dateFilter'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="dateFilter" class="form-label"> </label>
                    <button type="submit" class="btn btn-primary form-control">Lọc</button>
                </div>
            </form>

            

            <h2>Danh Sách Sự Kiện</h2>
            <table>
                <thead>
                <tr>
                    <th>Tên Sự Kiện</th>
                    <th>Ngày</th>
                    <th>Chức Năng</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $resultEvent->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#photoModal" onclick="loadPhotos(<?= $row['id'] ?>)">
                                <?= $row['eventname'] ?>
                            </a>
                        </td>
                        <td><?= $row['eventdate'] ?></td>
                        <td>
                            <a href="addphoto.php?event_id=<?= $row['id'] ?>" class="btn btn-warning">Thêm ảnh</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Modal hiển thị ảnh -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Ảnh của Sự Kiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="photoGallery" class="row"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function loadPhotos(eventID) {
        $.ajax({
            url: 'load_photos.php',
            method: 'GET',
            data: {event_id: eventID},
            success: function (response) {
                $('#photoGallery').html(response);
            },
            error: function () {
                $('#photoGallery').html('<p class="text-danger">Không thể tải ảnh.</p>');
            }
        });
    }
</script>
</body>
</html>

