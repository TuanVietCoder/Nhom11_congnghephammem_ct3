<?php
session_start();
require_once 'pages/system/connect.php';

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


$sql = "SELECT * FROM slides";
$result = $conn->query($sql);

// Truy vấn SQL để lấy dữ liệu từ bảng contacttt
$sql1 = "SELECT hotline, email FROM contacttt";
$result11 = $conn->query($sql1);

// Kiểm tra nếu có kết quả
$hotline = '';
$email = '';
if ($result11->num_rows > 0) {
    // Lấy dữ liệu từ hàng đầu tiên (hoặc có thể lấy tất cả dữ liệu nếu cần)
    $row = $result11->fetch_assoc();
    $hotline = $row["hotline"];
    $email = $row["email"];
} else {
    $hotline = "Không có dữ liệu";
    $email = "Không có dữ liệu";
}
//content
// Truy vấn dữ liệu
$sql2 = "SELECT name, title, description1, description2, image1, image2, image3 FROM content WHERE id = 1";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
} else {
    echo "Không có dữ liệu";
    exit();
}
// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mầm non 11</title>
    <script src="https://kit.fontawesome.com/9574f0deef.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>


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
            <div class="header-main">
                <div class="logo">
                    <a style="display: flex; justify-content: center;" href="index.php"><img src="images/logo.png" alt="logo"></a>
                </div>

                <!-- Hamburger icon -->
                 <div class="hamburger" id="hamburgerMenu">&#9776;</div>

                <div class="menu">
                    <ul>
                        <li class="menu-text">
                            <p><a href="index.php">Trang chủ</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/Curriculum.php">Chương trình học</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/menu.php">Thực đơn</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/Admission.php">Tuyển sinh</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/event.php">Tin tức và sự kiện</a></p> 
                        </li>

                        <li class="menu-text"><button class="header-button" id="openPopup">Đăng kí tư vấn</button></li>
                        
                        <li class="menu-text">
                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="pages/logout.php" style="text-decoration: none; margin: auto;">Đăng xuất</a>
                            <?php else: ?>
                                <a href="pages/login.php" style="text-decoration: none; margin: auto;">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                        <li class="menu-text" style="align-items: center;">
                            <?php if (!isset($_SESSION['username'])): ?>
                                <a href="pages/signup.php" style="text-decoration: none; margin: auto;">Đăng ký</a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle"
                                        style="text-decoration: none; margin: auto;">
                                        <?php echo $fullname; ?> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <div class="dropdown-menu" style="left: -74px;">
                                        <a href="pages/profile.php">Hồ sơ cá nhân</a>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                            <a href="pages/admin/dashboard.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                                            <a href="pages/teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                                            <a href="pages/student/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <a href="pages/logout.php">Đăng xuất</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>

                    </ul>
                    <div class="popup-overlay" id="popupOverlay">
                        <div class="popup-content">
                            <div class="close-btn" id="closePopup">&times;</div>
                            <h2>Đăng ký tư vấn</h2>
                            <form action="index.php" method="post">
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
                    <a style="display: flex; justify-content: center;" href="index.php"><img src="images/logo.png" alt="logo"></a>
                </div>
                <div class="menu">
                    <ul>
                        <li class="menu-text">
                            <p><a href="index.php">Trang chủ</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/Curriculum.php">Chương trình học</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/menu.php">Thực đơn</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/Admission.php">Tuyển sinh</a></p> 
                        </li>
                        <li class="menu-text">
                            <p><a href="./pages/event.php">Tin tức và sự kiện</a></p> 
                        </li>

                        <li class="menu-text"><button class="header-button" id="openPopup">Đăng kí tư vấn</button></li>
                        
                        <li class="menu-text">
                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="pages/logout.php" style="text-decoration: none; margin: auto;">Đăng xuất</a>
                            <?php else: ?>
                                <a href="pages/login.php" style="text-decoration: none; margin: auto;">Đăng nhập</a>
                            <?php endif; ?>
                        </li>
                        <li class="menu-text" style="align-items: center;">
                            <?php if (!isset($_SESSION['username'])): ?>
                                <a href="pages/signup.php" style="text-decoration: none; margin: auto;">Đăng ký</a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle"
                                        style="text-decoration: none; margin: auto;">
                                        <?php echo $fullname; ?> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <div class="dropdown-menu" style="    left: -74px;">
                                        <a href="pages/profile.php">Hồ sơ cá nhân</a>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                            <a href="pages/admin/dashboard.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                                            <a href="pages/teacher/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                                            <a href="pages/student/attendance.php">Quản trị</a>
                                        <?php endif; ?>
                                        <a href="pages/logout.php">Đăng xuất</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>

                    </ul>
                    <div class="popup-overlay" id="popupOverlay">
                        <div class="popup-content">
                            <div class="close-btn" id="closePopup">&times;</div>
                            <h2>Đăng ký tư vấn</h2>
                            <form action="index.php" method="post">
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

