<?php
session_start();
require_once __DIR__ . '/config/database.php';

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM routes WHERE route_code LIKE :s OR route_name LIKE :s OR start_station LIKE :s OR end_station LIKE :s");
    $stmt->execute(['s' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM routes ORDER BY id DESC");
}
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tra cứu Lịch trình Giao thông Công cộng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🚌 Tra Cứu Xe Buýt</a>
        <div>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="admin.php" class="btn btn-warning me-2">Trang quản trị</a>
                <a href="handle/logout.php" class="btn btn-outline-light">Đăng xuất</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-light">Đăng nhập Quản lý</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h2 class="text-center mb-4">Tra cứu tuyến xe & Lịch trình chạy</h2>
    
    <!-- Thanh tìm kiếm -->
    <form method="GET" action="index.php" class="row g-2 mb-4 justify-content::center">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Nhập mã tuyến, tên tuyến, điểm đi hoặc điểm đến..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
    </form>

    <!-- Danh sách tuyến xe -->
    <div class="row">
        <?php if (count($routes) > 0): ?>
            <?php foreach ($routes as $route): ?>
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary font-weight-bold">
                                <?= htmlspecialchars($route['route_code']) ?>: <?= htmlspecialchars($route['route_name']) ?>
                            </h5>
                            <p class="card-text mb-1"><strong>Lộ trình:</strong> <?= htmlspecialchars($route['start_station']) ?> ➔ <?= htmlspecialchars($route['end_station']) ?></p>
                            <p class="card-text mb-1"><strong>Giờ hoạt động:</strong> <?= $route['start_time'] ?> - <?= $route['end_time'] ?></p>
                            <p class="card-text mb-1"><strong>Tần suất chuyến:</strong> <?= $route['interval_minutes'] ?> phút/chuyến</p>
                            <p class="card-text text-danger fw-bold">Giá vé: <?= number_format($route['ticket_price']) ?> VNĐ</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                <p>Không tìm thấy tuyến xe nào phù hợp!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>