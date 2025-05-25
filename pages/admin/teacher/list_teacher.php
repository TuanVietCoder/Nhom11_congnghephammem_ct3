<?php
require_once '../../system/connect.php';
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra quyền truy cập
if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}

// Xử lý tìm kiếm
$searchTerm = '';
$teachers = [];
$limit = 10; // Số lượng giáo viên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM teachers WHERE FullName LIKE ? ORDER BY FullName ASC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);
    $stmt->execute();
    $teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    // Nếu không tìm kiếm, lấy tất cả giáo viên
    $query = "SELECT * FROM teachers ORDER BY FullName ASC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Tính toán tổng số trang
$totalQuery = isset($_GET['search']) && !empty($_GET['search']) ? 
    "SELECT COUNT(*) FROM teachers WHERE FullName LIKE ?" : 
    "SELECT COUNT(*) FROM teachers";
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
    <title>Quản Lý Giáo Viên</title>
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
                        <li class="nav-item" style="background:#575f66">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý Giáo Viên</h1>
                </div>

                <!-- Thanh tìm kiếm -->
                <form action="list_teacher.php" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <input class="form-control" type="search" placeholder="Tìm kiếm giáo viên" name="search" aria-label="Search" value="<?php echo htmlspecialchars(trim($searchTerm, '%')); ?>">
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
                    <a href="add_teacher.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>

                <!-- Bảng danh sách giáo viên -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Ngày vào làm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = $offset + 1; ?>
                            <?php foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td><?php echo htmlspecialchars($teacher['FullName']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['Phone']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['Address']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['HireDate']); ?></td>
                                    <td>
                                        <a href="edit_teacher.php?id=<?php echo $teacher['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                        <a href="delete_teacher.php?id=<?php echo $teacher['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                    </td>
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

        <?php
        // Số trang cần hiển thị
        $page_range = 2; // số trang trước và sau trang hiện tại
        $start_page = max(1, $page - $page_range); // bắt đầu từ trang 1 hoặc trang hiện tại trừ đi page_range
        $end_page = min($totalPages, $page + $page_range); // kết thúc tại trang tổng cộng hoặc trang hiện tại cộng với page_range

        // Hiển thị trang đầu tiên nếu không có
        if ($start_page > 1) :
        ?>
            <li class="page-item">
                <a class="page-link" href="?page=1&search=<?php echo htmlspecialchars($searchTerm); ?>">1</a>
            </li>
            <?php if ($start_page > 2) : ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Hiển thị các trang xung quanh trang hiện tại -->
        <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Hiển thị dấu ... nếu cần thiết -->
        <?php if ($end_page < $totalPages) : ?>
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        <?php endif; ?>

        <!-- Hiển thị trang cuối cùng nếu không có -->
        <?php if ($end_page < $totalPages) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"><?php echo $totalPages; ?></a>
            </li>
        <?php endif; ?>

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
