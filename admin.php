<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/config/database.php';

// Lấy danh sách toàn bộ tuyến xe
$stmt = $pdo->query("SELECT * FROM routes ORDER BY id DESC");
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Lịch trình Xe Buýt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin.php">Quản lý Lịch trình Xe</a>
        <div>
            <span class="text-white me-3">Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username'] ?? 'Admin') ?></span>
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Xem Trang chủ</a>
            <a href="handle/logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Thêm Tuyến Xe -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white font-weight-bold">Thêm tuyến xe mới</div>
        <div class="card-body">
            <form action="handle/add_route.php" method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Mã tuyến (VD: Bus 09)</label>
                    <input type="text" name="route_code" class="form-control" placeholder="Bus 09" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Tên tuyến</label>
                    <input type="text" name="route_name" class="form-control" placeholder="Tuyến Giáp Bát - Nhổn" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giá vé (VNĐ)</label>
                    <input type="number" name="ticket_price" class="form-control" value="7000" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bến đầu</label>
                    <input type="text" name="start_station" class="form-control" placeholder="Bến xe Giáp Bát" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bến cuối</label>
                    <input type="text" name="end_station" class="form-control" placeholder="Nhổn" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giờ bắt đầu</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giờ kết thúc</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tần suất (phút/chuyến)</label>
                    <input type="number" name="interval_minutes" class="form-control" value="10" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Lưu tuyến xe</button>
                </div>
            </form>
        </div>
    </div>

    <h4>Danh sách các tuyến xe hiện có</h4>
    <table class="table table-bordered table-striped mt-3 align-middle">
        <thead class="table-dark">
            <tr>
                <th>Mã</th>
                <th>Tên tuyến</th>
                <th>Hành trình</th>
                <th>Giờ chạy</th>
                <th>Tần suất</th>
                <th>Giá vé</th>
                <th class="text-center" style="width: 150px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($routes) > 0): ?>
                <?php foreach ($routes as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['route_code']) ?></strong></td>
                        <td><?= htmlspecialchars($r['route_name']) ?></td>
                        <td><?= htmlspecialchars($r['start_station']) ?> ➔ <?= htmlspecialchars($r['end_station']) ?></td>
                        <td><?= htmlspecialchars($r['start_time']) ?> - <?= htmlspecialchars($r['end_time']) ?></td>
                        <td><?= htmlspecialchars($r['interval_minutes']) ?> phút</td>
                        <td><?= number_format($r['ticket_price']) ?> đ</td>
                        <td class="text-center">
                            <!-- Nút Mở Modal Sửa -->
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $r['id'] ?>">Sửa</button>
                            <!-- Nút Xóa -->
                            <a href="handle/action_route.php?action=delete&id=<?= $r['id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa tuyến xe [<?= htmlspecialchars($r['route_code']) ?>] này?');">Xóa</a>
                        </td>
                    </tr>

                    <!-- MODAL SỬA TUYẾN XE -->
                    <div class="modal fade" id="editModal<?= $r['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="handle/action_route.php" method="POST">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">Sửa tuyến xe: <?= htmlspecialchars($r['route_code']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">

                                        <div class="col-md-4">
                                            <label class="form-label">Mã tuyến</label>
                                            <input type="text" name="route_code" class="form-control" value="<?= htmlspecialchars($r['route_code']) ?>" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Tên tuyến</label>
                                            <input type="text" name="route_name" class="form-control" value="<?= htmlspecialchars($r['route_name']) ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Giá vé (VNĐ)</label>
                                            <input type="number" name="ticket_price" class="form-control" value="<?= htmlspecialchars($r['ticket_price']) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bến đầu</label>
                                            <input type="text" name="start_station" class="form-control" value="<?= htmlspecialchars($r['start_station']) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bến cuối</label>
                                            <input type="text" name="end_station" class="form-control" value="<?= htmlspecialchars($r['end_station']) ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giờ bắt đầu</label>
                                            <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($r['start_time']) ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giờ kết thúc</label>
                                            <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($r['end_time']) ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tần suất (phút)</label>
                                            <input type="number" name="interval_minutes" class="form-control" value="<?= htmlspecialchars($r['interval_minutes']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Chưa có tuyến xe nào trong hệ thống.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
