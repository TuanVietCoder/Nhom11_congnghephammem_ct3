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

// tìm kiếm
$searchTerm = '';
$classes = [];
$limit = 10; // Số lượng lớp mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Kiểm tra nếu có từ  tìm kiếm
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    $query = "SELECT c.*, t.FullName, g.GradeName
    FROM classes c 
    LEFT JOIN teachers t ON c.TeacherId = t.id 
    LEFT JOIN Grade g ON c.GradeID = g.id
    WHERE c.ClassName LIKE ? 
    ORDER BY c.ClassName ASC LIMIT ?, ?";

    // Cập nhật bind_param để truy vấn này hoạt động
    $stmt = $conn->prepare($query);
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);
    $stmt->execute();
    $classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    // Nếu không tìm kiếm, lấy tất cả lớp
    $query = "SELECT c.*, t.FullName, g.GradeName
    FROM classes c 
    LEFT JOIN teachers t ON c.TeacherId = t.id 
    LEFT JOIN Grade g ON c.GradeID = g.id
    WHERE c.ClassName LIKE ? 
    ORDER BY c.ClassName ASC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);
    $stmt->execute();
    $classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

}

// Tính toán tổng số trang
$totalQuery = isset($_GET['search']) && !empty($_GET['search']) ? 
    "SELECT COUNT(*) FROM classes WHERE ClassName LIKE ?" : 
    "SELECT COUNT(*) FROM classes";
$stmt = $conn->prepare($totalQuery);

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("s", $searchTerm);
} 

$stmt->execute();
$totalResults = $stmt->get_result()->fetch_row()[0];
$totalPages = ceil($totalResults / $limit);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Lớp Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        .page{
            display: flex;
        justify-content: center; /* Căn giữa phân trang */
        margin-top: auto; /* Đẩy phân trang xuống dưới */
        }
        .table-responsive {
            flex-grow: 1; /* Đảm bảo bảng có thể mở rộng nếu cần */
            min-height: 450px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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
                        <li class="nav-item" style="background:#575f66">
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Lớp Học</h1>
                </div>

                <!-- Thanh tìm kiếm -->
                <form action="list_class.php" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <input class="form-control" type="search" placeholder="Tìm kiếm lớp học" name="search" aria-label="Search" value="<?php echo htmlspecialchars(trim($searchTerm, '%')); ?>">
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
                    <a href="add_class.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Lớp</th>
                                <th>Giáo Viên Chủ Nhiệm</th>
                                <th>Sĩ số</th>
                                <th>Khối</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = $offset + 1; ?>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td><?php echo htmlspecialchars($class['ClassName']); ?></td>
                                    <td><?php echo htmlspecialchars($class['FullName'] ? $class['FullName'] : ''); ?></td>
                                    <td><?php echo htmlspecialchars($class['Quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($class['GradeName'] ? $class['GradeName'] : ''); ?></td> <!-- Hiển thị GradeName -->
                                    <td>
                                        <a href="edit_class.php?id=<?php echo $class['id']; ?>"
                                            class="btn btn-sm btn-primary">Sửa</a>
                                        <a href="delete_class.php?id=<?php echo $class['id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- Nút Previous -->
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo ($page <= 1) ? 1 : $page - 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php
        // Xác định các trang cần hiển thị
        $start = max(1, $page - 2); // Hiển thị 2 trang trước i
        $end = min($totalPages, $page + 2); // Hiển thị  2 trang sau 

        // Nếu số trang trước trang hiện tại lớn hơn 1, hiển thị trang đầu tiên và dấu "..."
        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=1">' . 1 . '</a></li>';
            if ($start > 2) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Hiển thị các trang xung quanh trang hiện tại
        for ($i = $start; $i <= $end; $i++) {
            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                    <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                  </li>';
        }

        // Nếu số trang sau trang hiện tại nhỏ hơn tổng số trang, hiển thị dấu "..." và trang cuối
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
        }
        ?>

        <!-- Nút Next -->
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo ($page >= $totalPages) ? $totalPages : $page + 1; ?>" aria-label="Next">
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
