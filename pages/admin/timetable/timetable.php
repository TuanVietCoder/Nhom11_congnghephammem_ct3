<?php
require_once '../../system/connect.php';


$grades = $conn->query("SELECT * FROM grade");

// Lấy danh sách thời khóa biểu
$filter_query = "SELECT t.*, g.GradeName, a.activityname, tm.timename 
                 FROM timetable t 
                 LEFT JOIN grade g ON t.GradeID = g.id 
                 LEFT JOIN activity a ON t.ActivityID = a.id 
                 LEFT JOIN time tm ON t.TimeID = tm.id 
                 WHERE 1=1 ";


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['filter'])) {
    // Lấy giá trị đầu vào và kiểm tra null hoặc rỗng
    $grade_id = isset($_GET['GradeID']) ? $conn->real_escape_string($_GET['GradeID']) : null;
    $date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : null;

    
    if (!empty($grade_id)) {
        $filter_query .= " AND t.GradeID = '$grade_id'";
    }
    if (!empty($date)) {
        $filter_query .= " AND t.date = '$date'";
    }
}
// Lấy ra thời khóa biểu
$timetables = $conn->query($filter_query);

// thêm thời khóa biểu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_timetable'])) {
    $grade_id = $_POST['GradeID'];
    $date = $_POST['date'];

    // Kiểm tra xem thời khóa biểu đã tồn tại chưa
    $check_query = "SELECT * FROM timetable WHERE GradeID = '$grade_id' AND date = '$date'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Nếu đã tồn tại, thông báo 
        echo "<script>alert('Thời khóa biểu đã tồn tại!');</script>";
    } else {
       
        for ($i = 1; $i <= 13; $i++) {
            $insert_query = "INSERT INTO timetable (GradeID, ActivityID, TimeID, Description, date) 
                             VALUES ('$grade_id', '$i', '$i', '', '$date')";
            if (!$conn->query($insert_query)) {
                echo "Lỗi khi thêm hoạt động $i: " . $conn->error . "<br>";
            }
        }

        // Sau khi thêm, truy vấn lại danh sách thời khóa biểu
        $filter_query = "SELECT t.*, g.GradeName, a.activityname, tm.timename 
                         FROM timetable t 
                         LEFT JOIN grade g ON t.GradeID = g.id 
                         LEFT JOIN activity a ON t.ActivityID = a.id 
                         LEFT JOIN time tm ON t.TimeID = tm.id 
                         WHERE t.GradeID = '$grade_id' AND t.date = '$date'
                         ";

        $timetables = $conn->query($filter_query);

        echo "<script>alert('Thêm thời khóa biểu thành công!');</script>";
    }
}

// Lưu tất cả mô tả
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_all'])) {
    if (isset($_POST['Description']) && is_array($_POST['Description'])) {
        foreach ($_POST['Description'] as $id => $description) {
            $id = (int)$id;
            $description = $conn->real_escape_string($description);

            $update_query = "UPDATE timetable SET Description = '$description' WHERE id = $id";
            $conn->query($update_query);
        }
        echo "<script>alert('Lưu tất cả mô tả thành công!');</script>";
    }
    
    // Truy vấn lại dữ liệu sau khi lưu
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Giáo Viên</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
      
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
        .form-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .navbar1{
            display: flex;
            align-items: center;
            gap: 42px
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
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/admin/teacher/list_teacher.php">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Quản lý giáo viên
                            </a>
                        </li>
                        <li class="nav-item" >
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
                        <li class="nav-item"  >
                            <a class="nav-link text-white" href="/tai/pages/admin/attendance/list_attendance.php">
                            <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item"style="background:#575f66">
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

            <!-- Main content -->
           
             <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 15%;">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Thời khóa biểu</h1>
                </div>

                <div class="form-container">
                    <h2><i class="bi bi-calendar-plus me-2"></i> Thêm thời khóa biểu</h2>
                    <form method="POST" class="navbar1">
                        <div class="mb-3">
                            <label for="GradeID" class="form-label">Khối:</label>
                            <select name="GradeID" id="GradeID" class="form-select" required>
                                <option value="">-- Chọn khối --</option>
                                <?php
                                $grades->data_seek(0); // Reset pointer
                                while ($grade = $grades->fetch_assoc()):
                                ?>
                                    <option value="<?= $grade['id']; ?>"><?= $grade['GradeName']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Ngày:</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <button type="submit" name="add_timetable" class="btn btn-custom"><i class="bi bi-plus-circle me-2"></i> Thêm</button>
                    </form>
                </div>

                <div class="form-container">
                    <h2><i class="bi bi-funnel me-2"></i> Lọc thời khóa biểu</h2>
                    <form method="GET" class="navbar1">
                        <div class="mb-3">
                            <label for="GradeID" class="form-label">Khối:</label>
                            <select name="GradeID" id="GradeID" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <?php
                                $grades->data_seek(0); // Reset pointer
                                while ($grade = $grades->fetch_assoc()):
                                ?>
                                    <option value="<?= $grade['id']; ?>" <?= (isset($_GET['GradeID']) && $_GET['GradeID'] == $grade['id']) ? 'selected' : ''; ?>>
                                        <?= $grade['GradeName']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Ngày:</label>
                            <input type="date" name="date" id="date" class="form-control" value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                        </div>

                        <button type="submit" name="filter" class="btn btn-custom"><i class="bi bi-search me-2"></i> Lọc</button>
                    </form>
                </div>

                <h2><i class="bi bi-calendar-check me-2"></i> Danh sách thời khóa biểu</h2>
                <form action="timetable.php" method="POST">
                    <button type="submit" name="save_all" class="btn btn-custom"><i class="bi bi-save me-2"></i> Lưu tất cả</button>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Khối</th>
                                <th>Thời gian</th>
                                <th>Hoạt động</th>
                                
                                <th>Mô tả</th>
                                <th>Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $timetables->fetch_assoc()): ?>
                                <tr>
                                   
                                    <td><?= $row['GradeName']; ?></td>
                                    <td><?= $row['timename']; ?></td>
                                    <td><?= $row['activityname']; ?></td>
                                    
                                    <td>
                                        <input type="text" name="Description[<?= $row['id']; ?>]" class="form-control" value="<?= $row['Description']; ?>">
                                    </td>
                                    <td><?= $row['date']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>



