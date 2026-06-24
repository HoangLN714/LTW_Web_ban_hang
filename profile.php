<?php
// Bật session để nhận diện khách hàng đã đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';
include 'header.php';

// Bảo mật: Nếu chưa đăng nhập thì bắt quay về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 1. XỬ LÝ KHI KHÁCH BẤM NÚT LƯU THAY ĐỔI
if (isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    
    // Cập nhật vào CSDL
    $sql_update = "UPDATE users SET fullname = '$fullname', email = '$email' WHERE id = $user_id";
    
    if ($conn->query($sql_update) === TRUE) {
        $message = "<div class='alert alert-success'>🎉 Cập nhật thông tin tài khoản thành công!</div>";
        // Cập nhật lại biến hiển thị tên trên thanh Header ngay lập tức
        $_SESSION['username'] = !empty($fullname) ? $fullname : $_SESSION['username'];
    } else {
        $message = "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}

// 2. TRUY VẤN LẤY THÔNG TIN HIỆN TẠI CỦA USER
$sql = "SELECT username, fullname, email FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">👤 Thông tin cá nhân</h2>
            
            <?= $message ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tên đăng nhập (Username)</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                            <small class="text-muted">Tên đăng nhập không thể thay đổi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên của bạn</label>
                            <input type="text" name="fullname" class="form-control" 
                                   value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" 
                                   placeholder="Nhập họ và tên đầy đủ..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Địa chỉ Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                                   placeholder="Ví dụ: nguyenvan@gmail.com" required>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <a href="index.php" class="btn btn-outline-secondary w-100">Quay lại</a>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="update_profile" class="btn btn-primary w-100 fw-bold">Lưu thay đổi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>