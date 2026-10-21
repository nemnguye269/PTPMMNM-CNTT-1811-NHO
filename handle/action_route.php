<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../config/database.php';

// XỬ LÝ XÓA TUYẾN XE
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM routes WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ../admin.php?msg=deleted');
    exit;
}

// XỬ LÝ SỬA TUYẾN XE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_route'])) {
    $id = intval($_POST['id']);
    $code = $_POST['route_code'];
    $name = $_POST['route_name'];
    $dep = $_POST['departure'];
    $des = $_POST['destination'];
    $time = $_POST['start_time'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE routes SET route_code=?, route_name=?, departure=?, destination=?, start_time=?, price=? WHERE id=?");
    $stmt->execute([$code, $name, $dep, $des, $time, $price, $id]);
    header('Location: ../admin.php?msg=updated');
    exit;
}
?>
