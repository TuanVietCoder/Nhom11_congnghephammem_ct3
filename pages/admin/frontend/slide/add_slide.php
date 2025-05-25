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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description_vn = $_POST['description_vn'];
    $description_en = $_POST['description_en'];
    $author = $_POST['author'];
    $button_text = $_POST['button_text'];
    
    $target_dir = "../../../../images/uploads/slide/";
    $image_url = "";
    if (!empty($_FILES["image"]["name"])) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = "images/uploads/slide/" . basename($_FILES["image"]["name"]);
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO slides (title, description_vn, description_en, author, button_text, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description_vn, $description_en, $author, $button_text, $image_url);
    
    if ($stmt->execute()) {
        header("Location: list_slide.php");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }
   
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Slide Mới</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Thêm Slide Mới</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description_vn" class="form-label">Mô tả (VN):</label>
                <textarea class="form-control" id="description_vn" name="description_vn" required></textarea>
            </div>
            <div class="mb-3">
                <label for="description_en" class="form-label">Mô tả (EN):</label>
                <textarea class="form-control" id="description_en" name="description_en" required></textarea>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Tác giả:</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="mb-3">
                <label for="button_text" class="form-label">Nội dung nút:</label>
                <input type="text" class="form-control" id="button_text" name="button_text" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="list_slide.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
