<?php
include 'connect.php';

if (isset($_POST['btn-contact'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $content = $_POST['content'];

    // Truy vấn SQL để lưu vào bảng `contact`
    $sql = "INSERT INTO contact (name, phone, email, content) VALUES ('$name', '$phone', '$email', '$content')";

    if (mysqli_query($conn, $sql)) {
        header('location:index.php');
        
        
    } else {
        echo "<script>alert('Có lỗi xảy ra. Vui lòng thử lại sau!');</script>";
    }
}
?>
