<?php
// 1. Luôn bật session lên đầu trang để lưu giỏ hàng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';
include 'header.php';

// Khởi tạo giỏ hàng nếu chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// 2. XỬ LÝ HÀNH ĐỘNG: THÊM VÀO GIỎ HÀNG (Dành cho cả nút bấm cũ lẫn Form số lượng mới)
if ((isset($_GET['action']) && $_GET['action'] == 'add_multi' && isset($_POST['product_id'])) || isset($_GET['add'])) {
    
    // Nếu đi từ Form (Có số lượng tùy chỉnh)
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    } 
    // Nếu đi từ link cũ hoặc link xem chi tiết (Mặc định thêm 1 sản phẩm)
    else {
        $product_id = intval($_GET['add']);
        $quantity = 1;
    }

    if ($quantity < 1) $quantity = 1;

    // Tiến hành cộng dồn số lượng vào Session
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Chuyển hướng về lại trang giỏ hàng để tránh lặp dữ liệu khi F5
    header("Location: cart.php");
    exit();
}

// 3. XỬ LÝ HÀNH ĐỘNG: CẬP NHẬT SỐ LƯỢNG KHI KHÁCH SỬA TRONG BẢNG GIỎ HÀNG
if (isset($_POST['update_cart_action'])) {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]); // Số lượng <= 0 thì xóa khỏi giỏ
            } else {
                $_SESSION['cart'][$id] = $qty; // Cập nhật số lượng mới chỉnh
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// 4. XỬ LÝ HÀNH ĐỘNG: XÓA SẢN PHẨM KHỎI GIỎ HÀNG
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">🛒 Giỏ hàng của bạn</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="cart.php">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá bán</th>
                                    <th style="width: 150px;">Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_all = 0;
                                $ids = implode(',', array_keys($_SESSION['cart']));
                                $sql = "SELECT * FROM products WHERE id IN ($ids)";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['id'];
                                    $qty = $_SESSION['cart'][$id];
                                    $subtotal = $row['price'] * $qty;
                                    $total_all += $subtotal;
                                    ?>
                                    <tr>
                                        <td><img src="uploads/<?= $row['image'] ?>" width="50" class="rounded border" alt=""></td>
                                        <td class="text-start fw-bold"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="text-danger"><?= number_format($row['price'], 0, ',', '.') ?> đ</td>
                                        <td>
                                            <input type="number" name="qty[<?= $id ?>]" class="form-control text-center mx-auto" value="<?= $qty ?>" min="1" style="width: 80px;">
                                        </td>
                                        <td class="text-danger fw-bold"><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                                        <td>
                                            <a href="cart.php?action=delete&id=<?= $id ?>" class="btn btn-sm btn-outline-danger">Xóa</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold fs-5">Tổng tiền thanh toán:</td>
                                    <td colspan="2" class="text-danger fw-bold fs-5"><?= number_format($total_all, 0, ',', '.') ?> đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="products.php" class="btn btn-outline-secondary me-2">⬅️ Tiếp tục mua sắm</a>
                    <button type="submit" name="update_cart_action" class="btn btn-warning fw-bold text-dark">🔄 Cập nhật giỏ hàng</button>
                </div>
                <a href="checkout.php" class="btn btn-success btn-lg fw-bold px-4">Tiến hành đặt hàng 🚀</a>
            </div>
        </form>

    <?php else: ?>
        <div class="text-center card shadow-sm p-5 border-0">
            <p class="text-muted fs-5 mb-3">Giỏ hàng của bạn đang trống rỗng.</p>
            <a href="products.php" class="btn btn-primary px-4 fw-bold">Mua sắm ngay nào!</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>