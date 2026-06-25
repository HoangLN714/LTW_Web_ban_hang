<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/db.php';
include 'header.php';
// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='alert alert-warning text-center mt-4'>
            Giỏ hàng của bạn đang trống!
            <a href='index.php'>Mua sắm ngay</a>
          </div>";
    include 'footer.php';
    exit();
}
// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger text-center mt-4'>
            Bạn cần <a href='login.php'>đăng nhập</a>
            để tiến hành đặt hàng!
          </div>";
    include 'footer.php';
    exit();
}
$user_id = $_SESSION['user_id'];
$message = "";
$total_all = 0;
$cart_items = [];
$ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM products WHERE id IN ($ids)";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $qty = $_SESSION['cart'][$id];
    $total_all += $row['price'] * $qty;
    $cart_items[] = $row;
}
// Xử lý đặt hàng
if (isset($_POST['place_order'])) {
    $payment_method = $_POST['payment_method'];
    $status = "Pending";
    $stmt = $conn->prepare(
        "INSERT INTO orders
        (user_id, total_money, payment_method, status)
        VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "idss",
        $user_id,
        $total_all,
        $payment_method,
        $status
    );
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        unset($_SESSION['cart']);
        $message = "
        <div class='alert alert-success text-center p-4 shadow-sm'>
            <h3>🎉 Đặt hàng thành công!</h3>
            <p>Mã đơn hàng của bạn là:
                <strong>#$order_id</strong>
            </p>
            <p class='mb-0'>
                Bạn có thể kiểm tra trạng thái tại
                <a href='history.php'
                   class='fw-bold text-success'>
                   Lịch sử đơn hàng
                </a>
            </p>
        </div>";
    } else {
        $message = "
        <div class='alert alert-danger'>
            Lỗi hệ thống: {$conn->error}
        </div>";
    }
}
?>
<div class="container mt-4">
    <?php if (!empty($message)): ?>
        <?= $message ?>
    <?php else: ?>
        <h2 class="mb-4">📋 Xác nhận thanh toán</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"> Sản phẩm đã chọn </h5>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($cart_items as $item): ?>
                                <?php $qty = $_SESSION['cart'][$item['id']]; ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <strong> <?= $item['name'] ?> </strong>
                                        <small class="d-block text-muted"> Số lượng: <?= $qty ?> </small>
                                    </div>
                                    <span class="text-danger fw-bold"> <?= number_format( $item['price'] * $qty, 0, ',', '.' ) ?> đ </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="border-top pt-3 d-flex justify-content-between">
                            <strong>Tổng tiền:</strong>
                            <span class="text-danger fw-bold fs-4"><?= number_format( $total_all, 0, ',', '.') ?> đ </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"> Thông tin thanh toán </h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="fw-bold"> Phương thức thanh toán </label>
                                <div class="form-check mt-2">
                                    <input type="radio" class="form-check-input" name="payment_method" value="COD" checked >
                                    <label class="form-check-label"> 💵 Thanh toán khi nhận hàng </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="payment_method" value="Chuyển khoản" >
                                    <label class="form-check-label"> 🏦 Chuyển khoản ngân hàng </label>
                                </div>
                            </div>
                            <button type="submit" name="place_order" class="btn btn-danger w-100 btn-lg" > 🚀 XÁC NHẬN ĐẶT HÀNG </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>