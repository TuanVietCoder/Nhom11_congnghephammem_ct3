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

// thêm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = $_POST['ClassName'];
    $teacherId = $_POST['TeacherId'];
    $gradeId = $_POST['GradeId'];  
    $quantity = $_POST['Quantity'];

   
    if (!empty($className) && !empty($teacherId) && !empty($gradeId) && !empty($quantity)) {

        // Kiểm tra  tên lớp đã tồn tại chưachưa
        $checkQuery = "SELECT * FROM classes WHERE ClassName = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $className);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Tên lớp này đã tồn tại, vui lòng chọn tên khác!";
        } else {
            // Nếu không trùng tên lớp, thực hiện thêm lớp mới
            $query = "INSERT INTO classes (ClassName, TeacherId, GradeID, Quantity) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siii", $className, $teacherId, $gradeId, $quantity);

            if ($stmt->execute()) {
                header("Location: list_class.php"); 
                exit();
            } else {
                $error = "Không thể thêm lớp, vui lòng thử lại!";
            }
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin!";
    }
}



$query = "SELECT id, FullName FROM teachers ORDER BY FullName ASC";
$teachersResult = $conn->query($query);
$teachers = $teachersResult->fetch_all(MYSQLI_ASSOC);



$queryGrade = "SELECT id, GradeName FROM Grade ORDER BY GradeName ASC";
$gradeResult = $conn->query($queryGrade);
$grades = $gradeResult->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Lớp Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thêm Lớp Học</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="add_class.php" method="POST">
                    <div class="mb-3">
                        <label for="ClassName" class="form-label">Tên Lớp</label>
                        <input type="text" class="form-control" id="ClassName" name="ClassName" required>
                    </div>

                    <div class="mb-3">
                        <label for="TeacherId" class="form-label">Giáo Viên Chủ Nhiệm</label>
                        <select class="form-select" id="TeacherId" name="TeacherId" required>
                            <option value="">Chọn giáo viên</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['FullName']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="GradeId" class="form-label">Khối</label>
                        <select class="form-select" id="GradeId" name="GradeId" required>
                            <option value="">Chọn khối</option>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['GradeName']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Quantity" class="form-label">Sĩ số</label>
                        <input type="number" class="form-control" id="Quantity" name="Quantity" required>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm Lớp
                    </button>
                    <a href="list_class.php" class="btn btn-secondary ms-2">Hủy</a>
                </form>

            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
