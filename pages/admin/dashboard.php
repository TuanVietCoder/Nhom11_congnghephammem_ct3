<?php
session_start();
require_once('../system/connect.php');

//echo 'Role: ' . (isset($_SESSION['role']) ? $_SESSION['role'] : 'Not set');
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
    header("Location: ../../index.php");
    exit();
}
function getDashboardStats($conn)
{
    $stats = [];

    // Truy vấn tổng số giáo viên, học sinh và lớp học
    $teacherQuery = "SELECT COUNT(*) as teacher_count FROM teachers";
    $studentQuery = "SELECT COUNT(*) as student_count FROM students";
    $classQuery = "SELECT COUNT(*) as class_count FROM classes";
    $accountQuery = "SELECT COUNT(*) as account_count FROM users";

    // Lấy dữ liệu
    $stmt = $conn->query($teacherQuery);
    $row = $stmt->fetch_assoc();
    $stats['teachers'] = $row['teacher_count'];

    $stmt = $conn->query($studentQuery);
    $row = $stmt->fetch_assoc();
    $stats['students'] = $row['student_count'];

    $stmt = $conn->query($classQuery);
    $row = $stmt->fetch_assoc();
    $stats['classes'] = $row['class_count'];

    $stmt = $conn->query($accountQuery);
    $row = $stmt->fetch_assoc();
    $stats['users'] = $row['account_count'];

    return $stats;
}


// Lấy danh sách giáo viên
function getTeachers($conn, $page = 1, $perPage = 10)
{
    $offset = ($page - 1) * $perPage;
    $query = "SELECT * FROM teachers ORDER BY id DESC LIMIT $offset, $perPage";
    $stmt = $conn->query($query);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

// Lấy danh sách học sinh
function getStudents($conn, $page = 1, $perPage = 10)
{
    $offset = ($page - 1) * $perPage;
    $query = "SELECT s.*, c.ClassName	 
              FROM students s 
              LEFT JOIN classes c ON s.ClassID = c.id 
              ORDER BY s.ClassID DESC LIMIT $offset, $perPage";

    $stmt = $conn->query($query);
    return $stmt->fetch_all(MYSQLI_ASSOC);
}




// Get stats and data
$stats = getDashboardStats($conn);
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$teachers = getTeachers($conn, $page);
$students = getStudents($conn, $page);


?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Trường Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
                        <li class="nav-item" style="background:#575f66">
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
                                Quản lý học sinhh
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
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/attendance/list_attendance.php">
                                <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/timetable/timetable.php">
                                <i class="fas fa-clock me-2"></i>
                                Quản lý thời khóa biểu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/slide/list_slide.php">
                                <i class="fas fa-layer-group me-2"></i>
                                Quản lý trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/frontend/slide/list_slide.php">
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
            <main class="col-md-10 ms-sm-auto px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1>Tổng quan</h1>
                    <a href="../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
                </div>

                <!-- Dashboard Stats -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Tổng số giáo viên</h5>
                                <p class="card-text h2"><?php echo $stats['teachers']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Tổng số học sinh</h5>
                                <p class="card-text h2"><?php echo $stats['students']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Tổng số lớp học</h5>
                                <p class="card-text h2"><?php echo $stats['classes']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Tổng số tài khoản</h5>
                                <p class="card-text h2"><?php echo $stats['users']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teachers Table -->
                <div class="col-12">
                    <h2>Danh sách giáo viên</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>

                                    <th>Họ và tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $teacher): ?>
                                    <tr>

                                        <td><?php echo $teacher['FullName']; ?></td>
                                        <td><?php echo $teacher['Phone']; ?></td>
                                        <td><?php echo $teacher['Email']; ?></td>
                                        <td><?php echo $teacher['Address']; ?></td>
                                        <td>
                                            <a href="teacher/edit_teacher.php?id=<?php echo $teacher['id']; ?>"
                                                class="btn btn-sm btn-primary">Sửa</a>
                                            <a href="teacher/delete_teacher.php?id=<?php echo $teacher['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="col-12 mt-4">
                    <h2>Danh sách học sinh</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>

                                    <th>Họ và tên</th>
                                    <th>Lớp</th>
                                    <th>Ngày sinh</th>
                                    <th>Địa chỉ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>

                                        <td><?php echo $student['FullName']; ?></td>
                                        <td><?php echo $student['ClassName']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($student['DateOfBirth'])); ?></td>
                                        <td><?php echo $student['Address']; ?></td>
                                        <td>
                                            <a href="student/edit_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-sm btn-primary">Sửa</a>
                                            <a href="student/delete_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </main>
        </div>
    </div>
</body>

</html>

