<?php
// 1. BẮT BUỘC: Luôn đặt session_start() ở ngay dòng đầu tiên để không bị mất thông tin đăng nhập
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';
include 'header.php';

// 2. KIỂM TRA ĐĂNG NHẬP: Nếu không có user_id trong session, bắt buộc chuyển hướng
if (!isset($_SESSION['user_id'])) {
    // Để chắc chắn không bị lỗi chuyển hướng lặp, ta in thông báo kèm nút bấm tự động
    echo "<div class='container mt-5 text-center'>";
    echo "<div class='alert alert-warning p-4 shadow-sm'>";
    echo "<h3>🔒 Phiên đăng nhập đã hết hạn hoặc chưa đăng nhập!</h3>";
    echo "<p class='text-muted'>Vui lòng đăng nhập lại để xem lịch sử mua hàng của bạn.</p>";
    echo "<a href='login.php' class='btn btn-primary mt-2 fw-bold px-4'>ĐĂNG NHẬP NGAY</a>";
    echo "</div>";
    echo "</div>";
    include 'footer.php';
    exit();
}

$user_id = $_SESSION['user_id'];

// 3. TRUY VẤN LẤY ĐƠN HÀNG CỦA RIÊNG USER NÀY
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">📜 Lịch sử mua hàng của bạn</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Phương Thức TT</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?= $row['id'] ?></strong></td>
                                    <td><?= isset($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : date('d/m/Y H:i') ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($row['total_money'], 0, ',', '.') ?> đ</td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['payment_method']) ?></span></td>
                                    <td>
                                        <?php 
                                            if($row['status'] == 'Pending') echo '<span class="badge bg-warning text-dark p-2">Chờ duyệt</span>';
                                            elseif($row['status'] == 'Shipping') echo '<span class="badge bg-primary p-2">Đang giao</span>';
                                            elseif($row['status'] == 'Completed') echo '<span class="badge bg-success p-2">Thành công</span>';
                                            else echo '<span class="badge bg-danger p-2">Đã hủy</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-muted p-5">
                                    <p class="mb-2">Bạn chưa đặt đơn hàng nào.</p>
                                    <a href="index.php" class="btn btn-sm btn-outline-primary">Mua sắm ngay</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>