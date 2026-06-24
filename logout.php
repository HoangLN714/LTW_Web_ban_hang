<?php
session_start();
// Xóa toàn bộ session (bao gồm thông tin đăng nhập và giỏ hàng)
session_destroy();

// Chuyển hướng về trang chủ
header("Location: index.php");
exit();
?>