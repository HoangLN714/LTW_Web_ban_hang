<?php 
include 'header.php'; 

$message = "";

// Xử lý khi Admin cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $sql_update = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    if ($conn->query($sql_update) === TRUE) {
        $message = "<div class='alert alert-success'>Cập nhật trạng thái đơn hàng #$order_id thành công!</div>";
    }
}

// Lấy danh sách tất cả đơn hàng, kèm thông tin tên người đặt (fullname) từ bảng users
$sql = "SELECT o.*, u.fullname FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>

<h2 class="mb-4">🛒 Quản lý Đơn hàng</h2>
<?= $message ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>PT Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Cập nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?= $row['id'] ?></strong></td>
                            <td class="text-start"><?= $row['fullname'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($row['total_money'], 0, ',', '.') ?> đ</td>
                            <td><span class="badge bg-secondary"><?= $row['payment_method'] ?></span></td>
                            <td>
                                <?php 
                                    if($row['status'] == 'Pending') echo '<span class="badge bg-warning text-dark">Chờ duyệt</span>';
                                    elseif($row['status'] == 'Shipping') echo '<span class="badge bg-primary">Đang giao</span>';
                                    elseif($row['status'] == 'Completed') echo '<span class="badge bg-success">Thành công</span>';
                                    else echo '<span class="badge bg-danger">Đã hủy</span>';
                                ?>
                            </td>
                            <td>
                                <form method="POST" action="" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm me-2" style="width: 130px;">
                                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                                        <option value="Shipping" <?= $row['status'] == 'Shipping' ? 'selected' : '' ?>>Đang giao</option>
                                        <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Thành công</option>
                                        <option value="Canceled" <?= $row['status'] == 'Canceled' ? 'selected' : '' ?>>Hủy đơn</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-muted p-4">Chưa có đơn hàng nào được đặt.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>