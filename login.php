<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Nhúng kết nối CSDL
require_once 'config/db.php';
require_once 'db.php';
include 'header.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // So sánh mật khẩu người dùng nhập với mật khẩu đã mã hóa trong CSDL
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin vào Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                echo "<script>window.location.href='admin/index.php';</script>";
            } else {
                echo "<script>window.location.href='index.php';</script>";
            }
        } else {
            echo "<div class='alert alert-danger'>Sai mật khẩu!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Tài khoản không tồn tại!</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mt-4">Đăng nhập</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
            <a href="register.php" class="btn btn-link">Chưa có tài khoản? Đăng ký</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>