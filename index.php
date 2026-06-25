<?php
require_once 'config/db.php';
include 'header.php';
$search = "";
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = trim($_GET['search']);
    $stmt = $conn->prepare(
        "SELECT *
         FROM products
         WHERE name LIKE ?
         ORDER BY id DESC
         LIMIT 8"
    );
    $keyword = "%".$search."%";
    $stmt->bind_param(
        "s",
        $keyword
    );
    $stmt->execute();
    $result = $stmt->get_result();
}else{
    $result = $conn->query(
        "SELECT *
         FROM products
         ORDER BY id DESC
         LIMIT 8"
    );
}
?>
<div class="container mt-4">
    <div class="bg-primary text-white text-center p-5 rounded shadow">
        <h1> Chào mừng đến với Shop Của Tôi </h1>
        <p> Website bán hàng PHP + MySQL </p>
    </div>
</div>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2> Sản phẩm nổi bật </h2>
        <form method="GET" action="index.php" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm sản phẩm..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary"> Tìm </button>
        </form>
    </div>
    <div class="row">
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="uploads/<?= $row['image'] ?>" class="card-img-top" style="height:250px;object-fit:cover;" onerror="this.src='assets/images/no-image.png';">
                        <div class="card-body text-center">
                            <h5 class="card-title"> <?= htmlspecialchars($row['name']) ?> </h5>
                            <p class="text-danger fw-bold"> <?= number_format($row['price'],0,',','.') ?> VNĐ </p>
                            <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary"> Xem chi tiết </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning"> Không tìm thấy sản phẩm. </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>