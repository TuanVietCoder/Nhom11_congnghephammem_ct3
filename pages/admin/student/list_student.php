<?php
require_once '../../system/connect.php';
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Xử lý tìm kiếm và phân trang
$searchTerm = '';
$classFilter = '';
$students = [];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;

// Kiểm tra từ khóa tìm kiếm
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchQuery = "%$searchTerm%";
} else {
    $searchQuery = '%'; //hiển thị tất cả
}

// Kiểm tra lọc theo lớp học
if (isset($_GET['class_filter']) && !empty($_GET['class_filter'])) {
    $classFilter = $_GET['class_filter'];
}


if (!empty($classFilter)) {
    // Tìm kiếm với lớp
    $query = "SELECT s.*, c.ClassName 
              FROM students s 
              LEFT JOIN classes c ON s.ClassID = c.id 
              WHERE s.FullName LIKE ? AND s.ClassID = ? 
              ORDER BY s.StudentID ASC 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $offset = ($page - 1) * $perPage;
    $stmt->bind_param("siii", $searchQuery, $classFilter, $offset, $perPage);
} else {
    // Tìm kiếm không lọc theo lớp học
    $query = "SELECT s.*, c.ClassName 
              FROM students s 
              LEFT JOIN classes c ON s.ClassID = c.id 
              WHERE s.FullName LIKE ? 
              ORDER BY s.StudentID ASC 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $offset = ($page - 1) * $perPage;
    $stmt->bind_param("sii", $searchQuery, $offset, $perPage);
}
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// tổng số học sinh với điều kiện tìm kiếm và lọc theo lớp học
if (!empty($classFilter)) {
   
    $countQuery = "SELECT COUNT(*) as total 
                   FROM students s 
                   LEFT JOIN classes c ON s.ClassID = c.id 
                   WHERE s.FullName LIKE ? AND s.ClassID = ?";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param("si", $searchQuery, $classFilter);
} else {
    
    $countQuery = "SELECT COUNT(*) as total 
                   FROM students s 
                   LEFT JOIN classes c ON s.ClassID = c.id 
                   WHERE s.FullName LIKE ?";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param("s", $searchQuery);
}
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

// Tính tổng số trang
$totalPages = ceil($total / $perPage);

// Hàm lấy lớp học hiển thị trong dropdown
function getClasses($conn) {
    $query = "SELECT * FROM classes";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$classes = getClasses($conn);
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
                        <li class="nav-item" style="background:#575f66">
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
                        <li class="nav-item" >
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

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 d-flex flex-column">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Học Sinh</h1>
                </div>

          

                <!-- Thanh lọc theo lớp -->
                <form action="list_student.php" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input class="form-control" type="search" placeholder="Tìm kiếm học sinh" name="search" value="<?php echo htmlspecialchars(trim($searchTerm, '%')); ?>">
                        </div>
                        <div class="col-md-4">
                            <!-- Dropdown cho tên lớp -->
                            <select class="form-control" name="class_filter">
                                <option value="">Tất cả các lớp</option>
                                <?php
                                // Lấy danh sách các lớp từ bảng classes
                                $classQuery = "SELECT * FROM classes";
                                $classResult = $conn->query($classQuery);
                                while ($class = $classResult->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo (isset($_GET['class_filter']) && $_GET['class_filter'] == $class['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['ClassName']); ?>
                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search me-2"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>


                <!-- Nút thêm mới -->
                <div class="mb-3">
                    <a href="add_student.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>
                <!-- Nút tải danh sách -->
                <!-- <div class="mb-3">
                    <a href="export_students.php" class="btn btn-warning">
                        <i class="fas fa-file-export me-1"></i> Tải danh sách
                    </a>
                </div> -->


                <!-- Bảng danh sách học sinh -->
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
                                <th></th>
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
                                    <td>
                                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                        <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <nav aria-label="Page navigation" class="page">
                    <ul class="pagination">
                        <!-- Nút Previous -->
                        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo ($page == 1) ? $totalPages : $page - 1; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php
                        $start = max(1, $page - 2); // Hiển thị tối thiểu 2 trang trước trang hiện tại
                        $end = min($totalPages, $page + 2); // Hiển thị tối đa 2 trang sau trang hiện tại
                        
                        // Hiển thị các trang trước trang hiện tại và sau trang hiện tại, với dấu "..." nếu cần
                        if ($start > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . htmlspecialchars($searchTerm) . '">1</a></li>';
                            if ($start > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                                    <a class="page-link" href="?page=' . $i . '&search=' . htmlspecialchars($searchTerm) . '">' . $i . '</a>
                                </li>';
                        }

                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>