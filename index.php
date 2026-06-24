<?php
// SỬA LỖI Ở ĐÂY: Thêm thư mục config/ vào trước db.php
require_once 'config/db.php'; 
include 'header.php';

// XỬ LÝ LOGIC TÌM KIẾM SẢN PHẨM
$search = "";
$sql = "SELECT * FROM products";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " WHERE name LIKE '%$search%'";
}

$sql .= " ORDER BY id DESC LIMIT 4"; // Lấy 4 sản phẩm mới nhất hoặc theo từ khóa tìm kiếm
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="text-center py-5 mb-5 text-white" style="
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?= isset($_SERVER['HTTPS']) ? 'https' : 'http' ?>://<?= $_SERVER['HTTP_HOST'] ?>/web_ban_hang/uploads/banner-chinh.png') no-repeat center center;
        background-size: cover;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        padding: 50px 20px;
    ">
        <h1 class="fw-bold display-4 mb-2" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Chào mừng đến với Shop Của Tôi</h1>
        <p class="fs-5 opacity-75 mb-0">Nơi cung cấp các sản phẩm chất lượng nhất.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="mb-0 fw-bold text-dark">Sản Phẩm Nổi Bật</h2>
        
        <form method="GET" action="index.php" class="d-flex" style="max-width: 400px; width: 100%;">
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
                        
                        <div class="card h-100 shadow-sm border-0 transition-card">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-top" style="height: 220px; width: 100%; overflow: hidden; padding: 15px;">
                                <img src="uploads/<?= $row['image'] ?>" class="img-fluid" alt="<?= htmlspecialchars($row['name']) ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
                            
                            <div class="card-body text-center d-flex flex-column p-3">
                                <h5 class="card-title fw-bold text-dark fs-6 mb-2" style="min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($row['name']) ?>
                                </h5>
                                <p class="text-danger fw-bold fs-5 mb-3"><?= number_format($row['price'], 0, ',', '.') ?> đ</p>
                                
                                <div class="mt-auto">
                                    <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary w-100 mb-2 py-1" style="font-size: 0.9rem;">Xem chi tiết</a>
                                    
                                    <div class="input-group mb-2 mx-auto" style="max-width: 130px;">
                                        <span class="input-group-text bg-light text-muted py-1 px-2" style="font-size: 0.8rem;">SL:</span>
                                        <input type="number" name="quantity" class="form-control text-center py-1 px-2" value="1" min="1" required style="font-size: 0.9rem;">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="font-size: 0.95rem;">🛒 Thêm vào giỏ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<div class='col-12'><div class='alert alert-danger shadow-sm'>Không tìm thấy sản phẩm nào phù hợp với từ khóa '".htmlspecialchars($search)."'.</div></div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>