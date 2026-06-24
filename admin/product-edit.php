<?php
include 'header.php';

// 1. KIỂM TRA ID SẢN PHẨM CẦN SỬA
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>Mã sản phẩm không hợp lệ!</div>";
    include 'footer.php';
    exit();
}

$id = intval($_GET['id']);
$message = "";

// 2. XỬ LÝ KHI BẤM NÚT "CẬP NHẬT SẢN PHẨM"
if (isset($_POST['update_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']); // Lấy dữ liệu mô tả sản phẩm
    
    // Xử lý hình ảnh nếu người dùng có up ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $target = "../uploads/" . $image_name;
        
        if (move_uploaded_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Nếu up ảnh mới thành công, cập nhật cả tên ảnh mới vào SQL
            $sql_update = "UPDATE products SET name='$name', price='$price', description='$description', image='$image_name' WHERE id=$id";
        }
    } else {
        // Nếu không chọn ảnh mới, giữ nguyên ảnh cũ trong database
        $sql_update = "UPDATE products SET name='$name', price='$price', description='$description' WHERE id=$id";
    }

    if ($conn->query($sql_update) === TRUE) {
        $message = "<div class='alert alert-success'>🎉 Cập nhật sản phẩm thành công!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}

// 3. TRUY VẤN LẤY DỮ LIỆU CŨ CỦA SẢN PHẨM ĐỂ ĐỔ VÀO FORM
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Sản phẩm không tồn tại!</div>";
    include 'footer.php';
    exit();
}
$product = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ Chỉnh sửa Sản phẩm</h2>
        <a href="product-list.php" class="btn btn-secondary">⬅️ Quay lại danh sách</a>
    </div>

    <?= $message ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá bán (đ)</label>
                    <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả chi tiết sản phẩm</label>
                    <textarea name="description" class="form-control" rows="6" placeholder="Nhập mô tả thông số, tính năng sản phẩm tại đây..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Ảnh hiện tại:</small>
                        <img src="../uploads/<?= $product['image'] ?>" width="120" class="img-thumbnail bg-light" alt="">
                    </div>
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">Lưu ý: Nếu không muốn đổi ảnh, vui lòng để trống ô chọn file này.</small>
                </div>

                <button type="submit" name="update_product" class="btn btn-primary fw-bold px-4">💾 Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>