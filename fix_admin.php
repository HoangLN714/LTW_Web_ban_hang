<?php
require_once "api/config/database.php";

$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE username = 'admin'");
$stmt->bind_param("s", $hash);

if ($stmt->execute()) {
    echo "<h1>Cập nhật thành công!</h1>";
    echo "<p>Mật khẩu của tài khoản <b>admin</b> đã được đặt lại thành <b>admin123</b> và đã được mã hóa chuẩn.</p>";
    echo "<a href='login.php'>Quay lại trang đăng nhập</a>";
} else {
    echo "Có lỗi xảy ra: " . $conn->error;
}
?>
