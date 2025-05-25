<?php
require_once '../../system/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM classes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);

    header('Location: list_class.php');
    exit;
}
?>