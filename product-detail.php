<?php
require_once 'config/db.php';
include 'header.php';
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}
$id = intval($_GET['id']);
$stmt = $conn->prepare(
    "SELECT *
     FROM products
     WHERE id=?"
);
$stmt->bind_param( "i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) { echo "<div class='container mt-5'>
            <div class='alert alert-danger'> Không tìm thấy sản phẩm. </div> </div>";
    include 'footer.php';
    exit();
}
$product = $result->fetch_assoc();
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <img
                src="uploads/<?= htmlspecialchars($product['image']) ?>"
                class="img-fluid rounded shadow"
                alt="<?= htmlspecialchars($product['name']) ?>"
                onerror="this.src='assets/images/no-image.png';">
        </div>
        <div class="col-md-7">
            <h2> <?= htmlspecialchars($product['name']) ?> </h2>
            <h3 class="text-danger my-3">
                <?= number_format($product['price'],0,',','.') ?> đ </h3>
            <p>
                <strong>Mô tả sản phẩm:</strong>
            </p>
            <p> <?= nl2br(htmlspecialchars($product['description'])) ?> </p>
            <a href="cart.php?add=<?= $product['id'] ?>" class="btn btn-success btn-lg"> 🛒 Thêm vào giỏ hàng </a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>