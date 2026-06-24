<?php
// BẮT BUỘC: Đặt ở dòng đầu tiên để hệ thống nhận diện giỏ hàng và trạng thái đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS bổ sung để đảm bảo chữ menu luôn sáng, không bị ảnh hưởng bởi trình duyệt */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="index.php">Shop Của Tôi</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="products.php">Sản phẩm</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="cart.php">
                            🛒 Giỏ hàng
                            <?php
                            // Đếm tổng số lượng sản phẩm trong giỏ
                            $cart_count = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $qty) {
                                    $cart_count += $qty;
                                }
                            }
                            if ($cart_count > 0) {
                                echo "<span class='badge bg-danger ms-1'>$cart_count</span>";
                            }
                            ?>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-info fw-bold" href="admin/index.php">⚙️ Quản trị</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="profile.php">Chào, <strong class="text-white"><?= htmlspecialchars($_SESSION['username']) ?></strong></a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="history.php">Lịch sử đơn hàng</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-outline-light text-white px-3 ms-2" href="logout.php" style="border-radius: 20px;">Đăng xuất</a>
                        </li>
                        
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-primary text-white px-3 ms-2" href="register.php" style="border-radius: 20px;">Đăng ký</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>