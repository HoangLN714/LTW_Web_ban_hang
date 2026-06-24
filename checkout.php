<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/db.php';
include 'header.php';

// Bảo mật: Nếu giỏ hàng trống thì không cho vào trang này
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='alert alert-warning text-center mt-4'>Giỏ hàng của bạn đang trống! <a href='index.php'>Mua sắm ngay</a></div>";
    include 'footer.php';
    exit();
}

// Yêu cầu bắt buộc đăng nhập để đặt hàng (để lấy user_id)
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger text-center mt-4'>Bạn cần <a href='login.php'>đăng nhập</a> để tiến hành đặt hàng!</div>";
    include 'footer.php';
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Tính tổng tiền giỏ hàng trước
$total_all = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$sql_cart = "SELECT * FROM products WHERE id IN ($ids)";
$res_cart = $conn->query($sql_cart);
$cart_items = [];

while ($row = $res_cart->fetch_assoc()) {
    $id = $row['id'];
    $qty = $_SESSION['cart'][$id];
    $subtotal = $row['price'] * $qty;
    $total_all += $subtotal;
    $cart_items[] = $row; // Lưu lại để xíu dùng tiếp
}

// XỬ LÝ KHI KHÁCH BẤM NÚT "XÁC NHẬN ĐẶT HÀNG"
if (isset($_POST['place_order'])) {
    $payment_method = $_POST['payment_method'];
    $status = "Pending"; // Trạng thái mặc định: Chờ duyệt
    
    // 1. Chèn thông tin vào bảng đơn hàng (orders)
    $sql_order = "INSERT INTO orders (user_id, total_money, payment_method, status) 
                  VALUES ($user_id, $total_all, '$payment_method', '$status')";
    
    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id; // Lấy ra mã đơn hàng vừa tự động tạo sinh
        
        // 2. [Nâng cao nếu có bảng order_details] Lưu chi tiết từng sản phẩm vào đây
        // Hiện tại hệ thống cơ bản chỉ cần lưu tổng đơn thành công là CSDL của bạn đã ghi nhận.

        // 3. Xóa sạch giỏ hàng sau khi đặt thành công
        unset($_SESSION['cart']);
        
        $message = "
            <div class='alert alert-success text-center p-4 shadow-sm'>
                <h3>🎉 Đặt hàng thành công!</h3>
                <p>Mã đơn hàng của bạn là: <strong>#$order_id</strong></p>
                <p class='mb-0'>Bạn có thể kiểm tra trạng thái tại <a href='history.php' class='fw-bold text-success'>Lịch sử đơn hàng</a>.</p>
            </div>";
    } else {
        $message = "<div class='alert alert-danger'>Lỗi hệ thống: " . $conn->error . "</div>";
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
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-3 fw-bold">Sản phẩm đã chọn</h5>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($cart_items as $item): 
                                $qty = $_SESSION['cart'][$item['id']];
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="fw-bold"><?= $item['name'] ?></span>
                                        <small class="text-muted d-block">Số lượng: <?= $qty ?></small>
                                    </div>
                                    <span class="text-danger fw-bold"><?= number_format($item['price'] * $qty, 0, ',', '.') ?> đ</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="fs-5 fw-bold">Tổng tiền cần trả:</span>
                            <span class="fs-4 fw-bold text-danger"><?= number_format($total_all, 0, ',', '.') ?> đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-3 fw-bold">Thông tin thanh toán</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phương thức thanh toán</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                    <label class="form-check-label" for="cod">
                                        💵 Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank" value="Chuyển khoản">
                                    <label class="form-check-label" for="bank">
                                        🏦 Chuyển khoản ngân hàng qua QR-Code
                                    </label>
                                </div>
                            </div>

                            <hr>
                            <p class="text-muted small">Bằng việc bấm nút đặt hàng, bạn đồng ý mua các sản phẩm được liệt kê ở bên cạnh.</p>
                            
                            <button type="submit" name="place_order" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow-sm">
                                🚀 XÁC NHẬN ĐẶT HÀNG NGAY
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>