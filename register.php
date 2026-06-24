<?php
require_once 'db.php';
include 'header.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    // Mã hóa mật khẩu để bảo mật
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    // Kiểm tra xem user hoặc email đã tồn tại chưa (bỏ qua bước này cho đơn giản, vào thẳng INSERT)
    $sql = "INSERT INTO users (username, password, fullname, email) VALUES ('$username', '$password', '$fullname', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mt-4">Đăng ký tài khoản</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Họ và tên</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-success">Đăng ký</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>