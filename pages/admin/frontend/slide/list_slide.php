<?php
require_once '../../../system/connect.php'; 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['role']) && intval($_SESSION['role']) !== 1) {
    header("Location: ../../index.php");
    exit();
}


// Lấy danh sách slide
$result = $conn->query("SELECT * FROM slides");

// contact 
// Lấy dữ liệu từ bảng `contactTT`
$sql1 = "SELECT * FROM contactTT LIMIT 1";
$result11 = $conn->query($sql1);
$row11 = $result11->fetch_assoc();

// Xử lý cập nhật dữ liệu khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotline = $_POST["hotline"];
    $email = $_POST["email"];

    $update_sql = "UPDATE contactTT SET hotline='$hotline', email='$email' WHERE id=1";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Cập nhật thành công!');</script>";
        header("Refresh:0"); // Tải lại trang để cập nhật dữ liệu
    } else {
        echo "Lỗi cập nhật: " . $conn->error;
    }
}

//content
$id = 1;
$sql3 = "SELECT * FROM content WHERE id = ?";
$stmt = $conn->prepare($sql3);
$stmt->bind_param("i", $id);
$stmt->execute();
$result3 = $stmt->get_result();
$row3 = $result3->fetch_assoc();

// Xử lý tải ảnh lên
function uploadImage($file, $currentImage) {
    $uploadDir = realpath(__DIR__ . '/../../../../images/uploads/') . '/'; // Đường dẫn tuyệt đối đến thư mục lưu ảnh

    

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileName = time() . "_" . basename($file["name"]); // Tránh trùng tên file
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "images/uploads/" . $fileName; // Lưu đường dẫn tương đối vào CSDL
        }
    }
    return $currentImage; // Giữ nguyên ảnh cũ nếu không có ảnh mới
}


// Xử lý cập nhật dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $description1 = $_POST['description1'];
    $description2 = $_POST['description2'];
    
    $image1 = uploadImage($_FILES['image1'], $row3['image1']);
    $image2 = uploadImage($_FILES['image2'], $row3['image2']);
    $image3 = uploadImage($_FILES['image3'], $row3['image3']);

    $update_sql = "UPDATE content SET name=?, title=?, description1=?, description2=?, image1=?, image2=?, image3=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssi", $name, $title, $description1, $description2, $image1, $image2, $image3, $id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Cập nhật thành công!</div>";
        header("Refresh:0");
    } else {
        echo "<div class='alert alert-danger'>Lỗi khi cập nhật!</div>";
    }
}


$conn->close();
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
.imageslide{
            width: 150px;
        }
        .table-responsive table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.table-responsive th, .table-responsive td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.table-responsive th {
    background-color:rgb(6 70 132);
    color: white;
    text-transform: uppercase;
}

.table-responsive tr:hover {
    background-color: #f1f1f1;
}

.actions {
    text-align: center;
}

.actions .btn {
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 3px;
    text-decoration: none;
}

.actions .edit {
    background-color: #ffc107;
    color: white;
}

.actions .delete {
    background-color: #dc3545;
    color: white;
}

.actions .edit:hover {
    background-color: #e0a800;
}

.actions .delete:hover {
    background-color: #c82333;
}

.imageslide {
    width: 100px;
    height: auto;
    border-radius: 5px;
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
                        <li class="nav-item" >
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
                        <li class="nav-item" style="background:#575f66">
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
                    <h1 class="h2">Quản Lý Slides </h1>
                </div>


                <!-- Nút thêm mới -->
                <div class="mb-3">
                    <a href="add_slide.php" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>

                <!-- Bảng danh sách giáo viên -->
                <div class="table-responsive">
                    <table>
                    <tr>
                    
                        <th>Tiêu đề</th>
                        <th>Mô tả (VN)</th>
                        <th>Mô tả (EN)</th>
                        <th>Tác giả</th>
                        <th>Nội dung nút</th>
                        <th>Hình ảnh</th>
                        <th></th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                        
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['description_vn'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['description_en'])); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['button_text']); ?></td>
                            <td><img class="imageslide" src="../../../../<?php echo htmlspecialchars($row['image_url']); ?>" alt="Slide Image"></td>
                            <td class="actions">
                                <a class="btn edit" href="edit_slide.php?id=<?php echo $row['id']; ?>">Sửa</a>
                                <a class="btn delete" href="delete_slide.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                                </table>
                </div>

                
    </ul>



            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thông tin liên hệ </h1>
                </div>
                <div class="container mt-5">
        
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Hotline:</label>
                            <input type="text" class="form-control" name="hotline" value="<?= htmlspecialchars($row11['hotline'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($row11['email'] ?? '') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu </button>
                    </form>
                </div>

            </main>

            <!-- connet -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thông tin nội dung </h1>
                </div>
                <div class="container mt-5">
                <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row3['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tiêu đề:</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row3['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả 1:</label>
                <textarea name="description1" class="form-control" required><?php echo htmlspecialchars($row3['description1']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả 2:</label>
                <textarea name="description2" class="form-control" required><?php echo htmlspecialchars($row3['description2']); ?></textarea>
            </div>
            
            <?php for ($i = 1; $i <= 3; $i++): ?>
<div class="mb-3">
    <label class="form-label">Ảnh <?php echo $i; ?>:</label>
    <input type="file" name="image<?php echo $i; ?>" class="form-control" accept="image/*" onchange="previewImage(event, <?php echo $i; ?>)">
    
    <!-- Hiển thị ảnh từ thư mục đã lưu -->
    <img id="preview-image<?php echo $i; ?>" 
         src="<?php echo !empty($row3['image' . $i]) ? htmlspecialchars('../../../../' . $row3['image' . $i]) : ''; ?>" 
         alt="Ảnh <?php echo $i; ?>" 
         class="mt-2" 
         style="width: 100px; height: auto; display: <?php echo !empty($row3['image' . $i]) ? 'block' : 'none'; ?>;">
</div>
<?php endfor; ?>

            
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
                    
                </div>

            </main>








        </div>
     
      

    </div>
    <script>
        function previewImage(event, index) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview-image' + index);
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>






