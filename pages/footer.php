<?php 
// Truy vấn SQL để lấy dữ liệu từ bảng contacttt
$sql = "SELECT hotline, email FROM contacttt";
$result11 = $conn->query($sql);

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
?>
<footer class="footer-wrapper" style="background-image: url('../images/bg-footer.png');">
    <section class="session-footer">
        <div class="footer">
            <div class="footer-content">
                <div class="footer-item">
                    <a href="index.php"><img src="../images/logo.png" alt=""></a>
                    <p>Điều hạnh phúc nhất mỗi ngày là cùng nhìn ngắm những Bạn Nhỏ học tập, vui chơi cùng nhau, và phát triển khoẻ mạnh</p>
                </div>
                <div class="footer-item">
                <ul>
                        <h2 class="ft-title">Về chúng tôi</h2>
                        <li><a href="">Trang chủ</a></li>
                        <li><a href="Curriculum.php">Chương trình học</a></li>
                        <li><a href="menu.php">Thực đơn</a></li>
                        <li><a href="Admission.php">Tuyển sinh</a></li>
                        <li><a href="event.php">Tin tức và sự kiện</a></li>
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
                        <li>Để tìm hiểu nhiều thông tin hơn về trường Mầm Non Xanh ba mẹ vui lòng kết nối với các kênh truyền thông sau:</li>
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
// poppup
const openPopup = document.getElementById('openPopup');
    const closePopup = document.getElementById('closePopup');
    const popupOverlay = document.getElementById('popupOverlay');

    openPopup.addEventListener('click', () => {
        popupOverlay.style.display = 'flex';
    });

    closePopup.addEventListener('click', () => {
        popupOverlay.style.display = 'none';
    });

    // Close the popup when clicking outside the form
    popupOverlay.addEventListener('click', (e) => {
        if (e.target === popupOverlay) {
            popupOverlay.style.display = 'none';
        }
    });


</script>
<script src="../js/scr.js"></script>
</body>
</html>