<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("INSERT INTO routes (route_code, route_name, start_station, end_station, start_time, end_time, ticket_price, interval_minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['route_code'],
        $_POST['route_name'],
        $_POST['start_station'],
        $_POST['end_station'],
        $_POST['start_time'],
        $_POST['end_time'],
        $_POST['ticket_price'],
        $_POST['interval_minutes']
    ]);
    $_SESSION['success'] = "Thêm tuyến xe thành công!";
    header('Location: ../admin.php');
    exit;
}