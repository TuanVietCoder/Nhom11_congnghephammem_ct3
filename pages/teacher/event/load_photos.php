<?php
require_once '../../system/connect.php';

$event_id = $_GET['event_id'] ?? null;
if (!$event_id) {
    echo '<p class="text-danger">Không tìm thấy ID sự kiện.</p>';
    exit;
}

$sql = "SELECT photo_name FROM eventphoto WHERE eventid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$upload_dir = '../../../images/uploads/'; 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-3"><img src="' . $upload_dir  . htmlspecialchars($row['photo_name']) . '" class="img-fluid"></div>';
    }
} else {
    echo '<p class="text-warning">Chưa có ảnh nào.</p>';
}

?>