<section class="slider">
<div class="slider-container">
    <?php 
    $active = "active"; // Chỉ slide đầu tiên có class "active"
    while ($row = $result->fetch_assoc()): ?>
        <div class="slide <?php echo $active; ?>" style="background-image: url('<?php echo $row['image_url']; ?>');">
            <div class="slide-content">
                <h1><?php echo $row['title']; ?></h1>
                <p><?php echo $row['description_vn']; ?></p>
                <p><?php echo $row['description_en']; ?></p>
                <p>– <?php echo $row['author']; ?> –</p>
                <button><?php echo $row['button_text']; ?></button>
            </div>
        </div>
    <?php 
    $active = ""; // Chỉ slide đầu tiên có class "active"
    endwhile;
    ?>
     <div class="slider-nav">
            <i class="fa-solid fa-caret-left" id="prev"></i>
            <i class="fa-solid fa-caret-right" id="next"></i>
        </div>
</div>


</section>

<!-- -----------------------------------------------content----------- -->
<section class="content">
    <div class="content">
        <h2 class="name"><?php echo htmlspecialchars($row2['name']); ?></h2>
        <h1 class="title"><?php echo htmlspecialchars($row2['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($row2['description1'])); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row2['description2'])); ?></p>
    </div>
    <div class="images-content">
        <div class="img-content">
            <img src="<?php echo htmlspecialchars($row2['image1']); ?>" alt="ảnh 1">
        </div>
        <div class="img-content">
            <img src="<?php echo htmlspecialchars($row2['image2']); ?>" alt="ảnh 2">
        </div>
        <div class="img-content">
            <img src="<?php echo htmlspecialchars($row2['image3']); ?>" alt="ảnh 3">
        </div>
    </div>
</section>

<section class="ss-project" style="background-image: url('images/bg-project.png');">
    <div class="project">
        <div class="project-title">
            <div class="pr-title">
            <h2 >Trường Mầm Non 11</h2>
            <h1 >Dự án của bé</h1>
            </div>
        </div>
        <div class="project-content">
            <p class="pr-content">Phương pháp Giáo dục theo dự án – Project-based learning mang đến cho bé cơ hội khám phá thế giới, với niềm hứng thú say mê. Từ sự tò mò ban đầu, trẻ được “nghiên cứu” từng chủ đề, được trải nghiệm thực tế để nâng cao óc quan sát, kỹ năng tư duy và tổng hợp thông tin, từ đó tiếp nhận kiến thức một cách sinh động nhất</p>
        </div>
    </div>
    <div class="project">
        <div class="project-baby">
            <div class="project-item">
                <div class="pr-img">
                    <a href=""><img src="images/logo.png" alt="project"></a>
                </div>
                <div class="pr-name">
                    <h3>Trải nghiệm rạp phim 1/6 tại trường Mầm Non 11</h3>
                    <p>Tại Rạp chiếu phim của Trường Mầm Non 11 các bạn nhỏ đã được tham [...]</p>
                </div>
            </div>
            <div class="project-item">
                <div class="pr-img">
                    <a href=""><img src="images/logo.png" alt="project"></a>
                </div>
                <div class="pr-name">
                    <h3>Lễ tốt nghiệp của các bé trường Mầm non 11</h3>
                    <p>HỆ THỐNG TRƯỜNG MẦM NON 11 CS 1 Mầm non 11 montessori – Cát [...]</p>
                </div>
            </div>
            <div class="project-item">
                <div class="pr-img">
                    <a href=""><img src="images/logo.png" alt="project"></a>
                </div>
                <div class="pr-name">
                    <h3>Thăm quan nhà sách – Ngày hội đọc sách</h3>
                    <p>Let’s gocùng Trường Mầm Non 11 đưa các bạn nhỏ đến thăm quan nhà sách [...]</p>
                </div>
            </div>
            <div class="project-item">
                <div class="pr-img">
                    <a href=""><img src="images/logo.png" alt="project"></a>
                </div>
                <div class="pr-name">
                    <h3>Bé tham gia PIJAMA PARTY</h3>
                    <p>Pijama Day là một trong những hoạt động mang lại niềm vui cũng như sự [...]</p>
                </div>
            </div>
        </div>
        
    </div>
</section>



<section class="phuhuynh-share background-section" style="background-image: url('images/bg-cmt.png');">
    <div class="ph-share">
        <h2>Trường Mầm Non 11</h2>
        <h1>Góc chia sẻ</h1>
        <div class="share-content">
            <div class="share-item">
                <div class="share-img">
                    <img src="images/ph1.jpg" alt="phu huynh">
                </div>
                <div class="share-contn">
                    <p>
                    Nhờ một người bạn giới thiệu mà gia đình tôi tìm đến trường mầm non 11, cảm ơn trường cùng các cô đã yêu thương và chăm sóc bé nhà mình tận tâm. Bé về nhà kể rất yêu quý các cô và các bạn, bé đi học luôn rất hạnh phúc.
                    </p>
                    <p><span>Trương Tuấn Tú </span>/ PH bé Thiên</p>
                    
                </div>
            </div>

            <div class="share-item">
                <div class="share-img">
                    <img src="images/ph2.jpg" alt="phu huynh">
                </div>
                <div class="share-contn">
                    <p>
                    Biết đến trường mầm non 11 khi được một người bạn giới thiệu, cảm ơn trường và các cô đã tận tình chăm sóc và yêu thương bé nhà mình. Bé về nhà kể rất quý cô và các bạn, bé đi học luôn tràn đầy niềm vui.
                    </p>
                    <p><span>Sa Ngộ Tị </span>/ PH bé Bảy</p>
                </div>
            </div>
            <div class="share-item">
                <div class="share-img">
                    <img src="images/ph3.jpg" alt="phu huynh">
                </div>
                <div class="share-contn">
                    <p>
                    Nhờ một người bạn giới thiệu mà tôi biết đến trường mầm non 11, xin cảm ơn trường cùng các cô đã yêu thương và chăm sóc bé nhà mình rất chu đáo. Bé kể lại rất quý mến các cô và bạn bè, bé đi học mỗi ngày đều rất hào hứng.
                    </p>
                    <p><span>Phan Tấn Trung </span>/ PH bé An</p>
                </div>
            </div>
            <div class="share-item">
                <div class="share-img">
                    <img src="images/ph4.jpg" alt="phu huynh">
                </div>
                <div class="share-contn">
                    <p>
                    Tôi biết đến trường mầm non 11 qua lời giới thiệu của một người bạn, xin gửi lời cảm ơn đến trường và các cô đã yêu thương và hỗ trợ bé nhà mình nhiệt tình. Bé về kể rất quý các cô và bạn, bé luôn vui vẻ khi đến trường.
                    </p>
                    <p><span>Hồng Quang Minh </span>/ PH bé ...</p>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="footer-wrapper" style="background-image: url('images/bg-footer.png');">
    <section class="session-footer">
        <div class="footer">
            <div class="footer-content">
                <div class="footer-item">
                    <a href="index.php"><img src="images/logo.png" alt=""></a>
                    <p>Điều hạnh phúc nhất mỗi ngày là cùng nhìn ngắm những Bạn Nhỏ học tập, vui chơi cùng nhau, và phát triển khoẻ mạnh</p>
                </div>
                <div class="footer-item">
                    <ul>
                        <h2 class="ft-title">Về chúng tôi</h2>
                        <li><a href="">Trang chủ</a></li>
                        <li><a href="./pages/Curriculum.php">Chương trình học</a></li>
                        <li><a href="./pages/menu.php">Thực đơn</a></li>
                        <li><a href="./pages/Admission.php">Tuyển sinh</a></li>
                        <li><a href="./pages/event.php">Tin tức và sự kiện</a></li>
                    </ul>
                    
                </div>
                <div class="footer-item">
                    <ul>
                        <h2 class="ft-title">Liên kết nhanh</h2>
                        <li><a href="">Dự án học tập</a></li>
                        <li><a href="">Mách tip hay</a></li>
                        <li><a href="">Góc chia sẻ của phụ huynh</a></li>
                        <li><a href="">Thời khóa biểu</a></li>
                    </ul>
                </div>
                <div class="footer-item">
                <ul>
                        <h2 class="ft-title">Kết nối với chúng tôi</h2>
                        <li>Hotline <span><?php echo $hotline; ?></span></li>
                        <li>Email: <?php echo $email; ?></li>
                        <li>Để tìm hiểu nhiều thông tin hơn về trường Mầm Non 11 ba mẹ vui lòng kết nối với các kênh truyền thông sau:</li>
                        <div class="icon-contact">
                            <i class="fa-brands fa-facebook icon-footer"></i>
                            <i class="fa-brands fa-instagram icon-footer"></i>
                            <i class="fa-regular fa-envelope icon-footer"></i>
                            <i class="fa-brands fa-twitter icon-footer"></i>
                        </div>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</footer>
<script>
    // Lắng nghe sự kiện cuộn trang
window.onscroll = function() {
    // Kiểm tra xem người dùng đã cuộn xuống đủ 1000px chưa
    if (window.pageYOffset > 500) {
        document.getElementById('fixedHeader').classList.add('show'); // Hiển thị thanh header
    } else {
        document.getElementById('fixedHeader').classList.remove('show'); // Ẩn thanh header
    }
};

</script>
<script src="js/scr.js"></script>
</body>
</html>





