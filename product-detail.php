<?php
require_once 'config/db.php';
include 'header.php';

// Kiểm tra xem trên URL có truyền ID sản phẩm không
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Truy vấn lấy thông tin sản phẩm theo ID
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<div class='container mt-5'>Không tìm thấy sản phẩm.</div>";
        include 'footer.php';
        exit();
    }
} else {
    // Nếu không có ID, đẩy về trang chủ
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <img src="uploads/<?= $product['image'] ?>" class="img-fluid rounded" alt="<?= $product['name'] ?>">
        </div>
        
        <div class="col-md-7">
            <h2><?= $product['name'] ?></h2>
            <h3 class="text-danger fw-bold my-3"><?= number_format($product['price'], 0, ',', '.') ?> đ</h3>
            
            <p class="fs-5"><strong>Mô tả sản phẩm:</strong></p>
            <p><?= nl2br($product['description']) ?></p>
            
            <a href="cart.php?add=<?= $product['id'] ?>" class="btn btn-success btn-lg mt-4">Thêm vào giỏ hàng</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>