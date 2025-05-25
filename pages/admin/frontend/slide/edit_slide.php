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
// Kiểm tra xem ID có được truyền vào không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_slide.php");
    exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM slides WHERE id=$id");
$slide = $result->fetch_assoc();

// Xử lý cập nhật slide
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description_vn = $_POST['description_vn'];
    $description_en = $_POST['description_en'];
    $author = $_POST['author'];
    $button_text = $_POST['button_text'];
    $image_url = $slide['image_url'];

    // Xử lý tải lên hình ảnh mới nếu có
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../../../images/uploads/slide/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_url = "images/uploads/slide/" . basename($_FILES["image"]["name"]);
    }

    $stmt = $conn->prepare("UPDATE slides SET title=?, description_vn=?, description_en=?, author=?, button_text=?, image_url=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description_vn, $description_en, $author, $button_text, $image_url, $id);
    
    if ($stmt->execute()) {
        header("Location: list_slide.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Slide</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Chỉnh sửa Slide</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($slide['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description_vn" class="form-label">Mô tả (VN)</label>
            <textarea class="form-control" name="description_vn" required><?php echo htmlspecialchars($slide['description_vn']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="description_en" class="form-label">Mô tả (EN)</label>
            <textarea class="form-control" name="description_en" required><?php echo htmlspecialchars($slide['description_en']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Tác giả</label>
            <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($slide['author']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="button_text" class="form-label">Nội dung nút</label>
            <input type="text" class="form-control" name="button_text" value="<?php echo htmlspecialchars($slide['button_text']); ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="list_slide.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
