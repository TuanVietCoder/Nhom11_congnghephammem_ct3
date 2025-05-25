<?php
    include "connect.php";

    function executeSql($query, $params, $param_types) {
        global $conn; // Sử dụng kết nối từ bên ngoài
        
        // Chuẩn bị truy vấn
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }
        
        // Gán tham số nếu có
        if (!empty($params)) {
            $stmt->bind_param($param_types, ...$params);
        }
        
        // Thực thi truy vấn
        $stmt->execute();
        
        // Lấy kết quả
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        
        // Đóng truy vấn
        $stmt->close();
        
        return $data;
    }

?>