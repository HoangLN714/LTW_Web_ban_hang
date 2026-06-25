<?php
require_once 'config/db.php';
include 'header.php';
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare(
        "SELECT *
         FROM products
         WHERE name LIKE ?
         ORDER BY id DESC"
    );
    $keyword = "%" . $search . "%";
    $stmt->bind_param(
        "s",
        $keyword
    );
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query(
        "SELECT *
         FROM products
         ORDER BY id DESC"
    );
}
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2>Tất cả sản phẩm</h2>
        <form method="GET" action="products.php" class="d-flex" style="max-width:400px;width:100%;">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên sản phẩm..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary"> Tìm </button>
        </form>
    </div>
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <form method="POST" action="cart.php?action=add_multi" class="h-100">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <div class="card h-100 shadow-sm">
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:220px;padding:15px;">
                                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($row['name']) ?>" style="max-height:100%;object-fit:contain;" onerror="this.src='assets/images/no-image.png';">
                            </div>
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title"> <?= htmlspecialchars($row['name']) ?> </h5>
                                <p class="text-danger fw-bold fs-5"> <?= number_format($row['price'],0,',','.') ?> đ </p>
                                <div class="mt-auto"> <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary w-100 mb-2"> Xem chi tiết </a>
                                    <div class="input-group mb-2 mx-auto" style="max-width:130px;">
                                        <span class="input-group-text"> SL </span>
                                        <input type="number" name="quantity" class="form-control text-center" value="1" min="1" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100"> 🛒 Thêm vào giỏ </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning"> Không tìm thấy sản phẩm phù hợp. </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>