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

// Lấy ID chương trình học từ query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: curriculum.php");
    exit();
}
$curriculum_id = intval($_GET['id']);

// Lấy thông tin chương trình học từ cơ sở dữ liệu
$sql = "SELECT title, content, image FROM curriculum WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curriculum_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: curriculum.php");
    exit();
}
$curriculum = $result->fetch_assoc();
$stmt->close();

// Xử lý khi form được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $curriculum['image']; // Giữ ảnh cũ nếu không tải lên ảnh mới
    
    // Xử lý tải lên ảnh mới
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../../../images/uploads/";
        $image = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Tải lên thành công, giữ tên ảnh mới
        } else {
            $image = $curriculum['image']; // Nếu tải lên thất bại, giữ ảnh cũ
        }
    }
    
    // Cập nhật dữ liệu vào database
    $sql = "UPDATE curriculum SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $content, $image, $curriculum_id);
    
    if ($stmt->execute()) {
        header("Location: curriculum.php");
        exit();
    } else {
        echo "<script>alert('Cập nhật thất bại!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Chương Trình Học</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sửa Chương Trình Học</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($curriculum['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <textarea class="form-control" id="content" name="content" rows="4" required><?php echo htmlspecialchars($curriculum['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            </div>
            <!-- Ảnh hiện tại và xem trước -->
            <div class="mb-3">
                <?php if (!empty($curriculum['image'])): ?>
                    <p>Ảnh hiện tại:</p>
                    <img src="../../../../images/uploads/<?php echo htmlspecialchars($curriculum['image']); ?>" alt="Ảnh hiện tại" class="img-thumbnail" style="max-width: 200px;">
                <?php endif; ?>
                <p>Xem trước ảnh mới:</p>
                <img id="preview" src="#" alt="Xem trước ảnh" class="img-thumbnail d-none" style="max-width: 200px;">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
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