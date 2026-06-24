<?php 
// Nhúng file header của admin để kiểm tra quyền và lấy kết nối CSDL
include 'header.php'; 

// Lấy toàn bộ danh sách thành viên trong CSDL
$sql = "SELECT id, username, fullname, email, role, created_at FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<h2 class="mb-4">👥 Quản lý Người dùng</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày đăng ký</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= $row['username'] ?></strong></td>
                            <td class="text-start"><?= !empty($row['fullname']) ? $row['fullname'] : 'Chưa cập nhật' ?></td>
                            <td class="text-start"><?= !empty($row['email']) ? $row['email'] : 'Chưa cập nhật' ?></td>
                            <td>
                                <?php if ($row['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Quản trị viên (Admin)</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Khách hàng</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-muted p-4">Hệ thống chưa có thành viên nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>