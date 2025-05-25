<?php
require_once '../../system/connect.php';
session_start();

// Kiểm tra người dùng đã đăng nhập và có quyền
if (!isset($_SESSION['username']) || intval($_SESSION['role']) !== 1) {
    header("Location: ../login.php");
    exit();
}

// Header cho file CSV
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=danh_sach_hoc_sinh.csv");
header("Pragma: no-cache");
header("Expires: 0");

// Mở file output để ghi dữ liệu
$output = fopen("php://output", "w");

// Thêm BOM để Excel nhận diện file là UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8

// Ghi dòng tiêu đề
fputcsv($output, ["Mã học sinh", "Họ và tên", "Lớp", "Ngày sinh", "Địa chỉ", "Phụ huynh", "SDT phụ huynh"], ",");

// Truy vấn danh sách học sinh
$query = "SELECT s.StudentID, s.FullName, c.ClassName, s.DateOfBirth, s.Address, s.ParentName, s.ParentPhone 
          FROM students s 
          LEFT JOIN classes c ON s.ClassID = c.id 
          ORDER BY s.StudentID ASC";
$result = $conn->query($query);

// Ghi dữ liệu từng dòng vào file CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['StudentID'],
        $row['FullName'],
        $row['ClassName'],
        $row['DateOfBirth'] ? date('d/m/Y', strtotime($row['DateOfBirth'])) : '',
        $row['Address'],
        $row['ParentName'],
        $row['ParentPhone']
    ], ",");
}

// Đóng file output
fclose($output);
exit();
?>
