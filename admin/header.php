<?php
session_start();
// BẢO MẬT: Kiểm tra nếu không phải admin thì đuổi về trang đăng nhập
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Nhúng file kết nối CSDL (Lùi lại 1 cấp thư mục để vào config)
require_once '../config/db.php'; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background-color: #343a40; color: white; padding-top: 20px; position: fixed; width: 250px; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 12px 20px; font-size: 16px; }
        .sidebar a:hover { background-color: #495057; color: white; border-left: 4px solid #0d6efd; }
        .content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar shadow">
            <h4 class="text-center mb-4 text-white">⚙️ ADMIN PANEL</h4>
            <a href="index.php">📊 Dashboard</a>
            <a href="product-list.php">📦 Quản lý Sản phẩm</a>
            <a href="category.php">📁 Quản lý Danh mục</a>
            <a href="order.php">🛒 Quản lý Đơn hàng</a>
            <a href="index.php#danh-sach-khach-hang">👥 Quản lý Người dùng</a>
            <hr class="text-secondary">
            <a href="../index.php" target="_blank">🌐 Xem trang Web</a>
            <a href="../logout.php" class="text-danger fw-bold">🚪 Đăng xuất</a>
        </div>

        <div class="content">