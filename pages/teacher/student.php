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

$students = [];
$classes = [];

// Lấy danh sách lớp mà giáo viên phụ trách
$sql_classes = "SELECT c.id, c.ClassName 
                FROM classes c
                JOIN teachers t ON c.TeacherID = t.id
                WHERE t.id = '$teacherID'"; 
$result_classes = $conn->query($sql_classes);
while ($row = $result_classes->fetch_assoc()) {
    $classes[] = $row;
}

// Phân trang
$perPage = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start = ($page - 1) * $perPage;

// Lọc học sinh theo lớp 
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; 


$sql_students = "SELECT s.*, c.ClassName
                 FROM students s
                 JOIN classes c ON s.ClassID = c.id
                 WHERE c.TeacherID = '$teacherID'"; 

if (!empty($searchTerm)) {
    $sql_students .= " AND (s.FullName LIKE '%$searchTerm%' OR s.id LIKE '%$searchTerm%')";
}


if (!empty($classFilter)) {
    $sql_students .= " AND c.id = '$classFilter'";
}


$sql_students .= " LIMIT $start, $perPage"; 

// danh sách học sinh
$result_students = $conn->query($sql_students);
while ($row = $result_students->fetch_assoc()) {
    $students[] = $row;
}


$sql_count_students = "SELECT COUNT(*) AS total FROM students s
                       JOIN classes c ON s.ClassID = c.id
                       WHERE c.TeacherID = '$teacherID'";
if (!empty($searchTerm)) {
    $sql_count_students .= " AND (s.FullName LIKE '%$searchTerm%' OR s.id LIKE '%$searchTerm%')";
}
if (!empty($classFilter)) {
    $sql_count_students .= " AND c.id = '$classFilter'"; 
}
$result_count = $conn->query($sql_count_students);
$totalStudents = $result_count->fetch_assoc()['total'];
$totalPages = ceil($totalStudents / $perPage); 
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Học Sinh</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }

        .container-fluid {
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1; 
            display: flex;
            flex-direction: column;
        }

        .table-responsive {
            flex-grow: 1; 
            min-height: 450px;
        }

        .pagination {
            display: flex;
            justify-content: center; 
            margin-top: auto; 
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
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
                        <li class="nav-item" style="background:#575f66">
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

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 d-flex flex-column">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Học Sinh</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>

                <!-- Thanh tìm kiếm và lọc -->
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <label for="search">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" class="form-control" value="<?php echo isset($searchTerm) ? htmlspecialchars($searchTerm) : ''; ?>" />
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

                        <!-- Nút tìm kiếm -->
                        <div class="col-md-2 col-12 mb-2 mt-2 mb-md-0">
                            <label for="class"></label>
                            <button type="submit" class="btn btn-primary form-control">Tìm kiếm</button>
                        </div>
                    </div>
                </form>


                

                <div class="table-responsive flex-grow-1">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã học sinh</th>
                                <th>Họ và tên</th>
                                <th>Lớp</th>
                                <th>Ngày sinh</th>
                                <th>Địa chỉ</th>
                                <th>Phụ huynh</th>
                                <th>SDT phụ huynh</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $stt = ($page - 1) * $perPage + 1; // Cập nhật số thứ tự bắt đầu
                                foreach ($students as $student): 
                            ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td> <!-- Hiển thị và tăng số thứ tự -->
                                    <td><?php echo htmlspecialchars($student['StudentID'] ?? ''); ?></td> 
                                    <td><?php echo htmlspecialchars($student['FullName'] ?? ''); ?></td> 
                                    <td><?php echo htmlspecialchars($student['ClassName'] ?? ''); ?></td> 
                                    <td><?php echo $student['DateOfBirth'] ? date('d/m/Y', strtotime($student['DateOfBirth'])) : ''; ?></td>
                                    <td><?php echo htmlspecialchars($student['Address'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['ParentName'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['ParentPhone'] ?? ''); ?></td>
                                    
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <nav aria-label="Page navigation" class="page">
                    <ul class="pagination">
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo ($page == 1) ? $totalPages : $page - 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
