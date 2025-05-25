<?php
require_once '../../system/connect.php';
session_start();

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
} else {
    die("Event ID không hợp lệ!");
}

$stmt = $conn->prepare("SELECT eventname FROM event WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    $event_name = $event['eventname']; 
} else {
    die("Không tìm thấy sự kiện với ID này.");
}

// submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra có ảnh được tải lên không
    if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../images/uploads/'; 

        // Lặp qua các ảnh đã tải lên
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            $photo_name = basename($_FILES['photos']['name'][$key]);
            $photo_description = isset($_POST['photo_description'][$key]) ? $_POST['photo_description'][$key] : '';

            // Kiểm tra xem ảnh đã tồn tại chưa
            $stmt = $conn->prepare("SELECT id FROM eventphoto WHERE eventid = ? AND photo_name = ?");
            $stmt->bind_param("is", $event_id, $photo_name);
            $stmt->execute();
            $check_result = $stmt->get_result();

            if ($check_result->num_rows > 0) {
                echo "Ảnh '{$photo_name}' đã tồn tại trong sự kiện này. Không thể tải lên ảnh này.<br>";
                continue; 
            }

            // lưu vào thư mục upload
            $target_file = $upload_dir . $photo_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                // Lưu thông tin vào cơ sở dữ liệu
                $stmt = $conn->prepare("INSERT INTO eventphoto (eventid, photo_name, photo_description) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $event_id, $photo_name, $photo_description);
                if ($stmt->execute()) {
                    echo "Thêm ảnh '{$photo_name}' thành công!<br>";
                } else {
                    echo "Lỗi khi lưu thông tin ảnh '{$photo_name}' vào cơ sở dữ liệu.<br>";
                }
            } else {
                echo "Lỗi khi tải ảnh '{$photo_name}' lên.<br>";
            }
        }
        echo "<script>alert('Quá trình tải ảnh hoàn tất!'); window.location.href = 'event.php';</script>";
    } else {
        echo "Vui lòng chọn ít nhất một ảnh hợp lệ.";
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
    form {
        max-width: 60%;
        margin: 30px auto;
        font-family: Arial, sans-serif;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
        color: #555;
    }

    input[type="file"],
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .button-container1 {
    display: flex
;
    justify-content: center;
    margin-top: 20px;
    gap: 20px;
}

    button,
    a {
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
       
        text-decoration: none;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button {
        background-color: #4CAF50; /* Màu xanh lá */
    }

    button:hover {
        background-color: #45a049;
    }

    a {
        background-color: #f44336; /* Màu đỏ */
    }

    a:hover {
        background-color: #d32f2f;
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
            <h1>Thêm Ảnh  </h1>

                <a href="../../../index.php" class="btn btn-secondary">Quay lại Trang Chủ</a>
            </div>
            <form action="addphoto.php?event_id=<?= $event_id ?>" method="POST" enctype="multipart/form-data">
                <h1>Sự Kiện: <?= htmlspecialchars($event_name) ?> </h1>

                <label for="photos">Chọn ảnh:</label>
                <input type="file" name="photos[]" id="photos" multiple required>

                <label for="photo_description[]">Mô tả ảnh:</label>
                <input type="text" name="photo_description[]" placeholder="Mô tả ảnh ">

                <div class="button-container1">
                    <button type="submit">Tải lên</button>
                    <a href="event.php">Quay lại</a>
                </div>
            </form>


        </main>
    </div>
</div>

</body>
</html>



