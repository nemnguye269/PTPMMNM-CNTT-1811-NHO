<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra tài khoản và mật khẩu (hỗ trợ cả mật khẩu thường lẫn password_hash)
    if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
        $_SESSION['user'] = $user;
        header('Location: ../admin.php'); // Hoặc admin.php tùy cấu trúc thư mục của bạn
        exit;
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 text-primary">Đăng Nhập Quản Trị</h3>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" placeholder="admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="123456" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Đăng nhập</button>
                    <a href="../index.php" class="btn btn-outline-secondary w-100">Quay lại trang chủ</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
