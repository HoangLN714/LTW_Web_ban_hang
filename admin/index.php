<?php
// Bật thông báo lỗi để dễ theo dõi nếu có trục trặc
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Khởi động session và kết nối CSDL
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';
include 'header.php';

// Kiểm tra quyền hạn Admin (Bảo mật)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. TRUY VẤN ĐẾM SỐ LIỆU ĐỂ HIỂN THỊ LÊN CÁC THẺ MÀU
// Đếm tổng số sản phẩm
$res_p = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = ($res_p) ? $res_p->fetch_assoc()['total'] : 0;

// Đếm tổng số khách hàng (loại bỏ tài khoản admin ra khỏi danh sách đếm)
$res_u = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
$total_users = ($res_u) ? $res_u->fetch_assoc()['total'] : 0;

// Đếm tổng số đơn hàng (nếu bạn có bảng orders, nếu chưa có tạm thời để trống hoặc mặc định bằng 0)
$total_orders = 0;
if ($conn->query("SHOW TABLES LIKE 'orders'")->num_rows > 0) {
    $res_o = $conn->query("SELECT COUNT(*) as total FROM orders");
    $total_orders = ($res_o) ? $res_o->fetch_assoc()['total'] : 0;
}

// 2. LẤY DANH SÁCH THÀNH VIÊN HIỂN THỊ LÊN BẢNG
$sql_users = "SELECT * FROM users ORDER BY id DESC";
$result_users = $conn->query($sql_users);
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">📊 Hệ thống Quản trị (Dashboard)</h2>

    <div class="row mb-5">
        
        <div class="col-md-4 mb-4">
            <a href="product-list.php" class="text-decoration-none d-block">
                <div class="card text-white bg-primary shadow-sm border-0 h-100 py-2" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">📦 Tổng Sản phẩm</h5>
                        <h1 class="display-4 fw-bold my-2"><?= $total_products ?></h1>
                        <small class="text-white border-bottom border-light">Xem chi tiết danh sách ➡️</small>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="#danh-sach-thanh-vien" class="text-decoration-none d-block">
                <div class="card text-white bg-success shadow-sm border-0 h-100 py-2" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">👥 Tổng Khách hàng</h5>
                        <h1 class="display-4 fw-bold my-2"><?= $total_users ?></h1>
                        <small class="text-white border-bottom border-light">Xem danh sách phía dưới 👇</small>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="order.php" class="text-decoration-none d-block">
                <div class="card text-dark bg-warning shadow-sm border-0 h-100 py-2" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">🛒 Tổng Đơn hàng</h5>
                        <h1 class="display-4 fw-bold my-2"><?= $total_orders ?></h1>
                        <small class="text-dark border-bottom border-dark">Xem quản lý đơn hàng ➡️</small>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div id="danh-sach-thanh-vien" class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold py-3">
            👥 Danh sách Thành viên Hệ thống
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Quyền hạn</th>
                            <th>Ngày đăng ký (Realtime)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_users && $result_users->num_rows > 0): ?>
                            <?php while ($row = $result_users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td class="fw-bold text-start ps-4"><?= htmlspecialchars($row['username']) ?></td>
                                    <td>
                                        <?php if ($row['role'] === 'admin'): ?>
                                            <span class="badge bg-danger">Quản trị viên</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Khách hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (isset($row['created_at']) && !empty($row['created_at'])) {
                                            echo date('d/m/Y H:i', strtotime($row['created_at']));
                                        } else {
                                            echo '<span class="text-muted">Chưa ghi nhận</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-muted p-4">Hệ thống chưa có thành viên nào đăng ký.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>