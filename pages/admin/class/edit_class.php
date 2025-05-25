<?php
require_once '../../system/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM classes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();

    if (!$class) {
        echo "Class not found!";
        exit;
    }

    
    $teacherQuery = "SELECT id, FullName FROM teachers";
    $teacherResult = $conn->query($teacherQuery);
    if (!$teacherResult) {
        die("Error fetching teachers: " . $conn->error);
    }
    $teachers = $teacherResult->fetch_all(MYSQLI_ASSOC);

   
    $gradeQuery = "SELECT id, GradeName FROM Grade";
    $gradeResult = $conn->query($gradeQuery);
    if (!$gradeResult) {
        die("Error fetching grades: " . $conn->error);
    }
    $grades = $gradeResult->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $className = $_POST['ClassName'];
    $teacherId = $_POST['TeacherId'];
    $quantity = $_POST['Quantity'];
    $gradeId = $_POST['GradeId']; 

    // ktra tên tồn tại chưachưa
    $checkQuery = "SELECT * FROM classes WHERE ClassName = ? AND id != ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("si", $className, $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script type='text/javascript'>
                alert('Tên lớp đã tồn tại!');
                window.history.back();
              </script>";
        exit;
    }

    
    $query = "UPDATE classes SET ClassName = ?, TeacherId = ?, Quantity = ?, GradeID = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiii", $className, $teacherId, $quantity, $gradeId, $id);
    $stmt->execute();

    header('Location: list_class.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Lớp Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Sửa Lớp Học</h1>
        <form method="POST" action="edit_class.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($class['id']); ?>">
            <div class="mb-3">
                <label for="ClassName" class="form-label">Tên Lớp</label>
                <input type="text" class="form-control" id="ClassName" name="ClassName" value="<?php echo htmlspecialchars($class['ClassName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="TeacherId" class="form-label">Giáo Viên Chủ Nhiệm</label>
                <select class="form-control" id="TeacherId" name="TeacherId" required>
                    <option value="">Chọn giáo viên</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo htmlspecialchars($teacher['id']); ?>"
                            <?php echo ($class['TeacherId'] == $teacher['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($teacher['FullName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Quantity" class="form-label">Sĩ Số</label>
                <input type="text" class="form-control" id="Quantity" name="Quantity" value="<?php echo htmlspecialchars($class['Quantity']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="GradeId" class="form-label">Khối</label>
                <select class="form-control" id="GradeId" name="GradeId" required>
                    <option value="">Chọn khối</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?php echo htmlspecialchars($grade['id']); ?>"
                            <?php echo ($class['GradeID'] == $grade['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($grade['GradeName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="list_class.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
