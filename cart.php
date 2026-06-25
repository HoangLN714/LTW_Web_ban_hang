<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';
include 'header.php';
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
/* THÊM SẢN PHẨM */
if (
    (isset($_GET['action']) && $_GET['action'] == 'add_multi')
    ||
    isset($_GET['add'])
) {
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
    } else {
        $product_id = intval($_GET['add']);
        $quantity = 1;
    }
    if ($quantity < 1) {
        $quantity = 1;
    }
    $stmt = $conn->prepare(
        "SELECT id, quantity
         FROM products
         WHERE id=?"
    );
    $stmt->bind_param( "i", $product_id );
    $stmt->execute();
    $product = $stmt ->get_result() ->fetch_assoc();
    if ($product) {
        if ( isset($_SESSION['cart'][$product_id])
        ) { $_SESSION['cart'][$product_id] += $quantity;
        } else { $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit();
}
/* CẬP NHẬT GIỎ HÀNG */
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $id = intval($id);
        $qty = intval($qty);
        if ($qty <= 0) { unset( $_SESSION['cart'][$id] );
        } else { $_SESSION['cart'][$id] = $qty;}
    }
    header("Location: cart.php");
    exit();
}
/* XÓA SẢN PHẨM */
if (
    isset($_GET['action']) && $_GET['action'] == 'delete'
) {
    $id = intval($_GET['id']);
    unset( $_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}
?>
<div class="container mt-4">
<h2 class="mb-4"> 🛒 Giỏ hàng của bạn </h2>
<?php if (!empty($_SESSION['cart'])): ?>
    <?php
    $ids = implode( ",", array_map( 'intval', array_keys($_SESSION['cart']) ) );
    $sql =
        "SELECT *
         FROM products
         WHERE id IN ($ids)";
    $result = $conn->query($sql);
    $total = 0;
    ?>
    <form method="POST">
        <table
            class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $id = $row['id'];
                    $qty = $_SESSION['cart'][$id];
                    $subtotal = $row['price'] * $qty;
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" width="80" class="img-thumbnail" onerror="this.src='assets/images/no-image.png';">
                        </td>
                        <td>
                            <?= htmlspecialchars($row['name']) ?>
                        </td>
                        <td class="text-danger fw-bold"> <?= number_format($row['price'],0,',','.') ?> đ </td>
                        <td>
                            <input
                                type="number"
                                name="qty[<?= $id ?>]"
                                value="<?= $qty ?>"
                                min="1"
                                class="form-control text-center">
                        </td>
                        <td> <?= number_format($subtotal,0,',','.') ?> đ </td>
                        <td>
                            <a href="cart.php?action=delete&id=<?= $id ?>" class="btn btn-danger btn-sm"> Xóa </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="4" class="text-end fw-bold"> Tổng cộng </td>
                    <td colspan="2" class="text-danger fw-bold fs-5"> <?= number_format($total,0,',','.') ?> đ </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <a href="products.php" class="btn btn-secondary"> ← Tiếp tục mua hàng </a>
            <div>
                <button type="submit" name="update_cart" class="btn btn-warning"> Cập nhật giỏ hàng </button>
                <a href="checkout.php" class="btn btn-success"> Thanh toán </a>
            </div>
        </div>
    </form>
<?php else: ?>
    <div class="alert alert-info"> Giỏ hàng đang trống. </div>
    <a href="products.php" class="btn btn-primary"> Xem sản phẩm </a>
<?php endif; ?>
</div>
<?php include 'footer.php'; ?>
