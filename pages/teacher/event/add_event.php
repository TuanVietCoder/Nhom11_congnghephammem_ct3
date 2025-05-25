<?php
// Bao gồm kết nối cơ sở dữ liệu
require_once '../../system/connect.php';
session_start();
// Lấy teacherID từ session
$teacherID = $_SESSION['teacherID'] ?? ''; // Lấy teacherID từ session

// Kiểm tra nếu teacherID tồn tại
if (!$teacherID) {
    echo "Không tìm thấy thông tin giáo viên. Bạn cần đăng nhập để tiếp tục.";
    exit;
}

// Lấy ClassID mà giáo viên quản lý
$sql = "SELECT c.id FROM classes c
        JOIN teachers t ON c.TeacherID = t.id
        WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$class_result = $stmt->get_result();
$class = $class_result->fetch_assoc();

if (!$class) {
    echo "Không tìm thấy lớp học mà bạn quản lý.";
    exit;
}

$classID = $class['id']; // Lấy ClassID mà giáo viên quản lý

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventname = $_POST['eventname'] ?? null;
    $eventdescription = $_POST['eventdescription'] ?? null;
    $eventdate = $_POST['eventdate'] ?? null;

    // Kiểm tra dữ liệu trước khi chèn vào cơ sở dữ liệu
    if (empty($eventname) || empty($eventdescription) || empty($eventdate)) {
        echo "Vui lòng nhập đầy đủ thông tin sự kiện.";
        exit;
    }

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("INSERT INTO event (eventname, eventdescription, eventdate, ClassID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $eventname, $eventdescription, $eventdate, $classID);

    // Thực thi truy vấn
    if (!$stmt->execute()) {
        echo "Lỗi khi thực hiện truy vấn: " . $stmt->error;
        exit;
    } else {
        header("Location: event.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <title>Quản Lý Sự Kiện</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  
        <style>
/* Phong cách cho form */
form {
    max-width: 60%;
    margin: 50px auto;
    padding: 20px;
    background: linear-gradient(135deg, #ffffff, #f0f0f5);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

/* Phong cách cho nhãn */
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 1rem;
    color: #444;
}

/* Phong cách cho input và textarea */
form input[type="text"],
form input[type="datetime-local"],
form textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    background-color: #f9f9f9;
    box-sizing: border-box;
    transition: border-color 0.3s, box-shadow 0.3s;
}

/* Hiệu ứng khi focus */
form input[type="text"]:focus,
form input[type="datetime-local"]:focus,
form textarea:focus {
    border-color: #5b9bd5;
    outline: none;
    box-shadow: 0 0 5px rgba(91, 155, 213, 0.5);
}

/* Phong cách cho textarea */
form textarea {
    resize: vertical;
    min-height: 80px;
}

/* Container cho các nút */
.form-buttons {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

/* Phong cách cho các nút */
form input[type="submit"],
form button {
    flex: 1;
    padding: 10px;
    font-size: 1rem;
    font-weight: bold;
    color: white;
    background: linear-gradient(135deg, #4a90e2, #007aff);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    text-align: center;
}

/* Hiệu ứng hover và active */
form input[type="submit"]:hover,
form button:hover {
    background: linear-gradient(135deg, #007aff, #4a90e2);
}

form input[type="submit"]:active,
form button:active {
    transform: scale(0.98);
}

/* Nút quay lại có màu sắc khác */
form button {
    background: linear-gradient(135deg, #ff7b7b, #ff4a4a);
}

form button:hover {
    background: linear-gradient(135deg, #ff4a4a, #ff7b7b);
}


        </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar min-vh-100" style="padding:0;position: fixed;">
            <div class="position-sticky">
                <div class="p-3 text-white">
                    <h4>QUẢN LÝ TRƯỜNG HỌC</h4>
                </div>
                <ul class="nav flex-column">
                        <!-- <li class="nav-item">
                            <a class="nav-link text-white" href="/tai/pages/admin/dashboard.php">
                                <i class="fas fa-home me-2"></i>
                                Tổng quan
                            </a>
                        </li> -->
                        <li class="nav-item" >
                            <a class="nav-link text-white" href="/tai/pages/teacher/attendance.php">
                                <i class="fas fa-calendar-check me-2"></i>
                                Quản lý điểm danh
                            </a>
                        </li>
                        <li class="nav-item" >
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
                        <li class="nav-item" style="background:#575f66">
                            <a class="nav-link text-white" href="/tai/pages/teacher/event/event.php">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Sự kiện
                            </a>
                        </li>
                    </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Thêm sự kiện</h1>
                <a href="../../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
            </div>

            <!-- Form nhập thông tin sự kiện -->
<form action="add_event.php" method="post">
    <label for="eventname">Tên Sự Kiện:</label>
    <input type="text" name="eventname" id="eventname" required>

    <label for="eventdescription">Mô Tả:</label>
    <textarea name="eventdescription" id="eventdescription" rows="4" required></textarea>

    <label for="eventdate">Ngày Thực Hiện:</label>
    <input type="datetime-local" name="eventdate" id="eventdate" value="<?= date('Y-m-d\TH:i') ?>" required>

    <!-- Khu vực nút -->
    <div class="form-buttons">
        <input type="submit" value="Thêm Sự Kiện">
        <button type="button" onclick="history.back()">Quay Lại</button>
    </div>
</form>

        </main>
    </div>
</div>

</body>
</html>

