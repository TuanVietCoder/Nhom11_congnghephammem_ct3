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

// Xử lý khi form được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = "";
    
    // Xử lý tải lên ảnh
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../../../images/uploads/";
        $image = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Tải lên thành công
        } else {
            $image = ""; // Nếu tải lên thất bại
        }
    }
    
    // Thêm dữ liệu vào database
    $sql = "INSERT INTO curriculum (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $image);
    
    if ($stmt->execute()) {
        header("Location: curriculum.php");
        exit();
    } else {
        echo "<script>alert('Thêm mới thất bại!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Chương Trình Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Thêm Chương Trình Học</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            </div>
            <!-- Ảnh xem trước -->
            <div class="mb-3">
                <img id="preview" src="#" alt="Xem trước ảnh" class="img-thumbnail d-none" style="max-width: 200px;">
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="curriculum.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script>
        function previewImage(event) {
            let input = event.target;
            let preview = document.getElementById("preview");

            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove("d-none"); // Hiện ảnh
                }
                reader.readAsDataURL(input.files[0]); // Đọc ảnh
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
