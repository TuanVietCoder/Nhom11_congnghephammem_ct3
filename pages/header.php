<?php




session_start();
require_once 'system/connect.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    $userID = $_SESSION['id']; // Lấy userID từ session

    $fullname = '';

    if ($role == 1) {
        // Vai trò admin
        $fullname = $_SESSION['username'];
    } elseif ($role == 2) {
        // Vai trò giáo viên
        $query = "SELECT teachers.FullName 
                  FROM users 
                  JOIN teachers ON users.TeacherID = teachers.id 
                  WHERE users.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fullname = $row['FullName'];
        }
    } elseif ($role == 3) {
        // Vai trò học sinh
        $query = "SELECT students.FullName 
                  FROM users 
                  JOIN students ON users.StudentID = students.id 
                  WHERE users.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fullname = $row['FullName'];
        }
    }
}

// Kiểm tra xem form đã được submit hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-contact'])) {
    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $content = $conn->real_escape_string($_POST['content']);

    // Câu lệnh SQL để thêm dữ liệu vào bảng contact
    $sql = "INSERT INTO contact (name, phone, email, content) VALUES ('$name', '$phone', '$email', '$content')";

    // Thực thi câu lệnh SQL
    if ($conn->query($sql) === TRUE) {
        echo "Dữ liệu đã được gửi thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}
?>


<body>
    <section class="header">
        <header>
            <div class="header-top">
                <p>Thời gian làm việc: Thứ 2 - Thứ 6: <span class="clock">7:00 - 18:00</span></p>
            </div>
            <style>
                .header-top {
                    overflow: hidden; /* Ẩn nội dung tràn ra ngoài */
                    white-space: nowrap; /* Giữ văn bản trên một dòng */
                }

                .header-top p {
                    display: inline-block; /* Cho phép animation di chuyển */
                    animation: marquee 10s linear infinite; /* Hiệu ứng cuộn, 10s, chạy liên tục */
                }

                @keyframes marquee {
                    0% { transform: translateX(300%); } /* Bắt đầu từ bên phải */
                    100% { transform: translateX(-100%); } /* Kết thúc ở bên trái */
                }
            </style>
            <div class="header-main" >
                <div class="logo">
                    <a style="display: flex; justify-content: center;" href="../index.php"><img src="../images/logo.png" alt="logo"></a>
                </div>
                <div class="menu">
                    <ul>
                        <li class="menu-text">
                            <p><a href="../index.php">Trang chủ</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="Curriculum.php">Chương trình học</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="menu.php">Thực đơn</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="Admission.php">Tuyển sinh</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="event.php">Tin tức và sự kiện</a></p> 
                        </li>

                        <li class="menu-text"><button class="header-button" id="openPopup">Đăng kí tư vấn</button></li>
                        
                        <li class="menu-text">
                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="logout.php" style="text-decoration: none; margin: auto;">Đăng xuất</a>
                            <?php else: ?>
                                <a href="login.php" style="text-decoration: none; margin: auto;">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                        <li class="menu-text" style="align-items: center;">
                            <?php if (!isset($_SESSION['username'])): ?>
                                <a href="signup.php" style="text-decoration: none; margin: auto;">Đăng ký</a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle"
                                        style="text-decoration: none; margin: auto;">
                                       
                                        <?php echo $fullname; ?> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <div class="dropdown-menu" style="left: -74px;">
                                        <a href="profile.php">Hồ sơ cá nhân</a>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                            <a href="admin/dashboard.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                                            <a href="teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                                            <a href="teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <a href="logout.php">Đăng xuất</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>

                    </ul>
                    <div class="popup-overlay" id="popupOverlay">
                        <div class="popup-content">
                            <div class="close-btn" id="closePopup">&times;</div>
                            <h2>Đăng ký tư vấn</h2>
                            <form action="../index.php" method="post">
                                <input type="text" name="name" placeholder="Họ và tên" required>
                                <input type="text" name="phone" placeholder="Số điện thoại" required>
                                <input type="email" name="email" placeholder="Email" required>
                                <textarea name="content" placeholder="Nội dung cần tư vấn" rows="4" required></textarea>
                                <button type="submit" name="btn-contact">Gửi thông tin</button>
                            </form>
                        </div>
                        
                    </div>
                </div>

            </div>

        </header>

    </section>
    <!-- Thanh header cố định khi cuộn xuống -->
<div id="fixedHeader" class="fixed-header">
    <div class="header-main">
                <div class="logo">
                    <a style="display: flex; justify-content: center;" href="../index.php"><img src="../images/logo.png" alt="logo"></a>
                </div>
                <div class="menu">
                    <ul>
                        <li class="menu-text">
                            <p><a href="../index.php">Trang chủ</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="Curriculum.php">Chương trình học</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="menu.php">Thực đơn</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="Admission.php">Tuyển sinh</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="event.php">Tin tức và sự kiện</a></p> 
                        </li>

                        <li class="menu-text"><button class="header-button" id="openPopup">Đăng kí tư vấn</button></li>
                        
                        <li class="menu-text">
                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="logout.php" style="text-decoration: none; margin: auto;">Đăng xuất</a>
                            <?php else: ?>
                                <a href="login.php" style="text-decoration: none; margin: auto;">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                        <li class="menu-text" style="align-items: center;">
                            <?php if (!isset($_SESSION['username'])): ?>
                                <a href="signup.php" style="text-decoration: none; margin: auto;">Đăng ký</a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle"
                                        style="text-decoration: none; margin: auto;">
                                        <?php echo $fullname; ?> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <div class="dropdown-menu" style="left: -74px;">
                                        <a href="profile.php">Hồ sơ cá nhân</a>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                            <a href="admin/dashboard.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                                            <a href="teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                                            <a href="teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <a href="logout.php">Đăng xuất</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>

                    </ul>
                    <div class="popup-overlay" id="popupOverlay">
                        <div class="popup-content">
                            <div class="close-btn" id="closePopup">&times;</div>
                            <h2>Đăng ký tư vấn</h2>
                            <form action="../index.php" method="post">
                                <input type="text" name="name" placeholder="Họ và tên" required>
                                <input type="text" name="phone" placeholder="Số điện thoại" required>
                                <input type="email" name="email" placeholder="Email" required>
                                <textarea name="content" placeholder="Nội dung cần tư vấn" rows="4" required></textarea>
                                <button type="submit" name="btn-contact">Gửi thông tin</button>
                            </form>
                        </div>
                        
                    </div>
                </div>

            </div>
</div>
