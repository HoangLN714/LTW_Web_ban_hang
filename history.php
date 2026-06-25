<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container mt-5 text-center'>
            <div class='alert alert-warning'>
                <h3>🔒 Bạn chưa đăng nhập!</h3>
                <a href='login.php'
                   class='btn btn-primary mt-3'>
                   Đăng nhập ngay
                </a>
            </div>
          </div>";
    include 'footer.php';
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare(
    "SELECT *
     FROM orders
     WHERE user_id = ?
     ORDER BY created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="container mt-4 mb-5">
    <h2 class="mb-4"> 📜 Lịch sử mua hàng </h2>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Ngày Đặt</th>
                        <th>Tổng Tiền</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong> #<?= $row['id'] ?> </strong>
                                </td>
                                <td> <?= date( 'd/m/Y H:i', strtotime($row['created_at']) ) ?> </td>
                                <td class="text-danger fw-bold"> <?= number_format( $row['total_money'], 0, ',', '.' ) ?> đ
                                </td>
                                <td> <?= $row['payment_method'] ?> </td>
                                <td>
                                    <?php
                                    switch ($row['status']) {
                                        case 'Pending':
                                            echo '<span class="badge bg-warning text-dark">Chờ duyệt</span>';
                                            break;
                                        case 'Processing':
                                            echo '<span class="badge bg-info">Đang xử lý</span>';
                                            break;
                                        case 'Shipping':
                                            echo '<span class="badge bg-primary">Đang giao</span>';
                                            break;
                                        case 'Completed': 
                                            echo '<span class="badge bg-success">Hoàn thành</span>';
                                            break;
                                        default: 
                                            echo '<span class="badge bg-danger">Đã hủy</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5"> Bạn chưa có đơn hàng nào. </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>