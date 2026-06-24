<?php 
include 'header.php'; 

$message = "";

// 1. Xử lý thêm danh mục mới
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    if (!empty($name)) {
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>Thêm danh mục mới thành công!</div>";
        }
    }
}

// 2. Xử lý xóa danh mục (Khi bấm nút Xóa)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM categories WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Đã xóa danh mục thành công!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Không thể xóa danh mục này (có thể đang có sản phẩm thuộc danh mục này).</div>";
    }
}

// 3. Lấy toàn bộ danh mục để hiển thị ra bảng
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<h2 class="mb-4">📁 Quản lý Danh mục Sản phẩm</h2>
<?= $message ?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Thêm danh mục mới</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên danh mục</label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Điện thoại, Áo khoác..." required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary w-100">Thêm ngay</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 100px;">STT</th>
                            <th>Tên danh mục</th>
                            <th style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stt = 1;
                        if ($result->num_rows > 0): 
                            while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td class="text-start fw-bold"><?= $row['name'] ?></td>
                                <td>
                                    <a href="category.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                            <tr><td colspan="3" class="text-muted">Chưa có danh mục nào. Hoặc hãy thêm mới ở bên cạnh!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>