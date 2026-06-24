<?php
require_once 'config/db.php';
include 'header.php';

// Xử lý từ khóa tìm kiếm nếu người dùng có nhập
$search = "";
$sql = "SELECT * FROM products";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    // Tìm kiếm theo tên sản phẩm có chứa từ khóa
    $sql .= " WHERE name LIKE '%$search%'";
}

$sql .= " ORDER BY id DESC"; // Sắp xếp sản phẩm mới nhất lên đầu
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2>Tất cả Sản phẩm</h2>
        
        <form method="GET" action="products.php" class="d-flex" style="max-width: 400px; width: 100%;">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên sản phẩm..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-primary fw-bold">Tìm</button>
        </form>
    </div>

    <div class="row">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <form method="POST" action="cart.php?action=add_multi" class="h-100">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        
                        <div class="card h-100 shadow-sm border-0">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-top" style="height: 220px; width: 100%; overflow: hidden; padding: 15px;">
                                <img src="uploads/<?= $row['image'] ?>" class="img-fluid" alt="<?= htmlspecialchars($row['name']) ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
                            
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title fw-bold text-dark fs-6" style="min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($row['name']) ?>
                                </h5>
                                <p class="text-danger fw-bold fs-5 mb-3"><?= number_format($row['price'], 0, ',', '.') ?> đ</p>
                                
                                <div class="mt-auto">
                                    <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary w-100 mb-2">Xem chi tiết</a>
                                    
                                    <div class="input-group mb-2 mx-auto" style="max-width: 130px;">
                                        <span class="input-group-text bg-light text-muted py-1 px-2" style="font-size: 0.8rem;">SL:</span>
                                        <input type="number" name="quantity" class="form-control text-center py-1 px-2" value="1" min="1" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold">🛒 Thêm vào giỏ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<div class='col-12'><div class='alert alert-danger'>Không tìm thấy sản phẩm nào phù hợp với từ khóa '".htmlspecialchars($search)."'.</div></div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>