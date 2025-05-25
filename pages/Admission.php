<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/9574f0deef.js" crossorigin="anonymous"></script>
    <title>Mầm non 11 - Đăng ký nhập học</title>
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input[type="file"] {
            padding: 3px;
        }
        .form-group textarea {
            height: 100px;
        }
        .form-group .error {
            color: red;
            font-size: 0.9em;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <section class="blog">
        <div class="banner">
            <img class="img-event" src="../images/Admission.png" alt="Admission Banner">
        </div>
        <div class="content4">
            <h1 class="title4">Phiếu Đăng Ký Nhập Học</h1>

            <!-- Form Đăng ký -->
            <div class="form-container">
                <?php
                // Kết nối cơ sở dữ liệu
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "ql_mamnon_nhom11";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }

                $errors = [];
                $success = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Lấy dữ liệu từ form
                    $full_name = trim($_POST['full_name']);
                    $date_of_birth = trim($_POST['date_of_birth']);
                    $gender = trim($_POST['gender']);
                    $address = trim($_POST['address']);
                    $parent_name = trim($_POST['parent_name']);
                    $parent_phone = trim($_POST['parent_phone']);
                    $email = trim($_POST['email']);

                    // Xác thực dữ liệu
                    if (empty($full_name)) $errors[] = "Tên trẻ là bắt buộc.";
                    if (empty($date_of_birth)) $errors[] = "Ngày sinh là bắt buộc.";
                    if (empty($gender)) $errors[] = "Giới tính là bắt buộc.";
                    if (empty($address)) $errors[] = "Địa chỉ là bắt buộc.";
                    if (empty($parent_name)) $errors[] = "Tên phụ huynh là bắt buộc.";
                    if (empty($parent_phone)) $errors[] = "Số điện thoại phụ huynh là bắt buộc.";
                    if (empty($email)) $errors[] = "Email là bắt buộc.";
                    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";

                    // Xử lý upload ảnh
                    $photo_path = "";
                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                        $file_name = $_FILES['photo']['name'];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $file_size = $_FILES['photo']['size'];
                        $file_tmp = $_FILES['photo']['tmp_name'];

                        if (!in_array($file_ext, $allowed)) {
                            $errors[] = "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif).";
                        } elseif ($file_size > 5 * 1024 * 1024) {
                            $errors[] = "Kích thước file ảnh không được vượt quá 5MB.";
                        } else {
                            // Đường dẫn tuyệt đối tới thư mục uploads
                            $upload_dir = dirname(__DIR__) . '/uploads/';
                            // Tạo thư mục nếu chưa tồn tại
                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }

                            $new_file_name = uniqid() . '.' . $file_ext;
                            $photo_path = $upload_dir . $new_file_name;

                            if (!move_uploaded_file($file_tmp, $photo_path)) {
                                $errors[] = "Không thể upload ảnh. Vui lòng kiểm tra quyền thư mục uploads.";
                            } else {
                                // Lưu đường dẫn tương đối để lưu vào database
                                $photo_path = 'uploads/' . $new_file_name;
                            }
                        }
                    }

                    // Nếu không có lỗi, lưu vào cơ sở dữ liệu
                    if (empty($errors)) {
                        $sql = "INSERT INTO students (FullName, DateOfBirth, Gender, Address, ParentName, ParentPhone, Email, PhotoPath) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssss", $full_name, $date_of_birth, $gender, $address, $parent_name, $parent_phone, $email, $photo_path);

                        if ($stmt->execute()) {
                            $success = "Đăng ký thành công! Nhà trường sẽ liên hệ với bạn sớm.";
                        } else {
                            $errors[] = "Lỗi khi lưu thông tin: " . $conn->error;
                        }
                        $stmt->close();
                    }
                }

                //$conn->close();
                ?>

                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <h2 class="subtitle4">Thông tin trẻ</h2>
                    <div class="form-group">
                        <label for="full_name">Họ và tên trẻ *</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Ngày sinh *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính *</label>
                        <select id="gender" name="gender">
                            <option value="">Chọn giới tính</option>
                            <option value="Nam" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>

                    <h2 class="subtitle4">Thông tin phụ huynh</h2>
                    <div class="form-group">
                        <label for="parent_name">Họ và tên phụ huynh *</label>
                        <input type="text" id="parent_name" name="parent_name" value="<?php echo isset($_POST['parent_name']) ? htmlspecialchars($_POST['parent_name']) : ''; ?>">
                    </div>

                    <h2 class="subtitle4">Thông tin liên lạc</h2>
                    <div class="form-group">
                        <label for="parent_phone">Số điện thoại phụ huynh *</label>
                        <input type="tel" id="parent_phone" name="parent_phone" value="<?php echo isset($_POST['parent_phone']) ? htmlspecialchars($_POST['parent_phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <h2 class="subtitle4">Nơi cư trú</h2>
                    <div class="form-group">
                        <label for="address">Địa chỉ *</label>
                        <textarea id="address" name="address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>

                    <h2 class="subtitle4">Hình giấy tờ</h2>
                    <div class="form-group">
                        <label for="photo">Upload (jpg, jpeg, png, gif, tối đa 5MB)</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>

                    <button type="submit" class="submit-btn">Gửi đăng ký</button>
                </form>
            </div>

            <!-- Nội dung gốc -->
            <div class="step4">
                <h2 class="subtitle4">1. Hoàn tất Phiếu Thông tin Tuyển sinh</h2>
                <p class="p-css">Quý phụ huynh vui lòng điền đầy đủ thông tin trong Phiếu Thông tin Tuyển sinh qua kênh website trực tuyến, hoặc tải Phiếu Thông tin Tuyển sinh về và gửi email đến Phòng Tuyển sinh nhà trường.</p>
            </div>

            <div class="step4">
                <h2 class="subtitle4">2. Tham quan trường và phỏng vấn</h2>
                <p class="p-css">Nhà trường sẽ liên hệ Quý phụ huynh để xếp lịch tham quan trường và tham dự buổi phỏng vấn cùng Hiệu trưởng và Giám đốc học thuật.</p>
                <p class="p-css">Bé sẽ tham gia hoạt động cùng giáo viên trong buổi phỏng vấn. Các bé có nhu cầu đặc biệt sẽ có Giám đốc Chương trình giáo dục đặc biệt tham gia.</p>
            </div>

            <div class="step4">
                <h2 class="subtitle4">3. Xét duyệt hồ sơ và thông báo kết quả tuyển sinh</h2>
                <p class="p-css">Tất cả hồ sơ và đánh giá phỏng vấn đều do Hiệu trưởng và Giám đốc Học thuật xét duyệt.</p>
                <p class="p-css">Thông báo kết quả tuyển sinh:</p>
                <ul class="ul-css">
                    <li>Chấp nhận tuyển sinh.</li>
                    <li>Danh sách chờ.</li>
                    <li>Từ chối tuyển sinh.</li>
                </ul>
            </div>

            <div class="step4">
                <h2 class="subtitle4">4. Xác nhận nhập học</h2>
                <p class="p-css">Quý phụ huynh điền Phiếu Đăng ký Nhập học và hoàn thiện hồ sơ nhập học theo quy định:</p>
                <ul class="ul-css">
                    <li class="li-css">Bản sao công chứng giấy khai sinh và hộ khẩu.</li>
                    <li class="li-css">Giấy khám sức khỏe.</li>
                    <li class="li-css">Bản sao giấy chứng nhận đã tiêm chủng hoặc bản sao sổ tiêm chủng của học trò.</li>
                    <li class="li-css">Thông tin đưa đón.</li>
                    <li class="li-css">Thông tin sức khỏe.</li>
                    <li class="li-css">Thư thỏa thuận.</li>
                    <li class="li-css">Ký Thư chấp nhận tuyển sinh.</li>
                    <li class="li-css">Hoàn thành các khoản phí theo quy định.</li>
                </ul>
            </div>
        </div>
    </section>
</body>
</html>

<?php include "footer.php"; ?>

<?php
// Đóng kết nối sau khi tất cả các truy vấn đã hoàn tất
$conn->close();
?>