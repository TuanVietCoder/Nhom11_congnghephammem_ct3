<?php
session_start();
if (!isset($_SESSION['studentID'])) {
    header("Location: ../login.php");
    exit();
}

$studentID = $_SESSION['studentID'] ?? '';
if (!$studentID) {
    die("Không tìm thấy thông tin học sinh.");
}

require_once '../system/connect.php';

$sqlClass = "
    SELECT classes.id AS ClassID, classes.ClassName 
    FROM students
    INNER JOIN classes ON students.ClassID = classes.id
    WHERE students.id = '$studentID';
";
$resultClass = $conn->query($sqlClass);
if (!$resultClass || $resultClass->num_rows === 0) {
    die("Không tìm thấy thông tin lớp học.");
}
$classInfo = $resultClass->fetch_assoc();
$studentClassID = $classInfo['ClassID'];
$className = $classInfo['ClassName'];

// Lấy danh sách sự kiện của lớp học của học sinh
$sqlEvents = "
    SELECT 
        event.id, 
        event.eventname, 
        DATE(event.eventdate) AS eventdate
    FROM event
    WHERE event.ClassID = '$studentClassID'
    ORDER BY event.id DESC;
";
$resultEvents = $conn->query($sqlEvents);
if (!$resultEvents) {
    die("Truy vấn sự kiện thất bại: " . $conn->error);
}
// Lấy sự kiện với điều kiện lọc theo ngày (nếu có)
$filterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : '';
$sqlEvents = "
    SELECT 
        event.id, 
        event.eventname, 
        DATE(event.eventdate) AS eventdate
    FROM event
    WHERE event.ClassID = '$studentClassID' ";

//điều kiện lọc theo ngày nếu cócó
if ($filterDate) {
    $sqlEvents .= " AND DATE(event.eventdate) = '$filterDate' ";
}

$sqlEvents .= "ORDER BY event.id DESC";

$resultEvents = $conn->query($sqlEvents);
if (!$resultEvents) {
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
    <title>Trang Sự Kiện - Học Sinh</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <style>
        h2 {
            text-align: center;
            margin: 20px 0;
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
        a {
            text-decoration: none;
            color: black;
            font-weight: 600;
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
                        <h4>MẦM NON 11</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item" >
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
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/student/event.php">
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
                    <h1 class="h2">Danh Sách Sự Kiện</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>
                <!-- Thông tin lớp học -->
            <div class="mb-3">
                <h4>Lớp: <?= htmlspecialchars($className) ?></h4>
            </div>

           <!-- Form lọc theo ngày -->
           <div class="mb-3">
                <label for="filterDate" class="form-label">Chọn ngày</label>
                <form id="filterForm" class="d-flex" method="GET">
                    <input type="date" name="filterDate" id="filterDate" style="padding: 10px;" class=" me-2" placeholder="Chọn ngày" value="<?= htmlspecialchars($filterDate) ?>" />
                    <button type="submit" class="btn btn-primary">Xem</button>
                </form>
            </div>



            <!-- Danh sách sự kiện -->
            <h2>Các Sự Kiện </h2>
            <table>
                <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Tên Sự Kiện</th>
                    <th>Chi Tiết</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $resultEvents->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['eventdate']) ?></td>
                        <td>
                            <a href="#" class="" data-bs-toggle="modal" data-bs-target="#photoModal" onclick="loadPhotos(<?= $row['id'] ?>)">
                                <?= htmlspecialchars($row['eventname']) ?>
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#photoModal" onclick="loadPhotos(<?= $row['id'] ?>)">Xem Ảnh</button>
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
            success: function(response) {
                $('#photoGallery').html(response);
            }
        });
    }
</script>

</body>
</html>
