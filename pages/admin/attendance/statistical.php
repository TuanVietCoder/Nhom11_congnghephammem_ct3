<?php 
require_once '../../system/connect.php'; 
// Lấy ngày điểm danh từ URL (nếu có) hoặc dùng ngày hôm nay mặc định
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Lấy ngày hôm nay nếu không có ngày nào được chọn

// Truy vấn dữ liệu điểm danh từ cơ sở dữ liệu
$sql = "
    SELECT
        a.date AS 'Ngày',
        c.ClassName AS 'Lớp',
        COUNT(CASE WHEN a.status = 1 THEN 1 END) AS 'Số học sinh đi học',
        COUNT(CASE WHEN a.status = 0 THEN 1 END) AS 'Số học sinh nghỉ',
        COUNT(a.studentid) AS 'Tổng số học sinh'
    FROM
        attendance a
    JOIN students s ON a.studentid = s.StudentID
    JOIN classes c ON s.ClassID = c.id  -- Đảm bảo rằng đây là trường kết nối chính xác
    WHERE
        a.date = ?
    GROUP BY
        a.date, c.ClassName
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Điểm Danh</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Thống Kê Điểm Danh</h1>

<!-- Form chọn ngày -->
<form method="GET" action="">
    <label for="date">Chọn ngày:</label>
    <input type="date" id="date" name="date" value="<?php echo $date; ?>">
    <button type="submit">Cập nhật</button>
</form>

<!-- Bảng thống kê -->
<table border="1">
    <thead>
        <tr>
            <th>Ngày</th>
            <th>Lớp</th>
            <th>Số học sinh đi học</th>
            <th>Số học sinh nghỉ</th>
            <th>Tổng số học sinh</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Hiển thị dữ liệu
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Ngày'] . "</td>";
            echo "<td>" . $row['Lớp'] . "</td>";
            echo "<td>" . $row['Số học sinh đi học'] . "</td>";
            echo "<td>" . $row['Số học sinh nghỉ'] . "</td>";
            echo "<td>" . $row['Tổng số học sinh'] . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Đóng kết nối
$stmt->close();
$conn->close();
?>

</body>
</html>
