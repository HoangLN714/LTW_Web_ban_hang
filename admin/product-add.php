<?php
include 'header.php';

// 1. XỬ LÝ LỆNH XÓA SẢN PHẨM (NẾU CÓ)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);
    
    // Lấy tên ảnh cũ để xóa file vật lý trong thư mục uploads trước
    $img_res = $conn->query("SELECT image FROM products WHERE id = $id_to_delete");
    if ($img_res && $img_res->num_rows > 0) {
        $img_row = $img_res->fetch_assoc();
        $old_image = "../uploads/" . $img_row['image'];
        if (file_exists($old_image) && !empty($img_row['image'])) {
            unlink($old_image); // Xóa ảnh khỏi thư mục
        }
    }
    
    // Xóa dữ liệu sản phẩm trong Database
    $sql_delete = "DELETE FROM products WHERE id = $id_to_delete";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<div class='alert alert-success'>🎉 Đã xóa sản phẩm thành công!</div>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi khi xóa: " . $conn->error . "</div>";
    }
}

// 2. TRUY VẤN LẤY TOÀN BỘ DANH SÁCH SẢN PHẨM
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Quản lý Danh sách Sản phẩm</h2>
        <a href="product-add.php" class="btn btn-success fw-bold">➕ Thêm sản phẩm mới</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 100px;">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th style="width: 130px;">Giá bán</th>
                            <th>Mô tả chi tiết</th>
                            <th style="width: 160px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <img src="../uploads/<?= $row['image'] ?>" width="60" height="60" class="rounded border" style="object-fit: cover;" alt="">
                                    </td>
                                    <td class="text-start fw-bold"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($row['price'], 0, ',', '.') ?> đ</td>
                                    
                                    <td class="text-start text-muted" style="max-width: 250px; font-size: 0.9rem;">
                                        <?php 
                                        if (!empty($row['description'])) {
                                            $desc = htmlspecialchars($row['description']);
                                            echo (mb_strlen($desc) > 60) ? mb_substr($desc, 0, 60) . '...' : $desc;
                                        } else {
                                            echo '<span class="text-black-50 italic">Chưa có mô tả</span>';
                                        }
                                        ?>
                                    </td>
                                    
                                    <td>
                                        <a href="product-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning fw-bold text-dark me-1">✏️ Sửa</a>
                                        <a href="product-list.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này và ảnh của nó không?')">🗑️ Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted p-4">Hệ thống của bạn hiện chưa có sản phẩm nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>