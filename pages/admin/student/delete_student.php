<?php
require_once '../../system/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);

    header('Location: list_student.php');
    exit;
}
?>