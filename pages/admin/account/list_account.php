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

// Lọc theo vai trò
$roleFilter = isset($_GET['role']) ? intval($_GET['role']) : null;

// Xử lý tìm kiếm
$searchTerm = isset($_GET['search']) ? "%" . $conn->real_escape_string(trim($_GET['search'])) . "%" : "%";

function getUsers($conn, $page = 1, $perPage = 10, $searchTerm = "", $roleFilter = null)
{
    // Tính toán offset dựa trên trang hiện tại
    $offset = ($page - 1) * $perPage;

    // Nếu từ khóa tìm kiếm để trống, mặc định lấy tất cả
    $searchTerm = empty($searchTerm) ? "%" : "%" . $searchTerm . "%";

    // Câu truy vấn với JOIN giữa bảng users, teachers, và students
    $query = "
        SELECT users.*, 
               CASE 
                   WHEN users.role = 2 THEN teachers.fullname 
                   WHEN users.role = 3 THEN students.fullname 
                   ELSE 'Admin' 
               END AS FullName
        FROM users
        LEFT JOIN teachers ON users.TeacherID = teachers.id
        LEFT JOIN students ON users.StudentID = students.id
        WHERE users.username LIKE ? ";

    // Thêm điều kiện lọc theo vai trò nếu `$roleFilter` không trống
    if (!is_null($roleFilter)) {
        $query .= "AND users.role = ? ";
    }

    // Sắp xếp theo ID giảm dần và giới hạn bản ghi trả về
    $query .= "ORDER BY users.id DESC LIMIT ?, ?";

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    // Gắn tham số vào truy vấn
    if (!is_null($roleFilter)) {
        $stmt->bind_param("siii", $searchTerm, $roleFilter, $offset, $perPage);
    } else {
        $stmt->bind_param("sii", $searchTerm, $offset, $perPage);
    }

    // Thực thi truy vấn
    if (!$stmt->execute()) {
        die("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}




function getTotalUsers($conn, $searchTerm = "%", $roleFilter = null)
{
    $query = "SELECT COUNT(*) AS total FROM users WHERE username LIKE ? ";

    if ($roleFilter !== null) {
        $query .= "AND role = ? ";
    }

    $stmt = $conn->prepare($query);
    
    if ($roleFilter !== null) {
        $stmt->bind_param("si", $searchTerm, $roleFilter);
    } else {
        $stmt->bind_param("s", $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

// Xác định trang hiện tại và số bản ghi mỗi trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max($page, 1);
$perPage = 10;
$totalUsers = getTotalUsers($conn, $searchTerm, $roleFilter);
$totalPages = ceil($totalUsers / $perPage);

$users = getUsers($conn, $page, $perPage, $searchTerm, $roleFilter);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
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
                        <li class="nav-item">
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
                        <li class="nav-item" style="background:#575f66">
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản Lý tài khoản</h1>
                </div>

            
                <!-- Thanh lọc theo vai trò -->
<form action="list_account.php" method="GET">
    <div class="row mb-3">
        <div class="col-md-8">
            <input class="form-control" type="search" placeholder="Tìm kiếm tài khoản" name="search" aria-label="Search" value="<?php echo htmlspecialchars(trim($_GET['search'] ?? '', '%')); ?>">
        </div>
        <div class="col-md-2">
            <select class="form-control" name="role">
                <option value="">Chọn vai trò</option>
                <option value="1" <?php echo (isset($_GET['role']) && $_GET['role'] == 1) ? 'selected' : ''; ?>>Quản trị viên</option>
                <option value="2" <?php echo (isset($_GET['role']) && $_GET['role'] == 2) ? 'selected' : ''; ?>>Giáo viên</option>
                <option value="3" <?php echo (isset($_GET['role']) && $_GET['role'] == 3) ? 'selected' : ''; ?>>Phụ huynh</option>
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
                    <a href="add_account.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên đăng nhập</th>
                                <!-- <th>Mật khẩu</th> -->
                                <th>Vai trò</th>
                                <th>Họ và tên</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = ($page - 1) * $perPage + 1;
                            foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td><?php echo htmlspecialchars($user['username'] ?? ''); ?></td>
                                    <!-- <td><?php echo htmlspecialchars($user['password'] ?? ''); ?></td> -->
                                    <td>
                                        <?php 
                                            if ($user['role'] == 1) {
                                                echo 'Quản trị viên';
                                            } elseif ($user['role'] == 2) {
                                                echo 'Giáo viên';
                                            } elseif ($user['role'] == 3) {
                                                echo 'Phụ huynh';
                                            } else {
                                                echo 'Người dùng';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['FullName'] ?? ''); ?></td>
                                    <td>
                                        <a href="detail_account.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                                        <a href="edit_account.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                        <a href="delete_account.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
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
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo ($page > 1) ? '?search=' . urlencode(trim($_GET['search'] ?? '')) . '&page=' . ($page - 1) : '#'; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php
        // Xác định phạm vi các trang cần hiển thị
        $start = max(1, $page - 2); // Hiển thị tối thiểu 2 trang trước trang hiện tại
        $end = min($totalPages, $page + 2); // Hiển thị tối đa 2 trang sau trang hiện tại

        // Nếu trang đầu tiên không phải là trang 1, hiển thị trang 1 và dấu ba chấm
        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="?search=' . urlencode(trim($_GET['search'] ?? '')) . '&page=1">1</a></li>';
            if ($start > 2) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Hiển thị các trang trong phạm vi đã tính toán
        for ($i = $start; $i <= $end; $i++) {
            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                    <a class="page-link" href="?search=' . urlencode(trim($_GET['search'] ?? '')) . '&page=' . $i . '">' . $i . '</a>
                  </li>';
        }

        // Nếu trang cuối cùng không phải là trang cuối, hiển thị dấu ba chấm và trang cuối cùng
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?search=' . urlencode(trim($_GET['search'] ?? '')) . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
        }
        ?>

        <!-- Nút Next -->
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo ($page < $totalPages) ? '?search=' . urlencode(trim($_GET['search'] ?? '')) . '&page=' . ($page + 1) : '#'; ?>" aria-label="Next">
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